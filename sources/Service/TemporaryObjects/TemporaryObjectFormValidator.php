<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Service\TemporaryObjects;

use DBObject;
use IssueLog;
use ReflectionClass;

/**
 * TemporaryObjectFormValidator.
 *
 * Handle temporary objects linked to a form.
 *
 * @since 3.1
 */
class TemporaryObjectFormValidator
{
	/** @var TemporaryObjectFormValidator|null Singleton */
	static private ?TemporaryObjectFormValidator $oSingletonInstance = null;

	/** @var TemporaryObjectManager $oTemporaryObjectManager */
	private TemporaryObjectManager $oTemporaryObjectManager;

	/** @var TemporaryObjectRepository $oTemporaryObjectRepository */
	private TemporaryObjectRepository $oTemporaryObjectRepository;

	/**
	 * GetInstance.
	 *
	 * @return TemporaryObjectFormValidator
	 */
	public static function GetInstance(): TemporaryObjectFormValidator
	{
		if (is_null(self::$oSingletonInstance)) {
			self::$oSingletonInstance = new TemporaryObjectFormValidator();
		}

		return self::$oSingletonInstance;
	}

	/**
	 * Constructor.
	 *
	 */
	public function __construct()
	{
		// Retrieve temporary object manager
		$this->oTemporaryObjectManager = TemporaryObjectManager::GetInstance();

		// Retrieve temporary object repository
		$this->oTemporaryObjectRepository = TemporaryObjectRepository::GetInstance();
	}

	/**
	 * Validate.
	 *
	 * @param string $sFormTransactionId
	 * @param DBObject $oObject
	 *
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 * @throws \ReflectionException
	 */
	public function Validate(string $sFormTransactionId, DBObject $oObject)
	{
		// Group temporary object descriptors by validator
		$aGroupByValidator = $this->oTemporaryObjectRepository->SearchTemporaryObjectDescriptionByTempIdGroupByValidator($sFormTransactionId);

		// Iterate throw validators...
		foreach ($aGroupByValidator as $aElement) {

			// Retrieve validator class
			$sValidatorClass = $aElement['grouped_by_validator'];

			// Get temporary object descriptors attached to validator
			$oDbObjectSearch = $this->oTemporaryObjectRepository->SearchByTempIdAndValidatorClass($sFormTransactionId, addslashes($sValidatorClass));

			// Initialize validator
			$oReflexionClass = new ReflectionClass($sValidatorClass);
			$oValidator = $oReflexionClass->newInstanceArgs([]);

			// Validate temporary object descriptors
			$aValidObjects = $oValidator->Validate($oObject, $oDbObjectSearch->ToArray());

			// Remove temp descriptor for valid objects
			foreach ($aValidObjects as $oValidObject) {
				$oValidObject->DBDelete();
			}
		}

		// Delete descriptor and objects
		$this->oTemporaryObjectManager->CancelAllTemporaryObjects($sFormTransactionId);

		// Log
		IssueLog::Info("TemporaryObjectFormValidator: Validate form transaction id $sFormTransactionId", null, [
			'transaction_id' => $sFormTransactionId,
		]);
	}


	/**
	 * Invalidate.
	 *
	 * @param string $sTransactionId
	 *
	 * @return bool
	 */
	public function Invalidate(string $sTransactionId): bool
	{
		$this->oTemporaryObjectManager->CancelAllTemporaryObjects($sTransactionId);

		return true;
	}
}
