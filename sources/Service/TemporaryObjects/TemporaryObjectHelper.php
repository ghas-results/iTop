<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Service\TemporaryObjects;

use MetaModel;

/**
 * TemporaryObjectHelper.
 *
 * Helper with useful functions.
 *
 * @since 3.1
 */
class TemporaryObjectHelper
{
    const CONFIG_FORCE = 'external_keys.force_temporary_object_creation';
    const CONFIG_TEMP_LIFETIME = 'external_keys.temporary_object_lifetime';
    const CONFIG_WATCHDOG_INTERVAL = 'external_keys.watchdog_interval';


    /**
     * GetWatchDogJS.
     *
     * @param string $sTempId
     *
     * @return string
     */
    static public function GetWatchDogJS(string $sTempId): string
    {
        $iWatchdogInterval = MetaModel::GetConfig()->Get(self::CONFIG_WATCHDOG_INTERVAL);

        return <<<JS
			window.setInterval(function() {
				$.post(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php?route=temporary_object.watch_dog', {temp_id: '$sTempId'});
			}, $iWatchdogInterval * 1000)
JS;
    }

}
