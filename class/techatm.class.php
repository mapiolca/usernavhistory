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


namespace userNavHistory;

/**
 * Class TechATM
 * Handle some ATM technical features
 */
class TechATM
{

	/**
	 * @var DoliDb		Database handler (result of a new DoliDB)
	 */
	public $db;

	/**
	 * @var string 		Error string
	 * @see             $errors
	 */
	public $error;

	/**
	 * @var string[]	Array of error strings
	 */
	public $errors = array();

	/**
	 * @var reponse_code  a http_response_header parsed reponse code
	 */
	public $reponse_code;

	/**
	 * @var http_response_header  the last call $http_response_header
	 */

	public $http_response_header;

	/**
	 * @var TResponseHeader  the last call $http_response_header parsed <- for most common usage (see self::parseHeaders() function)
	 */
	public $TResponseHeader;

	/**
	 * url vers le domaine des appels techniques
	 */
	const ATM_TECH_URL = 'https://tech.atm-consulting.fr';


	/**
	 * il s'agit de la version de ce cette class
	 * Si jamais on change la façon de faire
	 * Au moins on peut gérer des redescentes d'info différentes ex json au lieu de html simple
	 */
	const CALL_VERSION = 1.0;

	/**
	 *  Constructor
	 *
	 * @param DoliDB $db Database handler
	 * @return void
	 */
	public function __construct($db)
	{
		$this->db = $db;
	}

	/**
	 * Retrieve the About page
	 *
	 * @param  DolibarrModules $moduleDescriptor  descriptor module
	 * @param  bool            $useCache          Use cache
	 * @return false|string                       display HTML or false
	 */
	public function getAboutPage($moduleDescriptor, $useCache = true)
	{
		global $langs;

		$url = self::ATM_TECH_URL.'/modules/modules-page-about.php';
		$url.= '?module='.$moduleDescriptor->name;
		$url.= '&id='.$moduleDescriptor->numero;
		$url.= '&version='.$moduleDescriptor->version;
		$url.= '&langs='.$langs->defaultlang;
		$url.= '&callv='.self::CALL_VERSION;

		$cachePath = DOL_DATA_ROOT . "/modules-atm/temp/about_page";
		$cacheFileName = dol_sanitizeFileName($moduleDescriptor->name.'_'.$langs->defaultlang).'.html';
		$cacheFilePath = $cachePath.'/'.$cacheFileName;

		if ($useCache && is_readable($cacheFilePath)) {
			$lastChange = filemtime($cacheFilePath);
			if ($lastChange > time() - 86400) {
				$content = @file_get_contents($cacheFilePath);
				if ($content !== false) {
					return $content;
				}
			}
		}

		$content = $this->getContents($url);

		if (!$content) {
			$content = '';
			// About page goes here
			$content.= '<div style="float: left;"><img src="../img/Dolibarr_Preferred_Partner_logo.png" /></div>';
			$content.= '<div>'.$langs->trans('ATMAbout').'</div>';
			$content.= '<hr/><center>';
			$content.= '<a href="http://www.atm-consulting.fr" target="_blank"><img src="../img/ATM_logo.jpg" /></a>';
			$content.= '</center>';
		}

		if ($useCache) {
			if (!is_dir($cachePath)) {
				$res = dol_mkdir($cachePath, DOL_DATA_ROOT);
			} else {
				$res = true;
			}

			if ($res) {
				$comment = '<!-- Generated from '.$url.' -->'."\r\n";

				file_put_contents(
					$cacheFilePath,
					$comment.$content
				);
			}
		}

		return $content;
	}

	/**
	 * @param DolibarrModules $moduleDescriptor Module descriptor instance
	 * @return string
	 */
	public static function getLastModuleVersionUrl($moduleDescriptor)
	{
		$url = self::ATM_TECH_URL.'/modules/modules-last-version.php';
		$url.= '?module='.$moduleDescriptor->name;
		$url.= '&number='.$moduleDescriptor->numero;
		$url.= '&version='.$moduleDescriptor->version;
		$url.= '&dolversion='.DOL_VERSION;
		$url.= '&callv='.self::CALL_VERSION;
		return $url;
	}

	/**
	 * Fetches URL content using Dolibarr native function.
	 *
	 * @param string $url The URL to fetch.
	 * @return array|false The fetched content, or false on failure.
	 */
	public function getContents($url)
	{
		global $conf;
		require_once DOL_DOCUMENT_ROOT . '/core/lib/files.lib.php';

		$content = getURLContent($url, 'GET', '', 1, 5);
		if ($content !== false) {
			$this->data = $content;
			return $this->data;
		}

		return false;
	}
	/**
	 * Parses raw HTTP header lines into an associative array.
	 *
	 * @param array $headers List of raw header strings.
	 * @return array Key-value headers, including the 'reponse_code'.
	 */
	public static function parseHeaders($headers)
	{
		$head = array();
		if (!is_array($headers)) {
			return $head;
		}

		foreach ($headers as $k=>$v) {
			$t = explode(':', $v, 2);
			if ( isset($t[1]) )
				$head[ trim($t[0]) ] = trim($t[1]);
			else {
				$head[] = $v;
				if ( preg_match("#HTTP/[0-9\.]+\s+([0-9]+)#", $v, $out) )
					$head['reponse_code'] = intval($out[1]);
			}
		}
		return $head;
	}
}
