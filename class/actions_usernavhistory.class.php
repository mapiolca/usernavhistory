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
class ActionsUserNavHistory  extends \userNavHistory\RetroCompatCommonHookActions
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
	 * @var array Hook results. Propagated to $hookmanager->resArray for later reuse
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
	 * Overloading the formObjectOptions function : replacing the parent's function with the one below
	 *
	 * @param   array           $parameters     Hook metadatas (context, etc...)
	 * @param   CommonObject    $object         The object to process (an invoice if you are in invoice module, a propale in propale's module, etc...)
	 * @param   string          $action         Current action (if set). Generally create or edit or null
	 * @param   HookManager     $hookmanager    Hook manager propagated to allow calling another hook
	 * @return  int                             < 0 on error, 0 on success, 1 to replace standard code
	 */
	public function formObjectOptions($parameters, &$object, &$action, $hookmanager)
	{
		global $conf, $user, $langs;

		$error = 0; // Error counter
		$aContext = explode(":", $parameters['context']);

		if(in_array('globalcard', $aContext) && !empty($object->element) && !empty($object->id)) {
			dol_include_once('usernavhistory/class/usernavhistory.class.php');
			$unh = new UserNavHistory($this->db);
			$res = $unh->addElementInUserHistory($user->id, $object->id, $unh->getObjectElementType($object));

			if($res < 0) {
				$this->error = $unh->errors;
			}

			return $res;
		}
	}

	public function printMainArea($parameters, &$object, &$action, $hookmanager) {
		global $user, $conf, $langs;

		$params = $paramToadd =  "";
		$print = GETPOST('optioncss', 'alphanohtml');
		if($print == 'print') return 0;

		$langs->load('usernavhistory@usernavhistory');

		$aFilters = ['fk_user' => $user->id];

		dol_include_once('usernavhistory/class/usernavhistory.class.php');
		$unh = new UserNavHistory($this->db);
		$aUnh = $unh->fetchAll('ASC', 'date_last_view', getDolGlobalString('USERNAVHISTORY_MAX_ELEMENT_NUMBER'), 0, $aFilters);

		$title = $langs->trans('LastNElementViewed',  getDolGlobalString('USERNAVHISTORY_MAX_ELEMENT_NUMBER'));
		$divUNH = '<ol class="breadcrumb"><li><span title="'.$title.'" class="fas fa-history"></span></li>';
		if(!empty($aUnh)) {
			foreach ($aUnh as $i => $item) {
				$params ="";
				if($item->element_type == 'category') $item->object->color = '#FFFFFF'; // Hack for categories because link color is calculated regarding category color
				if($item->object < 0) continue;

				if(!method_exists($item->object, 'getNomUrl'))
					$elem = $item->element_type.' : '.$item->element_id;
				else
					$elem = $item->object->getNomUrl(1);
					$pattern = '/<a\s+href="([^"]+)"/';
					preg_match($pattern, $elem, $matches);
					if (count($matches) > 1) {
						// URL trouvée
						$url = $matches[1];

						// propriété définie à la volée dans usernavhistory.class.php
						if (empty($item->object->mainmodule)){
							// nous somme sur un module custom
							$paramToadd =  !empty(UserNavHistory::getMainMenuFromElement($url)) ?  'mainmenu='.UserNavHistory::getMainMenuFromElement($url) : "" ;
						}else{
							// nous somme dans std dolibarr
							$paramToadd =  'mainmenu='.$item->object->mainmodule ;
						}
						// on test la presence de ? dans l'uri  et on ajuste en conséquence le séparateur de paramètre uri
						if (!empty($paramToadd)) $params .= strpos($url, '?')  ? "&".$paramToadd : "?".$paramToadd;


						// Remplacer l'ancienne URL par la nouvelle dans la chaîne
						$elem= preg_replace($pattern, '<a href="' . $url.$params . '"', $elem);
					}

				$divUNH.= '<li>'.$elem.'</li>';
			}
		}
		$divUNH.= '</ol>';

		$divStart = '<div class="usernavhistory">';
		$divEnd = '</div>';
		$hookmanager->resPrint = null; // Pour gerer le hookception
		$this->resprints = $divStart . $divUNH . $divEnd;


		?>
		<script>
			// cache la barre d'historique de navigation dans les popins
			if (window.name == 'objectpreview') {
				$(document).ready(function () {
					$('.usernavhistory').hide();
				});
			}

		</script>
		<?php

		return 1;

	}

	public function printTopRightMenu($parameters, &$object, &$action, $hookmanager) {
		//$usernavhist = '<div class="inline-block">';
		//$usernavhist.= '<div class="classfortooltip inline-block login_block_elem inline-block" style="padding: 0px; padding: 0px; padding-right: 3px !important;"><a href="/index.php?mainmenu=home&amp;leftmenu=home&amp;optioncss=print" target="_blank" rel="noopener noreferrer"><span class="fa fa-print atoplogin valignmiddle"></span></a></div>';
		//$usernavhist.= '<span class="fa fa-print atoplogin valignmiddle"></span>';
		//$usernavhist.= '</div>';
		//$this->resprints = $usernavhist;
		return 0;
	}
}
