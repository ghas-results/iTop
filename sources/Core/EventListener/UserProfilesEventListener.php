<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Core\EventListener;

use Combodo\iTop\Service\Events\EventData;
use Combodo\iTop\Service\Events\EventService;
use Combodo\iTop\Service\Events\iEventServiceSetup;
use Exception;
use IssueLog;
use LogChannels;

/**
 * Class UserProfilesEventListener
 *
 * @package Combodo\iTop\Core\EventListener
 * @since 3.1 N°5324
 */
class UserProfilesEventListener implements iEventServiceSetup
{
	/**
	 * @inheritDoc
	 */
	public function RegisterEventsAndListeners()
	{
		$callback = [$this, 'OnUserProfileLinkChange'];
		$aEventSource = [\User::class, \UserExternal::class, \UserInternal::class];

		EventService::RegisterListener(
			"EVENT_DB_CREATE_DONE",
			$callback,
			$aEventSource
		);

		EventService::RegisterListener(
			"EVENT_DB_UPDATE_DONE",
			$callback,
			$aEventSource
		);
	}

	public function OnUserProfileLinkChange(EventData $oEventData): void {
		/** @var \User $oObject */
		$oUser = $oEventData->Get('object');

		try {
			$oUser->CheckProfiles();
		} catch (Exception $oException) {
			IssueLog::Error('Exception occurred during calling User->CheckProfiles', LogChannels::DM_CRUD, [
				'user_class' => get_class($oUser),
				'user_id' => $oUser->GetKey(),
				'exception_message' => $oException->getMessage(),
				'exception_stacktrace' => $oException->getTraceAsString(),
			]);
		}
	}

}
