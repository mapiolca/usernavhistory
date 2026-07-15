<?php
/* Copyright (C) 2017  Laurent Destailleur <eldy@users.sourceforge.net>
 * Copyright (C) ---Put here your own copyright and developer email---
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
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */

/**
 * \file        class/usernavhistory.class.php
 * \ingroup     usernavhistory
 * \brief       This file is a CRUD class file for UserNavHistory (Create/Read/Update/Delete)
 */

// Put here all includes required by your class file
require_once DOL_DOCUMENT_ROOT.'/core/class/commonobject.class.php';

/**
 * Class for UserNavHistory
 */
class UserNavHistory extends CommonObject
{
	/**
	 * @var string ID of module.
	 */
	public $module = 'usernavhistory';

	/**
	 * @var string ID to identify managed object.
	 */
	public $element = 'usernavhistory';

	/**
	 * @var string Name of table without prefix where object is stored. This is also the key used for extrafields management.
	 */
	public $table_element = 'user_navhistory';

	/**
	 * @var int  Does this object support multicompany module ?
	 * 0=No test on entity, 1=Test with field entity, 'field@table'=Test with link by field@table
	 */
	public $ismultientitymanaged = 1;

	/**
	 * @var int  Does object support extrafields ? 0=No, 1=Yes
	 */
	public $isextrafieldmanaged = 0;

	/**
	 * @var string String with name of icon for usernavhistory. Must be the part after the 'object_' into object_usernavhistory.png
	 */
	public $picto = 'usernavhistory@usernavhistory';

	/**
	 *  'type' field format ('integer', 'integer:ObjectClass:PathToClass[:AddCreateButtonOrNot[:Filter[:Sortfield]]]', 'sellist:TableName:LabelFieldName[:KeyFieldName[:KeyFieldParent[:Filter[:Sortfield]]]]', 'varchar(x)', 'double(24,8)', 'real', 'price', 'text', 'text:none', 'html', 'date', 'datetime', 'timestamp', 'duration', 'mail', 'phone', 'url', 'password')
	 *         Note: Filter can be a string like "(t.ref:like:'SO-%') or (t.date_creation:<:'20160101') or (t.nature:is:NULL)"
	 *  'label' the translation key.
	 *  'picto' is code of a picto to show before value in forms
	 *  'enabled' is a condition when the field must be managed (Example: 1 or '$conf->global->MY_SETUP_PARAM)
	 *  'position' is the sort order of field.
	 *  'notnull' is set to 1 if not null in database. Set to -1 if we must set data to null if empty ('' or 0).
	 *  'visible' says if field is visible in list (Examples: 0=Not visible, 1=Visible on list and create/update/view forms, 2=Visible on list only, 3=Visible on create/update/view form only (not list), 4=Visible on list and update/view form only (not create). 5=Visible on list and view only (not create/not update). Using a negative value means field is not shown by default on list but can be selected for viewing)
	 *  'noteditable' says if field is not editable (1 or 0)
	 *  'default' is a default value for creation (can still be overwrote by the Setup of Default Values if field is editable in creation form). Note: If default is set to '(PROV)' and field is 'ref', the default value will be set to '(PROVid)' where id is rowid when a new record is created.
	 *  'index' if we want an index in database.
	 *  'foreignkey'=>'tablename.field' if the field is a foreign key (it is recommanded to name the field fk_...).
	 *  'searchall' is 1 if we want to search in this field when making a search from the quick search button.
	 *  'isameasure' must be set to 1 or 2 if field can be used for measure. Field type must be summable like integer or double(24,8). Use 1 in most cases, or 2 if you don't want to see the column total into list (for example for percentage)
	 *  'css' and 'cssview' and 'csslist' is the CSS style to use on field. 'css' is used in creation and update. 'cssview' is used in view mode. 'csslist' is used for columns in lists. For example: 'css'=>'minwidth300 maxwidth500 widthcentpercentminusx', 'cssview'=>'wordbreak', 'csslist'=>'tdoverflowmax200'
	 *  'help' is a 'TranslationString' to use to show a tooltip on field. You can also use 'TranslationString:keyfortooltiponlick' for a tooltip on click.
	 *  'showoncombobox' if value of the field must be visible into the label of the combobox that list record
	 *  'disabled' is 1 if we want to have the field locked by a 'disabled' attribute. In most cases, this is never set into the definition of $fields into class, but is set dynamically by some part of code.
	 *  'arrayofkeyval' to set a list of values if type is a list of predefined values. For example: array("0"=>"Draft","1"=>"Active","-1"=>"Cancel"). Note that type can be 'integer' or 'varchar'
	 *  'autofocusoncreate' to have field having the focus on a create form. Only 1 field should have this property set to 1.
	 *  'comment' is not used. You can store here any text of your choice. It is not used by application.
	 *	'validate' is 1 if need to validate with $this->validateField()
	 *  'copytoclipboard' is 1 or 2 to allow to add a picto to copy value into clipboard (1=picto after label, 2=picto after value)
	 *
	 *  Note: To have value dynamic, you can set value to 0 in definition and edit the value on the fly into the constructor.
	 */

	// BEGIN MODULEBUILDER PROPERTIES
	/**
	 * @var array  Array with all fields and their property. Do not use it as a static var. It may be modified by constructor.
	 */
	public $fields=array(
		'rowid' => array('type'=>'integer', 'label'=>'TechnicalID', 'enabled'=>'1', 'position'=>1, 'notnull'=>1, 'visible'=>0, 'noteditable'=>'1', 'index'=>1, 'css'=>'left', 'comment'=>"Id"),
		'entity' =>array('type'=>'integer', 'label'=>'Entity', 'default'=>1, 'enabled'=>1, 'visible'=>-2, 'notnull'=>1, 'position'=>10, 'index'=>1),
		'tms' => array('type'=>'timestamp', 'label'=>'DateModification', 'enabled'=>'1', 'position'=>501, 'notnull'=>0, 'visible'=>-2,),
		'fk_user' => array('type'=>'integer:User:user/class/user.class.php', 'label'=>'UserAuthor', 'enabled'=>'1', 'position'=>25, 'notnull'=>1, 'visible'=>-2, 'foreignkey'=>'user.rowid',),
		'element_id' => array('type'=>'integer', 'label'=>'Elementid', 'enabled'=>'1', 'position'=>30, 'notnull'=>0, 'visible'=>-1,),
		'element_type' => array('type'=>'varchar(32)', 'label'=>'Elementtype', 'enabled'=>'1', 'position'=>35, 'notnull'=>0, 'visible'=>-1,),
		'date_last_view' => array('type'=>'timestamp', 'label'=>'DateLastView', 'enabled'=>'1', 'position'=>40, 'notnull'=>1, 'visible'=>1,),
	);
	public $rowid;
	public $entity;
	public $tms;
	public $fk_user;
	public $element_id;
	public $element_type;
	public $date_last_view;
	// END MODULEBUILDER PROPERTIES



	/**
	 * Constructor
	 *
	 * @param DoliDb $db Database handler
	 */
	public function __construct(DoliDB $db)
	{
		global $conf, $langs;

		$this->db = $db;

		if (empty(getDolGlobalString('MAIN_SHOW_TECHNICAL_ID')) && isset($this->fields['rowid'])) {
			$this->fields['rowid']['visible'] = 0;
		}
		if (!isModEnabled('multicompany') && isset($this->fields['entity'])) {
			$this->fields['entity']['enabled'] = 0;
		}

		// Example to show how to set values of fields definition dynamically
		/*if ($user->rights->usernavhistory->usernavhistory->read) {
			$this->fields['myfield']['visible'] = 1;
			$this->fields['myfield']['noteditable'] = 0;
		}*/

		// Unset fields that are disabled
		foreach ($this->fields as $key => $val) {
			if (isset($val['enabled']) && empty($val['enabled'])) {
				unset($this->fields[$key]);
			}
		}

		// Translate some data of arrayofkeyval
		if (is_object($langs)) {
			foreach ($this->fields as $key => $val) {
				if (!empty($val['arrayofkeyval']) && is_array($val['arrayofkeyval'])) {
					foreach ($val['arrayofkeyval'] as $key2 => $val2) {
						$this->fields[$key]['arrayofkeyval'][$key2] = $langs->trans($val2);
					}
				}
			}
		}
	}

	/**
	 * Create object into database
	 *
	 * @param  User $user      User that creates
	 * @param  bool $notrigger false=launch triggers after, true=disable triggers
	 * @return int             <0 if KO, Id of created object if OK
	 */
	public function create(User $user, $notrigger = false)
	{
		return $this->createCommon($user, $notrigger);
	}

	/**
	 * Load object in memory from the database
	 *
	 * @param $id ID Object
	 * @param $userid ID User
	 * @param $elementid ID Element
	 * @param $elementtype Element type
	 * @return int <0 if KO, 0 if not found, >0 if OK
	 */
	public function fetch($id, $userid = 0, $elementid = 0, $elementtype = '')
	{
		if(!empty($id)) {
			$result = $this->fetchCommon($id);
		} else if(!empty($userid) && !empty($elementid) && !empty($elementtype)) {
			$moreWhere = " AND fk_user = ".$userid;
			$moreWhere.= " AND element_id = ".$elementid;
			$moreWhere.= " AND element_type = '".$elementtype."'";

			$result = $this->fetchCommon(0, null, $moreWhere);
		} else {
			return -1;
		}

		return $result;
	}



	/**
	 * Copié depuis add_object_linked du common object en V16 de Dolibarr
	 * permet de générer le element_type
	 *
	 * @param CommonObject    $object
	 * @return string formatted as elementType
	 */
	static public function getObjectElementType($object)
	{
		// Elements of the core modules which have `$module` property but may to which we don't want to prefix module part to the element name for finding the linked object in llx_element_element.
		// It's because an entry for this element may be exist in llx_element_element before this modification (version <=14.2) and ave named only with their element name in fk_source or fk_target.
		$coreModules = array('knowledgemanagement', 'partnership', 'workstation', 'ticket', 'recruitment', 'eventorganization');
		// Add module part to target type if object has $module property and isn't in core modules.

		if(!empty($object->module) && !in_array($object->module, $coreModules)){
			$modulePrefix = $object->module . '_';
			if(strpos($object->element, $modulePrefix) === false){
				return $modulePrefix.$object->element;
			}
		}

		return $object->element;
	}



	/**
	 * Load list of objects in memory from the database.
	 *
	 * @param  string      $sortorder    Sort Order
	 * @param  string      $sortfield    Sort field
	 * @param  int         $limit        limit
	 * @param  int         $offset       Offset
	 * @param  array       $filter       Filter array. Example array('field'=>'valueforlike', 'customurl'=>...)
	 * @param  string      $filtermode   Filter mode (AND or OR)
	 * @return array|int                 int <0 if KO, array of pages if OK
	 */
	public function fetchAll($sortorder = '', $sortfield = '', $limit = 0, $offset = 0, array $filter = array(), $filtermode = 'AND')
	{
		global $conf;

		dol_syslog(__METHOD__, LOG_DEBUG);

		$records = array();

		$sql = "SELECT ";
		$sql .= $this->getFieldList('t');
		$sql .= " FROM ".MAIN_DB_PREFIX.$this->table_element." as t";
		if (isset($this->ismultientitymanaged) && $this->ismultientitymanaged == 1) {
			$sql .= " WHERE t.entity IN (".getEntity($this->table_element).")";
		} else {
			$sql .= " WHERE 1 = 1";
		}
		// Manage filter
		$sqlwhere = array();
		if (count($filter) > 0) {
			foreach ($filter as $key => $value) {
				if ($key == 't.rowid') {
					$sqlwhere[] = $key." = ".((int) $value);
				} elseif (in_array($this->fields[$key]['type'], array('date', 'datetime', 'timestamp'))) {
					$sqlwhere[] = $key." = '".$this->db->idate($value)."'";
				} elseif ($key == 'customsql') {
					$sqlwhere[] = $value;
				} elseif (strpos($value, '%') === false) {
					$sqlwhere[] = $key." IN (".$this->db->sanitize($this->db->escape($value)).")";
				} else {
					$sqlwhere[] = $key." LIKE '%".$this->db->escape($value)."%'";
				}
			}
		}
		if (count($sqlwhere) > 0) {
			$sql .= " AND (".implode(" ".$filtermode." ", $sqlwhere).")";
		}

		if (!empty($sortfield)) {
			$sql .= $this->db->order($sortfield, $sortorder);
		}
		if (!empty($limit)) {
			$sql .= $this->db->plimit($limit, $offset);
		}

		$resql = $this->db->query($sql);
		if ($resql) {
			$num = $this->db->num_rows($resql);
			$i = 0;
			while ($i < ($limit ? min($limit, $num) : $num)) {
				$obj = $this->db->fetch_object($resql);

				$record = new self($this->db);
				$record->setVarsFromFetchObj($obj);
				$record->object = $this->getObjectByElement($record->element_type, $record->element_id);

				if($record->object){
					$records[$record->id] = $record;
				}

				$i++;
			}
			$this->db->free($resql);

			return $records;
		} else {
			$this->errors[] = 'Error '.$this->db->lasterror();
			dol_syslog(__METHOD__.' '.join(',', $this->errors), LOG_ERR);

			return -1;
		}
	}

	/**
	 * Update object into database
	 *
	 * @param  User $user      User that modifies
	 * @param  bool $notrigger false=launch triggers after, true=disable triggers
	 * @return int             <0 if KO, >0 if OK
	 */
	public function update(User $user, $notrigger = false)
	{
		return $this->updateCommon($user, $notrigger);
	}

	/**
	 * Delete object in database
	 *
	 * @param User $user       User that deletes
	 * @param bool $notrigger  false=launch triggers after, true=disable triggers
	 * @return int             <0 if KO, >0 if OK
	 */
	public function delete(User $user, $notrigger = false)
	{
		return $this->deleteCommon($user, $notrigger);
	}

	/**
	 *  Return a link to the object card (with optionaly the picto)
	 *
	 *  @param  int     $withpicto                  Include picto in link (0=No picto, 1=Include picto into link, 2=Only picto)
	 *  @param  string  $option                     On what the link point to ('nolink', ...)
	 *  @param  int     $notooltip                  1=Disable tooltip
	 *  @param  string  $morecss                    Add more css on link
	 *  @param  int     $save_lastsearch_value      -1=Auto, 0=No save of lastsearch_values when clicking, 1=Save lastsearch_values whenclicking
	 *  @return	string                              String with URL
	 */
	public function getNomUrl($withpicto = 0, $option = '', $notooltip = 0, $morecss = '', $save_lastsearch_value = -1)
	{
		global $conf, $langs, $hookmanager;

		if (!empty($conf->dol_no_mouse_hover)) {
			$notooltip = 1; // Force disable tooltips
		}

		$result = '';

		$label = img_picto('', $this->picto).' <u>'.$langs->trans("UserNavHistory").'</u>';
		if (isset($this->status)) {
			$label .= ' '.$this->getLibStatut(5);
		}
		$label .= '<br>';
		$label .= '<b>'.$langs->trans('Ref').':</b> '.$this->ref;

		$url = dol_buildpath('/usernavhistory/usernavhistory_card.php', 1).'?id='.$this->id;

		if ($option != 'nolink') {
			// Add param to save lastsearch_values or not
			$add_save_lastsearch_values = ($save_lastsearch_value == 1 ? 1 : 0);
			if ($save_lastsearch_value == -1 && preg_match('/list\.php/', $_SERVER["PHP_SELF"])) {
				$add_save_lastsearch_values = 1;
			}
			if ($url && $add_save_lastsearch_values) {
				$url .= '&save_lastsearch_values=1';
			}
		}

		$linkclose = '';
		if (empty($notooltip)) {
			if (!empty(getDolGlobalString('MAIN_OPTIMIZEFORTEXTBROWSER'))) {
				$label = $langs->trans("ShowUserNavHistory");
				$linkclose .= ' alt="'.dol_escape_htmltag($label, 1).'"';
			}
			$linkclose .= ' title="'.dol_escape_htmltag($label, 1).'"';
			$linkclose .= ' class="classfortooltip'.($morecss ? ' '.$morecss : '').'"';
		} else {
			$linkclose = ($morecss ? ' class="'.$morecss.'"' : '');
		}

		if ($option == 'nolink' || empty($url)) {
			$linkstart = '<span';
		} else {
			$linkstart = '<a href="'.$url.'"';
		}
		$linkstart .= $linkclose.'>';
		if ($option == 'nolink' || empty($url)) {
			$linkend = '</span>';
		} else {
			$linkend = '</a>';
		}

		$result .= $linkstart;

		if (empty($this->showphoto_on_popup)) {
			if ($withpicto) {
				$result .= img_object(($notooltip ? '' : $label), ($this->picto ? $this->picto : 'generic'), ($notooltip ? (($withpicto != 2) ? 'class="paddingright"' : '') : 'class="'.(($withpicto != 2) ? 'paddingright ' : '').'classfortooltip"'), 0, 0, $notooltip ? 0 : 1);
			}
		} else {
			if ($withpicto) {
				require_once DOL_DOCUMENT_ROOT.'/core/lib/files.lib.php';

				list($class, $module) = explode('@', $this->picto);
				$upload_dir = $conf->$module->multidir_output[$conf->entity]."/$class/".dol_sanitizeFileName($this->ref);
				$filearray = dol_dir_list($upload_dir, "files");
				$filename = $filearray[0]['name'];
				if (!empty($filename)) {
					$pospoint = strpos($filearray[0]['name'], '.');

					$pathtophoto = $class.'/'.$this->ref.'/thumbs/'.substr($filename, 0, $pospoint).'_mini'.substr($filename, $pospoint);
					if (empty(getDolGlobalString(strtoupper($module.'_'.$class).'_FORMATLISTPHOTOSASUSERS'))) {
						$result .= '<div class="floatleft inline-block valignmiddle divphotoref"><div class="photoref"><img class="photo'.$module.'" alt="No photo" border="0" src="'.DOL_URL_ROOT.'/viewimage.php?modulepart='.$module.'&entity='.$conf->entity.'&file='.urlencode($pathtophoto).'"></div></div>';
					} else {
						$result .= '<div class="floatleft inline-block valignmiddle divphotoref"><img class="photouserphoto userphoto" alt="No photo" border="0" src="'.DOL_URL_ROOT.'/viewimage.php?modulepart='.$module.'&entity='.$conf->entity.'&file='.urlencode($pathtophoto).'"></div>';
					}

					$result .= '</div>';
				} else {
					$result .= img_object(($notooltip ? '' : $label), ($this->picto ? $this->picto : 'generic'), ($notooltip ? (($withpicto != 2) ? 'class="paddingright"' : '') : 'class="'.(($withpicto != 2) ? 'paddingright ' : '').'classfortooltip"'), 0, 0, $notooltip ? 0 : 1);
				}
			}
		}

		if ($withpicto != 2) {
			$result .= $this->ref;
		}

		$result .= $linkend;
		//if ($withpicto != 2) $result.=(($addlabel && $this->label) ? $sep . dol_trunc($this->label, ($addlabel > 1 ? $addlabel : 0)) : '');

		global $action, $hookmanager;
		$hookmanager->initHooks(array('usernavhistorydao'));
		$parameters = array('id'=>$this->id, 'getnomurl'=>$result);
		$reshook = $hookmanager->executeHooks('getNomUrl', $parameters, $this, $action); // Note that $action and $object may have been modified by some hooks
		if ($reshook > 0) {
			$result = $hookmanager->resPrint;
		} else {
			$result .= $hookmanager->resPrint;
		}

		return $result;
	}

	/**
	 * Add an element in the user navigation history
	 * Reorder the user history
	 * Clean the user history
	 *
	 * @param int $userid ID of concerned user
	 * @param int $elementid ID of element concerned
	 * @param string $elementtype Type of element concerned
	 * @param int $nbToKeep Number max of element to keep in history for the user
	 * @return int 0 on success, < 0 on error
	 */
	public function addElementInUserHistory(int $userid, int $elementid, string $elementtype)
	{
		global $user, $conf;

		$error = 0;
		$this->db->begin();

		// We try to load the element in the user history to check if it's already existing
		$res = $this->fetch(0, $userid, $elementid, $elementtype);

		if($res < 0) {
			$error++;
		}

		if(!$error) {
			if ($res > 0) { // Element is already in history, we just update the last view date
				$this->date_last_view = dol_now();
				$res = $this->update($user);
			} else { // Element is not in user navigation history so we add it
				$this->fk_user = $userid;
				$this->element_id = $elementid;
				$this->element_type = $elementtype;
				$this->date_last_view = dol_now();

				$res = $this->create($user);
			}
		}

		if($res < 0) {
			$error++;
		}

		if(!$error) {
			$res = $this->cleanUserHistory($user->id,  getDolGlobalString('USERNAVHISTORY_MAX_ELEMENT_NUMBER'));
			if($res < 0) {
				$error++;
			}
		}

		if(!$error) {
			$this->db->commit();
			return 0;
		} else {
			$this->db->rollback();
			return -1;
		}
	}

	/**
	 * Remove user navigation history to only keep $limit elements
	 *
	 * @param int $userid ID of the concerned user
	 * @param int $limit Number of navigation history to keep
	 * @return int 1 on success, < 0 on error
	 */
	public function cleanUserHistory(int $userid, int $nbToKeep)
	{
		$sqlLastN = 'SELECT rowid FROM (SELECT rowid FROM '.MAIN_DB_PREFIX.$this->table_element;
		$sqlLastN.= ' WHERE fk_user = '.$userid;
		$sqlLastN.= ' ORDER BY date_last_view DESC LIMIT '.$nbToKeep.') foo';

		$sql = 'DELETE FROM '.MAIN_DB_PREFIX.$this->table_element;
		$sql.= ' WHERE fk_user = '.$userid;
		$sql.= ' AND rowid NOT IN ('.$sqlLastN.')';

		$resql = $this->db->query($sql);
		if(!$resql) {
			$this->errors[] = $this->db->lasterror();
			return -1;
		}

		return 1;
	}


	/**
	 * Create a new object instance based on the element type
	 * Fetch the object if id is provided
	 *
	 * @param string $objecttype Type of object ('invoice', 'order', 'expedition_bon', 'myobject@mymodule', ...)
	 * @param int $elementid Id of element to provide if fetch is needed
	 * @return CommonObject object of $elementtype, fetched by $elementid
	 */
	function getObjectByElement($elementtype, $elementid = 0)
	{
		global $conf, $langs, $db, $action, $hookmanager;

        /**
         * TODO factoriser cette méthode lorsque cette PR sera passée : https://github.com/Dolibarr/dolibarr/pull/21674
         * pour prendre en compte la rétrocompatibilité
         */

		$ret = -1;
		$regs = array();

		// Parse $objecttype (ex: project_task)
		$module = $myobject =  $classfile = "";

		// If we ask an resource form external module (instead of default path)
		if (preg_match('/^([^@]+)@([^@]+)$/i', $elementtype, $regs)) {
			$myobject = $regs[1];
			$module = $regs[2];
		}


		if (preg_match('/^([^_]+)_([^_]+)/i', $elementtype, $regs))
		{
			$module = $regs[1];
			$myobject = $regs[2];
		}

		// Generic case for $classpath
		$classpath = $module.'/class';

		list($classpath, $module, $classfile, $classname, $mainmodule) =  $this->setInternalValues($elementtype, $classpath, $module, $myobject );


		$hookmanager->initHooks(array('usernavhistorydao'));
		$parameters = array('elementtype' => &$elementtype, 'elementid'=> &$elementid, 'classfile' => &$classfile, 'classname' => &$classname, 'classpath' => &$classpath, 'module' => &$module);
		$hookmanager->executeHooks('getObjectByElement', $parameters, $this, $action); // Note that $action and $object may have been modified by some hooks

		if (isModEnabled($module))
		{
			$res = dol_include_once('/'.$classpath.'/'.$classfile.'.class.php');
			if ($res)
			{
				if (class_exists($classname)) {
					$obj = new $classname($db);
					$obj->mainmodule = $mainmodule;
					if(!empty($elementid)) {
						if($obj->fetch($elementid) < 1){
							return 0;
						}
					}
					return $obj;
				}
			}
		}
		return $ret;
	}

	/**
	 * @param string $elementtype
	 * @param string $classpath
	 * @param string $module
	 * @param string $myobject
	 * @return array
	 */
	public function setInternalValues( string $elementtype, string $classpath, string $module, string $myobject): array{
		$mainmodule = "";

		// Pour que les liens apparaissent dans l'interface il est impératif de renseigner
		/*
		$classpath
		$module
		$myobject
		$mainmodule

		 */

		// Special cases, to work with non standard path
		if ($elementtype == 'facture' || $elementtype == 'invoice') {
			$classpath = 'compta/facture/class';
			$module='facture';
			$myobject='facture';
			$mainmodule="billing";

		}elseif ($elementtype == 'product') {
			$classpath = 'product/class';
			$module='product';
			$myobject='product';
			$mainmodule="products";
		}
		elseif ($elementtype == 'ticket' ) {
			$classpath = 'ticket/class';
			$module='ticket';
			$myobject='ticket';
			$mainmodule="ticket";
		}
		elseif ($elementtype == 'holiday' ) {
			$classpath = 'holiday/class';
			$module='holiday';
			$myobject='holiday';
			$mainmodule="hrm";
		}
		elseif ($elementtype == 'societe' ) {
			$classpath = 'societe/class';
			$module='societe';
			$myobject='societe';
			$mainmodule="companies";
		}
		elseif ($elementtype == 'commande' || $elementtype == 'order') {
			$classpath = 'commande/class';
			$module='commande';
			$myobject='commande';
			$mainmodule="commercial";
		}
		elseif ($elementtype == 'contact')  {
			$module = 'societe';
			$mainmodule="companies";
		}
		elseif ($elementtype == 'category')  {
			$classpath = 'categories/class';
			$module ='categorie';
			$myobject='categorie';
		}
		elseif ($elementtype == 'propal')  {
			$classpath = 'comm/propal/class';
			$module ='propal';
			$myobject='propal';
			$mainmodule="commercial";
		}
		elseif ($elementtype == 'shipping') {
			$classpath = 'expedition/class';
			$myobject = 'expedition';
			$module = 'expedition';
			$mainmodule="products";
		}
		elseif ($elementtype == 'delivery') {
			$classpath = 'delivery/class';
			$myobject = 'delivery';
			$module = 'expedition';
			$mainmodule="products";
		}
		elseif ($elementtype == 'contract'|| $elementtype == 'contrat') {
			$classpath = 'contrat/class';
			$module='contrat';
			$myobject='contrat';
			$mainmodule="commercial";
		}
		elseif ($elementtype == 'member') {
			$classpath = 'adherents/class';
			$module='adherent';
			$myobject='adherent';
			$mainmodule="members";
		}
		elseif ($elementtype == 'cabinetmed_cons') {
			$classpath = 'cabinetmed/class';
			$module='cabinetmed';
			$myobject='cabinetmedcons';
		}
		elseif ($elementtype == 'fichinter') {
			$classpath = 'fichinter/class';
			$module='ficheinter';
			$myobject='fichinter';
			$mainmodule="ficheinter";
		}
		elseif ($elementtype == 'expensereport') {
			$classpath = 'expensereport/class';
			$module='expensereport';
			$myobject='expensereport';
			$mainmodule="hrm";
		}
		elseif ($elementtype == 'task') {
			$classpath = 'projet/class';
			$module='projet';
			$myobject='task';
			$mainmodule="project";
		}
		elseif ($elementtype == 'stock') {
			$classpath = 'product/stock/class';
			$module='stock';
			$myobject='stock';
			$mainmodule="product";
		}
		elseif ($elementtype == 'inventory') {
			$classpath = 'product/inventory/class';
			$module='stock';
			$myobject='inventory';
			$mainmodule="product";
		}
		elseif ($elementtype == 'mo') {
			$classpath = 'mrp/class';
			$module='mrp';
			$myobject='mo';
			$mainmodule="mrp";
		}
		elseif ($elementtype == 'salary') {
			$classpath = 'salaries/class';
			$module='salaries';
			$myobject='salary';
			$mainmodule="billing";
		}
		elseif ($elementtype == 'chargesociales') {
			$classpath = 'compta/sociales/class';
			$module='tax';
			$myobject='salary';
			$mainmodule="tax";
		}
		elseif ($elementtype == 'tva') {
			$classpath = 'compta/tva/class';
			$module='tax';
			$mainmodule="tax";
		}
		elseif ($elementtype == 'widthdraw') {
			$classpath = 'compta/prelevement/class';
			$module='prelevement';
			$myobject='bonprelevement';
			$mainmodule="accountancy";
		}
		elseif ($elementtype == 'project' || $elementtype == 'projet') {
			$classpath = 'projet/class';
			$module='projet';
			$myobject = 'project';
			$mainmodule="project";
		}
		elseif ($elementtype == 'project_task') {
			$classpath = 'projet/class';
			$module='projet';
			$mainmodule="project";
		}
		elseif ($elementtype == 'action') {
			$classpath = 'comm/action/class';
			$module='agenda';
			$myobject = 'ActionComm';
			$mainmodule="commercial";
		}
		elseif ($elementtype == 'mailing') {
			$classpath = 'comm/mailing/class';
			$mainmodule="commercial";
		}
		elseif ($elementtype == 'knowledgerecord') {
			$classpath = 'knowledgemanagement/class';
			$module='knowledgemanagement';
			$mainmodule="ticket";
		}
		elseif ($elementtype == 'recruitmentjobposition') {
			$classpath = 'recruitment/class';
			$module='recruitment';
			$mainmodule="hrm";
		}
		elseif ($elementtype == 'recruitmentcandidature') {
			$classpath = 'recruitment/class';
			$module='recruitment';
			$mainmodule="hrm";
		}
		elseif(function_exists('getElementProperties')){
			$element_properties =  getElementProperties($elementtype);
			$classpath = $element_properties['classpath'];
			$module= $element_properties['module'];
		}

		// Generic case for $classfile and $classname
		$classfile = strtolower($myobject);
		$classname = ucfirst($myobject);
		//print "objecttype=".$objecttype." module=".$module." subelement=".$subelement." classfile=".$classfile." classname=".$classname;
		if ($elementtype == 'invoice_supplier') {
			$classfile = 'fournisseur.facture';
			$classname = 'FactureFournisseur';
			$classpath = 'fourn/class';
			$module = 'fournisseur';
			$mainmodule="billing";
		}
		elseif ($elementtype == 'order_supplier') {
			$classfile = 'fournisseur.commande';
			$classname = 'CommandeFournisseur';
			$classpath = 'fourn/class';
			$module = 'fournisseur';
			$mainmodule="commercial";
		}
		elseif ($elementtype == 'supplier_proposal')  {
			$classfile = 'supplier_proposal';
			$classname = 'SupplierProposal';
			$classpath = 'supplier_proposal/class';
			$module = 'supplier_proposal';
			$mainmodule="commercial";
		}
		elseif ($elementtype == 'stock') {
			$classpath = 'product/stock/class';
			$classfile = 'entrepot';
			$classname = 'Entrepot';
			$mainmodule="products";
		}
		elseif ($elementtype == 'dolresource') {
			$classpath = 'resource/class';
			$classfile = 'dolresource';
			$classname = 'Dolresource';
			$module = 'resource';
			$mainmodule="agenda";
		}
		elseif ($elementtype == 'payment_various') {
			$classpath = 'compta/bank/class';
			$module='tax';
			$classfile = 'paymentvarious';
			$classname = 'PaymentVarious';
			$mainmodule="billing";
		}
		elseif ($elementtype == 'bank_account') {
			$classpath = 'compta/bank/class';
			$module='banque';
			$classfile = 'account';
			$classname = 'Account';
			$mainmodule="bank";
		}
		elseif ($elementtype == 'adherent_type')  {
			$classpath = 'adherents/class';
			$module = 'member';
			$classfile='adherent_type';
			$classname='AdherentType';
			$mainmodule="members";
		}
		else if($elementtype == 'facturerec') {
			$classfile = 'facture-rec';
			$classpath = 'compta/facture/class';
			$module = 'facture';
			$classname = 'FactureRec';
			$mainmodule="billing";
		}
		else if($elementtype == 'productlot') {
			$classfile = 'productlot';
			$classpath = 'product/stock/class/';
			$module = 'product';
			$classname = 'ProductLot';
			$mainmodule="products";

		}

		return array($classpath,$module,$classfile, $classname, $mainmodule);
	}

	/**
	 *  on récupère depuis la table llx_menu le mainmenu  si on trouve une concordance avec la fin de l'url
	 * @param string $url
	 * @return string
	 */
	public static function getMainMenuFromElement(string $url) {
		global $db;

		// Vérifier si "custom" est présent dans l'URL
		if (strpos($url, 'custom') !== false) {
			// Extraire la partie de l'URL après "custom" et avant le premier "?"
			$urlPartie = substr($url, strpos($url, 'custom') + strlen('custom'), strpos($url, '?') - strpos($url, 'custom'));
		// sinon htdocs
		}elseif (strpos($url, 'htdocs') !== false) {
			$urlPartie = substr($url, strpos($url, 'htdocs') + strlen('htdocs'), strpos($url, '?') - strpos($url, 'htdocs'));
		// sinon le nom de domaine
		}else{
			$urlPartie = substr($url, strpos($url, DOL_URL_ROOT) + strlen(DOL_URL_ROOT), strpos($url, '?') - strpos($url, DOL_URL_ROOT));
		}

		// Vérifier s'il y a un paramètre dans l'URL restante et le supprimer
		$posParametre = strpos($urlPartie, '?');
		if ($posParametre !== false) {
			// Si un paramètre est présent, enlever tout ce qui vient après
			$urlPartie = substr($urlPartie, 0, $posParametre);
		}

		//prendre la fin du lien et recherche dans la table llx_menu colonne url
		$sql =  "SELECT fk_mainmenu FROM ".MAIN_DB_PREFIX."menu WHERE url LIKE '%" . $urlPartie . "%' LIMIT 1";

		// si je trouve je prend fk_mainmenu
		$resql = $db->query($sql);
		if ($resql && $db->num_rows($resql) >  0) {
				$obj = $db->fetch_object($resql);
				return $obj->fk_mainmenu;
		}

		return '';
	}
}
