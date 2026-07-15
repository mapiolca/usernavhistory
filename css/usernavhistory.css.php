<?php

/* Copyright (C) 2025 ATM Consulting support@atm-consulting.fr
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see https://www.gnu.org/licenses/.
 */

/**
 * \file    /css/usernavhistory.css.php
 * \ingroup mymodule
 * \brief   CSS file for module MyModule.
 */


if (!defined('NOREQUIRESOC')) {
	define('NOREQUIRESOC', '1');
}
if (!defined('NOTOKENRENEWAL')) {
	define('NOTOKENRENEWAL', 1);
}
if (!defined('NOLOGIN')) {
	define('NOLOGIN', 1); // File must be accessed by logon page so without login
}
if (!defined('NOREQUIREHTML')) {
	define('NOREQUIREHTML', 1);
}
if (!defined('NOREQUIREAJAX')) {
	define('NOREQUIREAJAX', '1');
}

session_cache_limiter('public');

// Load Dolibarr environment
$res = 0;
// Try main.inc.php into web root known defined into CONTEXT_DOCUMENT_ROOT (not always defined)
if (!$res && !empty($_SERVER["CONTEXT_DOCUMENT_ROOT"])) {
	$res = @include $_SERVER["CONTEXT_DOCUMENT_ROOT"]."/main.inc.php";
}
// Try main.inc.php into web root detected using web root calculated from SCRIPT_FILENAME
$tmp = empty($_SERVER['SCRIPT_FILENAME']) ? '' : $_SERVER['SCRIPT_FILENAME']; $tmp2 = realpath(__FILE__); $i = strlen($tmp) - 1; $j = strlen($tmp2) - 1;
while ($i > 0 && $j > 0 && isset($tmp[$i]) && isset($tmp2[$j]) && $tmp[$i] == $tmp2[$j]) {
	$i--;
	$j--;
}
if (!$res && $i > 0 && file_exists(substr($tmp, 0, ($i + 1))."/main.inc.php")) {
	$res = @include substr($tmp, 0, ($i + 1))."/main.inc.php";
}
if (!$res && $i > 0 && file_exists(substr($tmp, 0, ($i + 1))."/../main.inc.php")) {
	$res = @include substr($tmp, 0, ($i + 1))."/../main.inc.php";
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

require_once DOL_DOCUMENT_ROOT.'/core/lib/functions2.lib.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/admin.lib.php';

// Define css type
header('Content-type: text/css');
// Important: Following code is to cache this file to avoid page request by browser at each Dolibarr page access.
// You can use CTRL+F5 to refresh your browser cache.
if (empty($dolibarr_nocache)) {
	header('Cache-Control: max-age=10800, public, must-revalidate');
} else {
	header('Cache-Control: no-cache');
}


$marginleft = 40;
$marginright = 36;
if(versioncompare(explode('.', DOL_VERSION), array(20)) >= 0) {
	$marginleft = 42;
	$marginright = 38;
}
if(versioncompare(explode('.', DOL_VERSION), array(21)) >= 0) {
	$marginleft = 44;
	$marginright = 38;
}

?>

.usernavhistory {
	margin-left: -<?php print $marginleft ?>px;
	margin-right: -<?php print $marginright ?>px;
	margin-top: -14px;

	display: grid;
	align-items: center;
}

.breadcrumb {
	padding: 8px 15px;
	margin-bottom: 0px;
	margin-top: 0px;
	list-style: none;
	background-color: var(--colorbackvmenu1);
}

.breadcrumb > li {
	display: inline-block;
	opacity: 0.7;
}

.breadcrumb > li + li:hover {
	opacity: 1;
}

.breadcrumb > li + li:before {
	content: "\00a0/\00a0";
	padding: 0 5px;
	color: #ccc;
}


.usernavhistory .breadcrumb img {
	max-height: 16px;
}

.breadcrumb > li a img {
	vertical-align: middle;
}
