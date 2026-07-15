<?php
/* Copyright (C) 2022 SuperAdmin <maxime@gmail.com>
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
 * \file    usernavhistory/class/actions_usernavhistory.class.php
 * \ingroup usernavhistory
 * \brief   Example hook overload.
 *
 * Put detailed description here.
 */

require_once __DIR__ . '/../backport/v19/core/class/commonhookactions.class.php';

/**
 * Class ActionsUserNavHistory
 */
class ActionsUserNavHistory extends \userNavHistory\RetroCompatCommonHookActions
{
	/**
	 * @var DoliDB Database handler.
	 */
	public $db;

	/**
	 * @var string Error code (or message)
	 */
	public $error = '';

	/**
	 * @var array Errors
	 */
	public $errors = array();


	/**
	 * @var array Hook results. Propagated to $this->results for later reuse
	 */
	public $results = array();

	/**
	 * @var string String displayed by executeHook() immediately after return
	 */
	public $resprints;


	/**
	 * Constructor
	 *
	 *  @param		DoliDB		$db      Database handler
	 */
	public function __construct($db)
	{
		$this->db = $db;
	}


	/**
	 * Execute action
	 *
	 * @param	array			$parameters		Array of parameters
	 * @param	CommonObject    $object         The object to process (an invoice if you are in invoice module, a propale in propale's module, etc...)
	 * @param	string			$action      	'add', 'update', 'view'
	 * @return	int         					<0 if KO,
	 *                           				=0 if OK but we want to process standard actions too,
	 *                            				>0 if OK and we want to replace standard actions.
	 */
	public function getNomUrl($parameters, &$object, &$action)
	{
		global $db, $langs, $conf, $user;

		$this->resprints = '';
		return 0;
	}
	/**
	 * Hook execution to record user navigation history.
	 *
	 * Intercepts the 'globalcard' context to save the current object view
	 * into the user's history log.
	 *
	 * @param array       $parameters  Hook context and arguments.
	 * @param object      $object      The current object being viewed.
	 * @param string      $action      The current action being performed.
	 * @param HookManager $hookmanager The hook manager instance.
	 * @return int Returns 0 on success (or no action), < 0 on error.
	 */
	public function doActions($parameters, &$object, &$action, $hookmanager)
	{
		global $conf, $user, $langs;

		$error = 0; // Error counter
		$aContext = explode(":", $parameters['context']);
		if (in_array('globalcard', $aContext) && !empty($object->element) && !empty($object->id)) {
			dol_include_once('usernavhistory/class/usernavhistory.class.php');
			$unh = new UserNavHistory($this->db);
			$res = $unh->addElementInUserHistory($user->id, $object->id, $object->getElementType());

			if ($res < 0) {
				$this->error = $unh->errors;
			}

			return $res;
		}

		return 0;
	}
	/**
	 * Outputs the user navigation history bar (breadcrumb).
	 *
	 * Retrieves the recently viewed items for the current user and generates a list of links.
	 * It dynamically injects the correct 'mainmenu' parameter into URLs to maintain
	 * the active menu context when navigating history.
	 *
	 * @param array       $parameters  Hook parameters.
	 * @param object      $object      The current business object.
	 * @param string      $action      The current action.
	 * @param HookManager $hookmanager The hook manager instance.
	 * @return int Returns 1 (content injected via $this->resprints).
	 */
	public function printMainArea($parameters, &$object, &$action, $hookmanager)
	{
		global $user, $conf, $langs;

		$print = GETPOST('optioncss', 'alphanohtml');
		if ($print == 'print') {
			return 0;
		}

		$langs->load('usernavhistory@usernavhistory');

		$aFilters = array('fk_user' => $user->id);

		dol_include_once('usernavhistory/class/usernavhistory.class.php');
		$unh = new UserNavHistory($this->db);
		// Note: Utilisation de array() au lieu de [] pour compatibilité max avec vieux Dolibarr si besoin, sinon [] est ok.
		$aUnh = $unh->fetchAll('ASC', 'date_last_view', getDolGlobalString('USERNAVHISTORY_MAX_ELEMENT_NUMBER'), 0, $aFilters);

		$title = $langs->trans('LastNElementViewed', getDolGlobalString('USERNAVHISTORY_MAX_ELEMENT_NUMBER'));

		$divUNH = '<ol class="breadcrumb"><li><span title="' . $title . '" class="fas fa-history"></span></li>';

		if (!empty($aUnh) && is_array($aUnh)) {
			foreach ($aUnh as $item) {
				$params = "";
				$paramToadd = "";

				// Hack for categories because link color is calculated regarding category color
				if ($item->element_type == 'category') {
					$item->object->color = '#FFFFFF';
				}
				if ($item->object < 0) {
					continue;
				}
				if (!method_exists($item->object, 'getNomUrl')) {
					$elem = $item->element_type . ' : ' . $item->element_id;
				} else {
					$elem = $item->object->getNomUrl(1);
				}
				// Extraction de l'URL brute depuis le lien HTML
				$pattern = '/<a\s+href="([^"]+)"/';
				if (preg_match($pattern, $elem, $matches) && count($matches) > 1) {
					// URL trouvée
					$url = $matches[1];

					// Propriété définie à la volée dans usernavhistory.class.php
					if (empty($item->object->mainmodule)) {
						// Nous sommes sur un module custom
						$mainMenuId = UserNavHistory::getMainMenuFromElement($url);
						$paramToadd = !empty($mainMenuId) ? 'mainmenu=' . $mainMenuId : "";
					} else {
						// Nous sommes dans le standard Dolibarr
						$paramToadd = 'mainmenu=' . $item->object->mainmodule;
					}
					// On teste la présence de ? dans l'URI et on ajuste le séparateur
					if (!empty($paramToadd)) {
						$params .= (strpos($url, '?') !== false) ? "&" . $paramToadd : "?" . $paramToadd;
					}
					// Remplacer l'ancienne URL par la nouvelle dans la chaîne
					$elem = preg_replace($pattern, '<a href="' . $url . $params . '"', $elem);
				}
				$divUNH .= '<li>' . $elem . '</li>';
			}
		}
		$divUNH .= '</ol>';
		$divStart = '<div class="usernavhistory">';
		$divEnd = '</div>';
		// Script JS injecté proprement dans une variable
		$jsScript = "
    <script>
       // Cache la barre d'historique de navigation dans les popins
       if (window.name == 'objectpreview') {
          jQuery(document).ready(function () {
             jQuery('.usernavhistory').hide();
          });
       }
    </script>";
		// Pour gérer le hookception et l'affichage direct
		$this->resprints = $divStart . $divUNH . $divEnd . $jsScript;
		return 1;
	}
}
