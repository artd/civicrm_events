<?php
/*------------------------------------------------------------------------
# mod_civicrm_events - CiviCRM Events Module
# ------------------------------------------------------------------------
# author Artd Webdesign GmbH
# copyright Copyright (C) artd.ch. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.artd.ch
# Technical Support:  http://www.artd.ch
-------------------------------------------------------------------------*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class JElementEventTypeList extends JElement
{
	var	$_name = 'EventTypeList';

	function fetchElement($name, $value, &$node, $control_name)
	{
		$db			= & JFactory::getDBO();
		$query = 'SELECT value ,label AS text'
			. ' FROM civicrm_option_value'
			. ' WHERE option_group_id=14'
			. ' ORDER BY label'
		;
		$db->setQuery($query);
		$options = $db->loadObjectList();
		$attribs = array();
		$attribs['class'] = 'inputbox';
		$multiple = $node->attributes('multiple') || $node->attributes('multiple') == 'true' || $node->attributes('multiple') == 'multiple';
		if ($multiple) {
			$attribs['multiple'] = 'multiple';
			$attribs['size'] = ( $node->attributes('size') ? (int)$node->attributes('size') : 7 );
		} else {
			array_unshift($options, JHTML::_('select.option', '' , JText::_('MOD_CIVICRM_EVENTS_SELECT_EVENT')));
		}
		
		return JHTML::_('select.genericlist',  $options, ''.$control_name.'['.$name.']'.($multiple ? '[]' : ''), $attribs, 'value', 'text', $value, $control_name.$name );
	}
}
