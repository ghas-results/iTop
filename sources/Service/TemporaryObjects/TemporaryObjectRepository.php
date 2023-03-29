<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Service\TemporaryObjects;

use CMDBSource;
use DBObjectSet;
use DBSearch;
use Exception;
use ExceptionLog;
use Expression;
use IssueLog;
use MetaModel;
use TemporaryObjectDescriptor;

/**
 * TemporaryObjectRepository.
 *
 * Repository class to perform ORM tasks.
 *
 * @since 3.1
 */
class TemporaryObjectRepository
{
	/** @var TemporaryObjectRepository|null Singleton */
	static private ?TemporaryObjectRepository $oSingletonInstance = null;

	/**
	 * GetInstance.
	 *
	 * @return TemporaryObjectRepository
	 */
	public static function GetInstance(): TemporaryObjectRepository
	{
		if (is_null(self::$oSingletonInstance)) {
			self::$oSingletonInstance = new TemporaryObjectRepository();
		}

		return self::$oSingletonInstance;
	}

	/**
	 * Constructor.
	 *
	 */
	private function __construct()
	{
	}

	/**
	 * Create.
	 *
	 * @param string $sTempId Temporary id
	 * @param string $sObjectClass Object class
	 * @param string $sObjectKey Object key
	 * @param string $sValidatorClass Validator class
	 * @param array $aMetadata Optional meta data
	 *
	 * @return TemporaryObjectDescriptor|null
	 */
	public function Create(string $sTempId, string $sObjectClass, string $sObjectKey, string $sValidatorClass, array $aMetadata = []): ?TemporaryObjectDescriptor
	{
		try {

			// Create a temporary object descriptor
			/** @var \TemporaryObjectDescriptor $oTemporaryObjectDescriptor */
			$oTemporaryObjectDescriptor = MetaModel::NewObject(TemporaryObjectDescriptor::class, [
				'operation'       => 'create',
				'temp_id'         => $sTempId,
				'expiration_date' => time() + MetaModel::GetConfig()->Get(TemporaryObjectHelper::CONFIG_TEMP_LIFETIME),
				'item_class'      => $sObjectClass,
				'item_id'         => $sObjectKey,
				'validator_class' => $sValidatorClass,
				'metadata'        => json_encode($aMetadata),
			]);
			$oTemporaryObjectDescriptor->DBInsert();

			// Log
			IssueLog::Info("TemporaryObjectsManager: Create a temporary object attached to transaction id $sTempId", null, [
				'transaction_id'  => $sTempId,
				'item_class'      => $sObjectClass,
				'item_id'         => $sObjectKey,
				'validator_class' => $sValidatorClass,
			]);

			return $oTemporaryObjectDescriptor;
		}
		catch (Exception $e) {

			ExceptionLog::LogException($e);

			return null;
		}
	}

	public function Delete(string $sTempId, string $sObjectClass, string $sObjectKey, string $sValidatorClass, array $aMetadata = []): ?TemporaryObjectDescriptor
	{
		return null;
	}

	/**
	 * SearchByTempId.
	 *
	 * @param string $sTempId temporary id
	 * @param bool $bReverseOrder reverse order of result
	 *
	 * @return \DBObjectSet
	 * @throws \MySQLException
	 * @throws \OQLException
	 */
	public function SearchByTempId(string $sTempId, bool $bReverseOrder = false): DBObjectSet
	{
		// Prepare OQL
		$sOQL = sprintf('SELECT `%s` WHERE temp_id=:temp_id', TemporaryObjectDescriptor::class);

		// Create db search
		$oDbObjectSearch = DBSearch::FromOQL($sOQL);

		// Create db set from db search
		$oDbObjectSet = new DBObjectSet($oDbObjectSearch, [], [
			'temp_id' => $sTempId,
		]);

		// Reverse order
		if ($bReverseOrder) {
			$oDbObjectSet->SetOrderBy([
				'id' => false,
			]);
		}

		return $oDbObjectSet;
	}

	/**
	 * SearchByTempIdAndValidatorClass.
	 *
	 * @param string $sTempId
	 * @param string $sValidatorClass
	 *
	 * @return DBObjectSet|null
	 */
	public function SearchByTempIdAndValidatorClass(string $sTempId, string $sValidatorClass): ?DBObjectSet
	{
		try {

			// Prepare OQL
			$sOQL = sprintf('SELECT `%s` WHERE temp_id=:temp_id AND validator_class=:validator_class',
				TemporaryObjectDescriptor::class);

			// Create db search
			$oDbObjectSearch = DBSearch::FromOQL($sOQL);

			// Create db set from db search
			return new DBObjectSet($oDbObjectSearch, [], [
				'temp_id'         => $sTempId,
				'validator_class' => $sValidatorClass,
			]);
		}
		catch (Exception $e) {

			ExceptionLog::LogException($e);

			return null;
		}
	}

	/**
	 * SearchByTempIdAndItemClassAndItemId.
	 *
	 * @param string $sTempId
	 * @param string $sItemClass
	 * @param string $sItemId
	 *
	 * @return TemporaryObjectDescriptor|null
	 */
	public function SearchByTempIdAndItemClassAndItemId(string $sTempId, string $sItemClass, string $sItemId): ?TemporaryObjectDescriptor
	{
		try {

			// Prepare OQL
			$sOQL = sprintf('SELECT `%s` WHERE temp_id=:temp_id AND item_class=:item_class AND item_id=:item_id',
				TemporaryObjectDescriptor::class);

			// Create db search
			$oDbObjectSearch = DBSearch::FromOQL($sOQL);

			// Create db set from db search
			return new DBObjectSet($oDbObjectSearch, [], [
				'temp_id'    => $sTempId,
				'item_class' => $sItemClass,
				'item_id'    => $sItemId,
			]);
		}
		catch (Exception $e) {

			ExceptionLog::LogException($e);

			return null;
		}
	}

	/**
	 * SearchTemporaryObjectDescriptionByTempIdGroupByValidator.
	 *
	 * @param string $sTempId
	 *
	 * @return array
	 * @throws \MySQLException
	 * @throws \OQLException
	 */
	public function SearchTemporaryObjectDescriptionByTempIdGroupByValidator(string $sTempId): array
	{
		// Prepare OQL
		$sOQL = sprintf('SELECT `%s` AS TMP WHERE temp_id=:temp_id', TemporaryObjectDescriptor::class);

		// Create db search
		$oDbObjectSearch = DBSearch::FromOQL($sOQL);

		// Group by validator
		$oFieldExp = Expression::FromOQL('TMP.validator_class');
		$sQuery = $oDbObjectSearch->MakeGroupByQuery([
			'temp_id' => $sTempId,
		], array('grouped_by_validator' => $oFieldExp), true,);

		return CMDBSource::QueryToArray($sQuery, MYSQLI_ASSOC);
	}


	/**
	 * CountTemporaryObjectsByTempId.
	 *
	 * @param string $sTempId
	 *
	 * @return int
	 */
	public function CountTemporaryObjectsByTempId(string $sTempId): int
	{
		try {

			$oDbObjectSet = $this->SearchByTempId($sTempId);

			// return operation success
			return $oDbObjectSet->count();
		}
		catch (Exception $e) {

			ExceptionLog::LogException($e);

			return -1;
		}
	}

}
