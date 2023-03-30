<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Service\TemporaryObjects;

use iBackgroundProcess;
use MetaModel;

/**
 * TemporaryObjectsGarbageCollector.
 *
 * Background task to collect and garbage expired temporary objects..
 *
 * @since 3.1
 */
class TemporaryObjectGarbageCollector implements iBackgroundProcess
{
	/** @var int Garbage collection interval */
	private int $iGarbageInterval;

	/** @var TemporaryObjectManager */
	private TemporaryObjectManager $oTemporaryObjectManager;

	/**
	 * Constructor.
	 *
	 */
	public function __construct()
	{
		// Retrieve service configuration
		$this->iGarbageInterval = MetaModel::GetConfig()->Get(TemporaryObjectHelper::CONFIG_GARBAGE_INTERVAL);

		// Retrieve service dependencies
		$this->oTemporaryObjectManager = TemporaryObjectManager::GetInstance();
	}

	/** @inheritDoc * */
	public function GetPeriodicity()
	{
		return $this->iGarbageInterval;
	}

	/** @inheritDoc * */
	public function Process($iUnixTimeLimit)
	{
		// Garbage temporary objects
		$this->oTemporaryObjectManager->GarbageExpiredTemporaryObjects();
	}
}