<?php
/* Copyright (C) 2026 ATM Consulting x Les Métiers du Bâtiment <developpeur@lesmetiersdubatiment.fr>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

/**
 * \file    usernavhistory/admin/about.php
 * \ingroup usernavhistory
 * \brief   About page of module UserNavHistory.
 */

$res = 0;
if (!$res && !empty($_SERVER['CONTEXT_DOCUMENT_ROOT'])) {
	$res = @include $_SERVER['CONTEXT_DOCUMENT_ROOT'].'/main.inc.php';
}
$tmp = empty($_SERVER['SCRIPT_FILENAME']) ? '' : $_SERVER['SCRIPT_FILENAME'];
$tmp2 = realpath(__FILE__);
$i = strlen($tmp) - 1;
$j = strlen($tmp2) - 1;
while ($i > 0 && $j > 0 && isset($tmp[$i]) && isset($tmp2[$j]) && $tmp[$i] == $tmp2[$j]) {
	$i--;
	$j--;
}
if (!$res && $i > 0 && file_exists(substr($tmp, 0, ($i + 1)).'/main.inc.php')) {
	$res = @include substr($tmp, 0, ($i + 1)).'/main.inc.php';
}
if (!$res && $i > 0 && file_exists(dirname(substr($tmp, 0, ($i + 1))).'/main.inc.php')) {
	$res = @include dirname(substr($tmp, 0, ($i + 1))).'/main.inc.php';
}
if (!$res && file_exists('../../main.inc.php')) {
	$res = @include '../../main.inc.php';
}
if (!$res && file_exists('../../../main.inc.php')) {
	$res = @include '../../../main.inc.php';
}
if (!$res) {
	die('Include of main fails');
}

require_once DOL_DOCUMENT_ROOT.'/core/lib/admin.lib.php';
require_once __DIR__.'/../lib/usernavhistory.lib.php';
require_once __DIR__.'/../core/modules/modUserNavHistory.class.php';

$langs->loadLangs(array('admin', 'usernavhistory@usernavhistory'));

if (empty($user->admin)) {
	accessforbidden();
}

$backtopage = GETPOST('backtopage', 'alpha');
$moduleDescriptor = new modUserNavHistory($db);
$title = $langs->trans('UserNavHistoryAbout');

llxHeader('', $title);

$linkback = '<a href="'.($backtopage ? $backtopage : DOL_URL_ROOT.'/admin/modules.php?restore_lastsearch_values=1').'">'.$langs->trans('BackToModuleList').'</a>';
print load_fiche_titre($title, $linkback, 'info');

$head = usernavhistoryAdminPrepareHead();
print dol_get_fiche_head($head, 'about', $title, -1, 'usernavhistory@usernavhistory');

print '<div class="underbanner opacitymedium">'.$langs->trans('UserNavHistoryAboutPage').'</div><br>';
print '<div class="fichecenter">';

print '<div class="fichehalfleft"><div class="div-table-responsive-no-min">';
print '<table class="noborder centpercent">';
print '<tr class="liste_titre"><th colspan="2">'.$langs->trans('AboutGeneralInformation').'</th></tr>';
print '<tr class="oddeven"><td class="titlefield">'.$langs->trans('ModuleName').'</td><td>'.$langs->trans('ModuleUserNavHistoryName').'</td></tr>';
print '<tr class="oddeven"><td>'.$langs->trans('Version').'</td><td>'.dol_escape_htmltag($moduleDescriptor->version).'</td></tr>';
print '<tr class="oddeven"><td>'.$langs->trans('Editor').'</td><td>'.dol_escape_htmltag($moduleDescriptor->editor_name).'</td></tr>';
print '<tr class="oddeven"><td>'.$langs->trans('Description').'</td><td>'.$langs->trans($moduleDescriptor->description).'</td></tr>';
print '<tr class="oddeven"><td>'.$langs->trans('Compatibility').'</td><td>'.$langs->trans('UserNavHistoryCompatibilityValue').'</td></tr>';
print '<tr class="oddeven"><td>'.$langs->trans('Dependencies').'</td><td>'.$langs->trans('NoRequiredDependency').'</td></tr>';
print '<tr class="oddeven"><td>'.$langs->trans('License').'</td><td>GPLv3+</td></tr>';
print '</table>';
print '</div></div>';

print '<div class="fichehalfright"><div class="div-table-responsive-no-min">';
print '<table class="noborder centpercent">';
print '<tr class="liste_titre"><th>'.$langs->trans('MainFeatures').'</th></tr>';
print '<tr class="oddeven"><td><ul class="paddingleft">';
print '<li>'.$langs->trans('AboutFeatureHistory').'</li>';
print '<li>'.$langs->trans('AboutFeatureResponsive').'</li>';
print '<li>'.$langs->trans('AboutFeatureMulticompany').'</li>';
print '</ul></td></tr>';
print '<tr class="liste_titre"><th>'.$langs->trans('UsefulLinks').'</th></tr>';
print '<tr class="oddeven"><td><a href="https://github.com/mapiolca/usernavhistory" target="_blank" rel="noopener">'.$langs->trans('DocumentationAndSourceCode').'</a></td></tr>';
print '<tr class="oddeven"><td><a href="'.dol_escape_htmltag($moduleDescriptor->editor_url).'" target="_blank" rel="noopener">'.dol_escape_htmltag($moduleDescriptor->editor_url).'</a></td></tr>';
print '</table>';
print '</div></div>';

print '</div>';
print dol_get_fiche_end();
llxFooter();
$db->close();
