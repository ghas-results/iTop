<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Service\TemporaryObjects;

use DateTime;
use DBObject;
use Exception;
use ExceptionLog;
use IssueLog;
use MetaModel;
use TemporaryObjectDescriptor;

/**
 * TemporaryObjectManager.
 *
 * Manager class to perform global temporary objects tasks.
 *
 * @since 3.1
 */
class TemporaryObjectManager
{
	/** @var TemporaryObjectManager|null Singleton */
	static private ?TemporaryObjectManager $oSingletonInstance = null;

	/** @var TemporaryObjectRepository $oTemporaryObjectRepository */
	private TemporaryObjectRepository $oTemporaryObjectRepository;

	/**
	 * GetInstance.
	 *
	 * @return TemporaryObjectManager
	 */
	public static function GetInstance(): TemporaryObjectManager
	{
		if (is_null(self::$oSingletonInstance)) {
			self::$oSingletonInstance = new TemporaryObjectManager();
		}

		return self::$oSingletonInstance;
	}

	/**
	 * Constructor.
	 *
	 */
	private function __construct()
	{
		// Initialize temporary object repository
		$this->oTemporaryObjectRepository = TemporaryObjectRepository::GetInstance();
	}

	/**
	 * CreateTemporaryObject.
	 *
	 * @param string $sTempId Temporary id context for the temporary object
	 * @param string $sObjectClass Temporary object class
	 * @param string $sObjectKey Temporary object key
	 * @param string $sOperation temporary operation on file TemporaryObjectHelper::OPERATION_CREATE or TemporaryObjectHelper::OPERATION_DELETE
	 *
	 * @return TemporaryObjectDescriptor|null
	 */
	public function CreateTemporaryObject(string $sTempId, string $sObjectClass, string $sObjectKey, string $sOperation): ?TemporaryObjectDescriptor
	{
		$result = $this->oTemporaryObjectRepository->Create($sTempId, $sObjectClass, $sObjectKey, $sOperation);

		// Log
		IssueLog::Info("TemporaryObjectsManager: Create a temporary object attached to temporary id $sTempId", null, [
			'temp_id'    => $sTempId,
			'item_class' => $sObjectClass,
			'item_id'    => $sObjectKey,
			'succeeded'  => $result != null,
		]);

		return $result;
	}

	/**
	 * Cancel the ongoing operation (create or delete) on all the temporary objects impacted by this transaction id
	 *
	 * @param string $sTransactionId form transaction id
	 *
	 * @return bool true if success
	 */
	public function CancelAllTemporaryObjects(string $sTransactionId): bool
	{
		try {
			// Get temporary object descriptors
			$oDbObjectSet = $this->oTemporaryObjectRepository->SearchByTempId($sTransactionId, true);

			// Cancel temporary objects...
			$bResult = $this->CancelTemporaryObjects($oDbObjectSet->ToArray());

			// Log
			IssueLog::Info("TemporaryObjectsManager: Cancel all temporary objects attached to temporary id $sTransactionId", null, [
				'temp_id'   => $sTransactionId,
				'succeeded' => $bResult,
			]);

			// return operation success
			return true;
		}
		catch (Exception $e) {
			ExceptionLog::LogException($e);

			return false;
		}

	}

	/**
	 * Cancel the ongoing operation (create or delete) on the given temporary objects
	 *
	 * @param array $aTemporaryObjectDescriptor
	 *
	 * @return bool true if success
	 */
	private function CancelTemporaryObjects(array $aTemporaryObjectDescriptor): bool
	{
		try {
			// All operations succeeded
			$bResult = true;

			/** @var TemporaryObjectDescriptor $oTemporaryObjectDescriptor */
			foreach ($aTemporaryObjectDescriptor as $oTemporaryObjectDescriptor) {

				// Refuse the modifications
				if (!$this->CancelTemporaryObject($oTemporaryObjectDescriptor)) {
					$bResult = false;
				}
			}

			return $bResult;
		}
		catch (Exception $e) {

			ExceptionLog::LogException($e);

			return false;
		}

	}


	/**
	 * Extends the temporary object descriptor lifetime
	 *
	 * @param string $sTransactionId
	 *
	 * @return bool
	 */
	public function DelayTemporaryObjectsExpiration(string $sTransactionId): bool
	{
		try {
			// Create db set from db search
			$oDbObjectSet = $this->oTemporaryObjectRepository->SearchByTempId($sTransactionId);

			// Expiration date
			$iExpirationDate = time() + MetaModel::GetConfig()->Get(TemporaryObjectHelper::CONFIG_TEMP_LIFETIME);

			// Delay objects expiration
			while ($oObject = $oDbObjectSet->Fetch()) {
				$oObject->Set('expiration_date', $iExpirationDate);
				$oObject->DBUpdate();
			}

			// Log
			$date = new DateTime();
			$date->setTimestamp($iExpirationDate);
			IssueLog::Debug("TemporaryObjectsManager: Delay all temporary objects descriptors expiration date attached to temporary id $sTransactionId", null, [
				'temp_id'                 => $sTransactionId,
				'expiration_date'         => date_format($date, 'Y-m-d H:i:s'),
				'total_temporary_objects' => $this->oTemporaryObjectRepository->CountTemporaryObjectsByTempId($sTransactionId),
			]);

			// return operation success
			return true;
		}
		catch (Exception $e) {
			ExceptionLog::LogException($e);

			return false;
		}
	}

	/**
	 * Accept all the temporary objects operations
	 *
	 * @param string $sTransactionId
	 *
	 * @return void
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 * @throws \OQLException
	 */
	private function FinalizeTemporaryObjects(string $sTransactionId)
	{
		// All operations succeeded
		$bResult = true;

		// Get temporary object descriptors
		$oDBObjectSet = $this->oTemporaryObjectRepository->SearchByTempId($sTransactionId, true);

		// Iterate throw descriptors...
		/** @var TemporaryObjectDescriptor $oTemporaryObjectDescriptor */
		while ($oTemporaryObjectDescriptor = $oDBObjectSet->Fetch()) {

			// Retrieve attributes values
			$sHostClass = $oTemporaryObjectDescriptor->Get('host_class');
			$sHostId = $oTemporaryObjectDescriptor->Get('host_id');

			// No host object
			if ($sHostId === 0) {
				$bResult = $bResult && $this->CancelTemporaryObject($oTemporaryObjectDescriptor);
				continue;
			}

			// Host object pointed by descriptor doesn't exist anymore
			$oHostObject = MetaModel::GetObject($sHostClass, $sHostId, false);
			if (is_null($oHostObject)) {
				$bResult = $bResult && $this->CancelTemporaryObject($oTemporaryObjectDescriptor);
				continue;
			}

			// Otherwise confirm
			$bResult = $bResult && $this->ConfirmTemporaryObject($oTemporaryObjectDescriptor);
		}

		// Log
		IssueLog::Info("TemporaryObjectsManager: Finalize all temporary objects attached to temporary id $sTransactionId", null, [
			'temp_id'   => $sTransactionId,
			'succeeded' => $bResult,
		]);

	}

	/**
	 * Accept operation on the given temporary object
	 *
	 * @param TemporaryObjectDescriptor $oTemporaryObjectDescriptor
	 *
	 * @return bool
	 */
	private function ConfirmTemporaryObject(TemporaryObjectDescriptor $oTemporaryObjectDescriptor): bool
	{
		try {

			// Retrieve attributes values
			$sOperation = $oTemporaryObjectDescriptor->Get('operation');
			$sItemClass = $oTemporaryObjectDescriptor->Get('item_class');
			$sItemId = $oTemporaryObjectDescriptor->Get('item_id');

			if ($sOperation === TemporaryObjectHelper::OPERATION_DELETE) {

				// Get temporary object
				$oTemporaryObject = MetaModel::GetObject($sItemClass, $sItemId);

				// Delete temporary object
				$oTemporaryObject->DBDelete();
			}

			// Remove temporary object descriptor entry
			$oTemporaryObjectDescriptor->DBDelete();

			// Log
			IssueLog::Debug("TemporaryObjectsManager: Confirm temporary object attached", null, [
				'operation'  => $sOperation,
				'item_class' => $sItemClass,
				'item_id'    => $sItemId,
			]);

			return true;
		}
		catch (Exception $e) {

			ExceptionLog::LogException($e);

			return false;
		}
	}

	/**
	 * CancelTemporaryObject.
	 *
	 * @param TemporaryObjectDescriptor $oTemporaryObjectDescriptor
	 *
	 * @return bool
	 */
	private function CancelTemporaryObject(TemporaryObjectDescriptor $oTemporaryObjectDescriptor): bool
	{
		try {

			// Retrieve attributes values
			$sOperation = $oTemporaryObjectDescriptor->Get('operation');
			$sItemClass = $oTemporaryObjectDescriptor->Get('item_class');
			$sItemId = $oTemporaryObjectDescriptor->Get('item_id');

			if ($sOperation === TemporaryObjectHelper::OPERATION_CREATE) {

				// Get temporary object
				$oTemporaryObject = MetaModel::GetObject($sItemClass, $sItemId);

				// Delete temporary object
				$oTemporaryObject->DBDelete();
			}

			// Remove temporary object descriptor entry
			$oTemporaryObjectDescriptor->DBDelete();

			return true;
		}
		catch (Exception $e) {

			ExceptionLog::LogException($e);

			return false;
		}
	}

	/**
	 * Handle temporary objects.
	 *
	 * @param \DBObject $oDBObject
	 * @param array $aContext
	 *
	 * @return void
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 * @throws \OQLException
	 */
	public function HandleTemporaryObjects(DBObject $oDBObject, array $aContext)
	{
		if (array_key_exists('create', $aContext)) {

			// Retrieve context information
			$aContextCreation = $aContext['create'];
			$sTransactionId = $aContextCreation['transaction_id'] ?? null;
			$sHostClass = $aContextCreation['host_class'] ?? null;
			$sHostAttCode = $aContextCreation['host_att_code'] ?? null;

			// Security
			if (is_null($sTransactionId) || is_null($sHostClass) || is_null($sHostAttCode)) {
				return;
			}

			// Get host class attribute definition
			try {
				$oAttDef = MetaModel::GetAttributeDef($sHostClass, $sHostAttCode);
			}
			catch (Exception $e) {
				ExceptionLog::LogException($e);

				return;
			}

			// If creation as temporary object requested or force for all objects
			if (($oAttDef->IsParam('create_temporary_object') && $oAttDef->Get('create_temporary_object'))
				|| MetaModel::GetConfig()->Get(TemporaryObjectHelper::CONFIG_FORCE)) {

				TemporaryObjectManager::GetInstance()->CreateTemporaryObject($sTransactionId, get_class($oDBObject), $oDBObject->GetKey(), TemporaryObjectHelper::OPERATION_CREATE);
			}
		}
		if (array_key_exists('finalize', $aContext)) {

			// Retrieve context information
			$aContextFinalize = $aContext['finalize'];
			$sTransactionId = $aContextFinalize['transaction_id'] ?? null;

			// validate temporary objects
			$this->FinalizeTemporaryObjects($sTransactionId);
		}
	}
}
