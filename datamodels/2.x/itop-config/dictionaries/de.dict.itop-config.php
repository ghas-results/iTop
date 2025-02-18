<?php
// Copyright (C) 2010-2023 Combodo SARL
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>
/*
* @author ITOMIG GmbH <martin.raenker@itomig.de>

* @copyright     Copyright (C) 2023 Combodo SARL
* @licence	http://opensource.org/licenses/AGPL-3.0
*		
*/
Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Menu:ConfigEditor' => 'Konfiguration',
	'config-edit-title' => 'Konfigurations-Editor',
	'config-edit-intro' => 'Seien sie bei der Bearbeitung der Konfigurationsdatei sehr vorsichtig.',
	'config-apply' => 'Anwenden (Ctrl+S)',
	'config-apply-title' => 'Anwenden (Ctrl+S)',
	'config-cancel' => 'Zurücksetzen',
	'config-saved' => 'Erfolgreich gespeichert',
	'config-confirm-cancel' => 'Ihre Änderungen werden nicht gespeichert.',
	'config-no-change' => 'Keine Änderungen: Die Datei wurde nicht verändert.',
	'config-reverted' => 'Die Konfiguration wurde zurückgesetzt',
	'config-parse-error' => 'Zeile %2$d: %1$s.<br/>Die Datei wurde nicht aktualisiert.',
	'config-current-line' => 'Editiere Zeile: %1$s',
	'config-saved-warning-db-password' => 'Die Konfiguration wurde gespeichert. Das Backup wird NICHT funktionieren, im Datenbankpasswort sind unzulässige Zeichen enthalten.',
	'config-error-transaction' => 'Error: invalid Transaction ID. The configuration was <b>NOT</b> modified.~~',
	'config-error-file-changed' => 'Error: The Configuration file has changed since you opened it and cannot be saved. Refresh and apply your changes again.~~',
	'config-not-allowed-in-demo' => 'Sorry, '.ITOP_APPLICATION_SHORT.' is in <b>demonstration mode</b>: the configuration file cannot be edited.~~',
	'config-interactive-not-allowed' => ITOP_APPLICATION_SHORT.' interactive edition of the configuration as been disabled. See <code>\'config_editor\' => \'disabled\'</code> in the configuration file.~~',
));
