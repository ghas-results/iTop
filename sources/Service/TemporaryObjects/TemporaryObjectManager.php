<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Service\TemporaryObjects;

use Combodo\iTop\Service\Base\ObjectRepository;
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
	 * @return \TemporaryObjectManager
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
	 * @param string $sObjectClass Object class
	 * @param string $sObjectKey Object key
	 * @param string $sValidatorClass Validator class
	 * @param array $aMetadata Optional meta data
	 *
	 * @return TemporaryObjectDescriptor|null
	 */
	public function CreateTemporaryObject(string $sTempId, string $sObjectClass, string $sObjectKey, string $sValidatorClass, array $aMetadata = []): ?TemporaryObjectDescriptor
	{
		return $this->oTemporaryObjectRepository->Create($sTempId, $sObjectClass, $sObjectKey, $sValidatorClass, $aMetadata);
	}

	/**
	 * DeleteAllTemporaryObjects.
	 *
	 * Remove the temporary objects descriptors attached to this temporary ID.
	 * Delete the temporary objects.
	 *
	 * @param string $sTempId
	 *
	 * @return bool
	 */
	public function DeleteAllTemporaryObjects(string $sTempId): bool
	{
		try {

			// Get temporary object descriptors
			$oDbObjectSet = $this->oTemporaryObjectRepository->SearchByTempId($sTempId, true);

			// Delete temporary objects...
			$this->DeleteTemporaryObjects($oDbObjectSet->ToArray());

			// Log
			IssueLog::Info("TemporaryObjectsManager: Invalidate all temporary objects attached to temporary id $sTempId", null, [
				'temp_id' => $sTempId,
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
	 * DeleteTemporaryObjects.
	 *
	 * Delete temporary object and his descriptor.
	 *
	 * @param array $aTemporaryObjectDescriptor
	 *
	 * @return bool
	 */
	public function DeleteTemporaryObjects(array $aTemporaryObjectDescriptor): bool
	{
		try {

			/** @var TemporaryObjectDescriptor $oTemporaryObjectDescriptor */
			foreach ($aTemporaryObjectDescriptor as $oTemporaryObjectDescriptor) {

				// Get temporary object
				$oTemporaryObject = MetaModel::GetObject($oTemporaryObjectDescriptor->Get('item_class'), $oTemporaryObjectDescriptor->Get('item_id'));

				// Delete temporary object
				$oTemporaryObject->DBDelete();

				// Remove temporary object descriptor entry
				$oTemporaryObjectDescriptor->DBDelete();
			}

			return true;
		}
		catch (Exception $e) {

			ExceptionLog::LogException($e);

			return false;
		}

	}

	/**
	 * RemoveTemporaryObjectsDescriptors.
	 *
	 * @param string $sTempId
	 *
	 * @return bool
	 */
	private function RemoveTemporaryObjectsDescriptors(string $sTempId): bool
	{
		// Prepare OQL
		$sOQL = sprintf('SELECT %s WHERE temp_id="%s"', TemporaryObjectDescriptor::class, $sTempId);

		// Log
		IssueLog::Info("TemporaryObjectsManager: Delete all temporary objects descriptors attached to temporary id $sTempId", null, [
			'temp_id' => $sTempId,
		]);

		// Delete descriptors
		return ObjectRepository::DeleteFromOql($sOQL);
	}

	/**
	 * DelayTemporaryObjectsExpiration.
	 *
	 * @param string $sTempId
	 *
	 * @return bool
	 */
	public function DelayTemporaryObjectsExpiration(string $sTempId): bool
	{
		try {

			// Create db set from db search
			$oDbObjectSet = $this->oTemporaryObjectRepository->SearchByTempId($sTempId);

			// Expiration date
			$iExpirationDate = time() + MetaModel::GetConfig()->Get(TemporaryObjectHelper::CONFIG_TEMP_LIFETIME);
			$date = new DateTime();
			$date->setTimestamp($iExpirationDate);

			// Delay objects expiration
			while ($oObject = $oDbObjectSet->Fetch()) {
				$oObject->Set('expiration_date', $iExpirationDate);
				$oObject->DBUpdate();
			}

			// Log
			IssueLog::Info("TemporaryObjectsManager: Delay all temporary objects descriptors expiration date attached to temporary id $sTempId", null, [
				'temp_id'                 => $sTempId,
				'expiration_date'         => date_format($date, 'Y-m-d H:i:s'),
				'total_temporary_objects' => $this->oTemporaryObjectRepository->CountTemporaryObjectsByTempId($sTempId),
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
	 * @param array $aTemporaryObjectDescriptors
	 * @param string $sClass
	 * @param string $sKey
	 *
	 * @return array
	 * @throws \ArchivedObjectException
	 * @throws \CoreCannotSaveObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \DeleteException
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 */
	public function RemoveTempObjectReference(array $aTemporaryObjectDescriptors, string $sClass, string $sKey): array
	{
		// Remove temp reference from array
		return array_filter($aTemporaryObjectDescriptors, static function (TemporaryObjectDescriptor $element) use ($sClass, $sKey) {
			// Matching entry
			$bFound = $element->Get('item_class') === $sClass && $element->Get('item_id') == $sKey;
			// Remove temp descriptor entry
			if ($bFound) {
				$element->DBDelete();
			}

			return !$bFound;
		});
	}
}
