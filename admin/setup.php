<?php
/* Copyright (C) 2004-2017 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2022 SuperAdmin <maxime@gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

/**
 * \file    usernavhistory/admin/setup.php
 * \ingroup usernavhistory
 * \brief   UserNavHistory setup page.
 */

// Load Dolibarr environment
$res = 0;
// Try main.inc.php into web root known defined into CONTEXT_DOCUMENT_ROOT (not always defined)
if (!$res && !empty($_SERVER["CONTEXT_DOCUMENT_ROOT"])) {
	$res = @include $_SERVER["CONTEXT_DOCUMENT_ROOT"]."/main.inc.php";
}
// Try main.inc.php into web root detected using web root calculated from SCRIPT_FILENAME
$tmp = empty($_SERVER['SCRIPT_FILENAME']) ? '' : $_SERVER['SCRIPT_FILENAME']; $tmp2 = realpath(__FILE__); $i = strlen($tmp) - 1; $j = strlen($tmp2) - 1;
while ($i > 0 && $j > 0 && isset($tmp[$i]) && isset($tmp2[$j]) && $tmp[$i] == $tmp2[$j]) {
	$i--; $j--;
}
if (!$res && $i > 0 && file_exists(substr($tmp, 0, ($i + 1))."/main.inc.php")) {
	$res = @include substr($tmp, 0, ($i + 1))."/main.inc.php";
}
if (!$res && $i > 0 && file_exists(dirname(substr($tmp, 0, ($i + 1)))."/main.inc.php")) {
	$res = @include dirname(substr($tmp, 0, ($i + 1)))."/main.inc.php";
}
// Try main.inc.php using relative path
if (!$res && file_exists("../../main.inc.php")) {
	$res = @include "../../main.inc.php";
}
if (!$res && file_exists("../../../main.inc.php")) {
	$res = @include "../../../main.inc.php";
}
if (!$res) {
	die("Include of main fails");
}

global $langs, $user;

// Libraries
require_once DOL_DOCUMENT_ROOT."/core/lib/admin.lib.php";
require_once '../lib/usernavhistory.lib.php';
//require_once "../class/myclass.class.php";

// Translations
$langs->loadLangs(array("admin", "usernavhistory@usernavhistory"));

// Initialize technical object to manage hooks of page. Note that conf->hooks_modules contains array of hook context
$hookmanager->initHooks(array('usernavhistorysetup', 'globalsetup'));

// Access control
if (!$user->admin) {
	accessforbidden();
}

// Parameters
$action = GETPOST('action', 'aZ09');
$backtopage = GETPOST('backtopage', 'alpha');
$modulepart = GETPOST('modulepart', 'aZ09');	// Used by actions_setmoduleoptions.inc.php

$value = GETPOST('value', 'alpha');
$label = GETPOST('label', 'alpha');
$scandir = GETPOST('scan_dir', 'alpha');
$type = 'myobject';


require_once DOL_DOCUMENT_ROOT.'/core/class/html.formsetup.class.php';
$formSetup = new FormSetup($db);

// you can use the param convertor
$arrayofparameters = array();
$formSetup->addItemsFromParamsArray($arrayofparameters);

// or use the new system see exemple as follow (or use both because you can ;-) )

/*
// Hôte
$item = $formSetup->newItem('NO_PARAM_JUST_TEXT');
$item->fieldOverride = (empty($_SERVER['HTTPS']) ? 'http://' : 'https://') . $_SERVER['HTTP_HOST'];
$item->cssClass = 'minwidth500';

// Setup conf USERNAVHISTORY_MYPARAM1 as a simple string input
$item = $formSetup->newItem('USERNAVHISTORY_MYPARAM1');

// Setup conf USERNAVHISTORY_MYPARAM1 as a simple textarea input but we replace the text of field title
$item = $formSetup->newItem('USERNAVHISTORY_MYPARAM2');
$item->nameText = $item->getNameText().' more html text ';

// Setup conf USERNAVHISTORY_MYPARAM3
$item = $formSetup->newItem('USERNAVHISTORY_MYPARAM3');
$item->setAsThirdpartyType();

// Setup conf USERNAVHISTORY_MYPARAM4 : exemple of quick define write style
$formSetup->newItem('USERNAVHISTORY_MYPARAM4')->setAsYesNo();

// Setup conf USERNAVHISTORY_MYPARAM5
$formSetup->newItem('USERNAVHISTORY_MYPARAM5')->setAsEmailTemplate('thirdparty');

// Setup conf USERNAVHISTORY_MYPARAM6
$formSetup->newItem('USERNAVHISTORY_MYPARAM6')->setAsSecureKey()->enabled = 0; // disabled

// Setup conf USERNAVHISTORY_MYPARAM7
$formSetup->newItem('USERNAVHISTORY_MYPARAM7')->setAsProduct();
*/

// Setup conf USERNAVHISTORY_MYPARAM1 as a simple string input
$item = $formSetup->newItem('USERNAVHISTORY_MAX_ELEMENT_NUMBER');
$item->fieldAttr['type'] = 'number';
$item->fieldAttr['min'] = '0';
$item->fieldAttr['step'] = '1';


/*
 * Actions
 */

include DOL_DOCUMENT_ROOT.'/core/actions_setmoduleoptions.inc.php';


/*
 * View
 */

$form = new Form($db);

$help_url = '';
$page_name = "UserNavHistorySetup";

llxHeader('', $langs->trans($page_name), $help_url);

// Subheader
$linkback = '<a href="'.($backtopage ? $backtopage : DOL_URL_ROOT.'/admin/modules.php?restore_lastsearch_values=1').'">'.$langs->trans("BackToModuleList").'</a>';

print load_fiche_titre($langs->trans($page_name), $linkback, 'title_setup');

// Configuration header
$head = usernavhistoryAdminPrepareHead();
print dol_get_fiche_head($head, 'settings', $langs->trans($page_name), -1, "usernavhistory@usernavhistory");

// Setup page goes here
echo '<span class="opacitymedium">'.$langs->trans("UserNavHistorySetupPage").'</span><br><br>';


if ($action == 'edit') {

	print $formSetup->generateOutput(true);
	print '<br>';
} else {
	if (!empty($formSetup->items)) {
		print $formSetup->generateOutput();

		print '<div class="tabsAction">';
		print '<a class="butAction" href="'.$_SERVER["PHP_SELF"].'?action=edit&token='.newToken().'">'.$langs->trans("Modify").'</a>';
		print '</div>';
	}
	else {
		print '<br>'.$langs->trans("NothingToSetup");
	}
}

// Page end
print dol_get_fiche_end();

llxFooter();
$db->close();
