<?php
/* Copyright (C) 2026 ATM Consulting x Les Métiers du Bâtiment <developpeur@lesmetiersdubatiment.fr>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

/**
 * \file    usernavhistory/admin/compatibility.php
 * \ingroup usernavhistory
 * \brief   Compatibility status page for UserNavHistory.
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
require_once __DIR__.'/../class/usernavhistorycompatibility.class.php';

$langs->loadLangs(array('admin', 'usernavhistory@usernavhistory'));

if (empty($user->admin)) {
	accessforbidden();
}

$backtopage = GETPOST('backtopage', 'alpha');
$title = $langs->trans('UserNavHistoryCompatibility');

llxHeader('', $title);

$linkback = '<a href="'.($backtopage ? $backtopage : DOL_URL_ROOT.'/admin/modules.php?restore_lastsearch_values=1').'">'.$langs->trans('BackToModuleList').'</a>';
print load_fiche_titre($title, $linkback, 'title_setup');

$head = usernavhistoryAdminPrepareHead();
print dol_get_fiche_head($head, 'compatibility', $title, -1, 'usernavhistory@usernavhistory');

print '<div class="div-table-responsive-no-min">';
print '<table class="noborder centpercent">';
print '<tr class="liste_titre"><th colspan="2">'.$langs->trans('CompatibilityEnvironment').'</th></tr>';
print '<tr class="oddeven"><td class="titlefield">'.$langs->trans('DetectedDolibarrVersion').'</td><td>'.dol_escape_htmltag(DOL_VERSION).'</td></tr>';
print '<tr class="oddeven"><td>'.$langs->trans('DetectedPhpVersion').'</td><td>'.dol_escape_htmltag(PHP_VERSION).'</td></tr>';
print '<tr class="oddeven"><td>'.$langs->trans('MinimumDolibarrVersion').'</td><td>'.dol_escape_htmltag(UserNavHistoryCompatibility::MIN_DOLIBARR_VERSION).'</td></tr>';
print '<tr class="oddeven"><td>'.$langs->trans('MinimumPhpVersion').'</td><td>'.dol_escape_htmltag(UserNavHistoryCompatibility::MIN_PHP_VERSION).'</td></tr>';
print '<tr class="oddeven"><td>'.$langs->trans('RecommendedEnvironment').'</td><td>'.$langs->trans('RecommendedEnvironmentValue').'</td></tr>';
print '</table>';
print '</div>';
print '<br>';

print '<div class="div-table-responsive-no-min">';
print '<table class="noborder centpercent">';
print '<tr class="liste_titre">';
print '<th>'.$langs->trans('CompatibilityFeature').'</th>';
print '<th>'.$langs->trans('Description').'</th>';
print '<th>'.$langs->trans('CoreAvailableFrom').'</th>';
print '<th>'.$langs->trans('ModuleAvailableFrom').'</th>';
print '<th>'.$langs->trans('MinimumPhpVersion').'</th>';
print '<th>'.$langs->trans('CompatibilityStatus').'</th>';
print '</tr>';

foreach (UserNavHistoryCompatibility::getCompatibilityFeatures() as $feature) {
	$available = !empty($feature['available']);
	$statusClass = $available ? 'badge-status4' : 'badge-status8';
	$statusLabel = $available ? $langs->trans('Available') : $langs->trans('Unavailable');
	print '<tr class="oddeven">';
	print '<td>'.$langs->trans($feature['label']).'</td>';
	print '<td>'.$langs->trans($feature['description']).'</td>';
	print '<td>'.dol_escape_htmltag($feature['core_available_from']).'</td>';
	print '<td>'.dol_escape_htmltag($feature['module_available_from']).'</td>';
	print '<td>'.dol_escape_htmltag($feature['min_php']).'</td>';
	print '<td><span class="badge '.$statusClass.'">'.$statusLabel.'</span>';
	if (!$available && !empty($feature['reason'])) {
		print '<br><span class="opacitymedium">'.$langs->trans($feature['reason']).'</span>';
	}
	print '</td>';
	print '</tr>';
}

print '</table>';
print '</div>';

print dol_get_fiche_end();
llxFooter();
$db->close();
