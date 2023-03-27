<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Controller;

use Combodo\iTop\Controller\AbstractController;
use JsonPage;
use TemporaryObjectManager;
use utils;

/**
 *
 * @since 3.1
 */
class TemporaryObjectController extends AbstractController
{
	public const ROUTE_NAMESPACE = 'temporary_object';

	/** @var \TemporaryObjectManager Temporary object manager */
	private TemporaryObjectManager $oTemporaryObjectManager;

	/**
	 * Constructor.
	 *
	 */
	public function __construct()
	{
		// Router service injection ???
		$this->oTemporaryObjectManager = TemporaryObjectManager::GetInstance();
	}

	/**
	 * OperationWatchDog.
	 *
	 * Watchdog for delaying expiration date of temporary objects linked to the provided temp id.
	 *
	 * @return JsonPage
	 */
	public function OperationWatchDog(): JsonPage
	{
		$oPage = new JsonPage();

		// Retrieve temp id
		$sTempId = utils::ReadParam('temp_id', '', false, utils::ENUM_SANITIZATION_FILTER_STRING);

		// Delay temporary objects expiration
		$this->oTemporaryObjectManager->DelayTemporaryObjectsExpiration($sTempId);

		return $oPage->SetData([
			'success' => true,
		]);
	}
}