<?php
/**
 * Exercise history hydration with a native CommonObject and simulated database/resolution.
 * Run with: php test/object_resolution_test.php /path/to/dolibarr/htdocs
 *
 * Copyright (C) 2026 Pierre Ardoin <developpeur@lesmetiersdubatiment.fr>
 * SPDX-License-Identifier: GPL-3.0-or-later
 */

if (PHP_SAPI !== 'cli') {
	exit(1);
}
$coreRoot = isset($argv[1]) ? realpath($argv[1]) : false;
if ($coreRoot === false || !is_file($coreRoot.'/core/class/commonobject.class.php')) {
	fwrite(STDERR, "Pass the path to a Dolibarr htdocs directory.\n");
	exit(1);
}
define('DOL_DOCUMENT_ROOT', $coreRoot);
define('MAIN_DB_PREFIX', 'test_');
error_reporting(E_ALL);
set_error_handler(function ($severity, $message, $file, $line) {
	throw new ErrorException($message, 0, $severity, $file, $line);
});

function dol_syslog($message, $level = 0)
{
}
function isModEnabled($module)
{
	return $module === 'multicompany';
}
function getDolGlobalString($key, $default = '')
{
	return $default;
}

// A database stand-in, not a real connection or a SQL isolation test.
class DoliDB
{
	public $rows = array();
	public function sanitize($value)
	{
		return $value;
	}
	public function query($sql)
	{
		return true;
	}
	public function num_rows($result)
	{
		return count($this->rows);
	}
	public function fetch_object($result)
	{
		return array_shift($this->rows);
	}
	public function free($result)
	{
	}
}
$conf = (object) array('entity' => 2);
$langs = null;
require_once __DIR__.'/../class/usernavhistory.class.php';

class ResolutionTestHistory extends UserNavHistory
{
	public function getObjectByElement($elementtype, $elementid = 0)
	{
		$this->mainmodule = 'billing';
		return $elementid === 42 ? new UserNavHistory($this->db) : null;
	}
}
$db = new DoliDB();
$history = new ResolutionTestHistory($db);
$row = (object) array('rowid' => 1, 'entity' => 2, 'fk_user' => 7, 'element_id' => 42, 'element_type' => 'invoice');
$missing = clone $row;
$missing->rowid = 2;
$missing->element_id = 43;
$db->rows = array($row, $missing);
$records = $history->fetchAll();
if (!is_array($records) || count($records) !== 1 || !isset($records[1])
	|| !($records[1]->object instanceof CommonObject) || $records[1]->mainmodule !== 'billing') {
	throw new RuntimeException('Resolved history entries changed or missing objects were retained.');
}
if ($history->object !== null || $history->fetchAll() !== array()) {
	throw new RuntimeException('Empty history inherited a previously resolved object.');
}
restore_error_handler();
print 'OK: resolved, unavailable and empty history entries with native hydration on PHP '.PHP_VERSION.".\n";
