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

jimport('joomla.base.tree');
jimport('joomla.utilities.simplexml');

/**
 * mod_mainmenu Helper class
 *
 * @static
 * @package		Joomla
 * @subpackage	Menus
 * @since		1.5
 */
class modCiviCRMEventsHelper
{
	function getEvents($params)
	{
		$mode		= $params->get('mode', 0);
		$typeIds	= $params->get('type_ids', array());
		$eventIds	= $params->get('event_ids', array());
		
		$hidePast		= $params->get('hide_past', 1);
		$hideInactive	= $params->get('hide_inactive', 1);
		$privacy		= $params->get('privacy', '');
		
		$orderBy	= $params->get('order_by', 'id');
		if (!in_array($orderBy, array('id', 'title', 'start_date', 'end_date'))) {
			$orderBy = 'id';
		}
		$orderDirn	= $params->get('order_dir', 'ASC');
		if (!in_array($orderDirn, array('ASC', 'DESC'))) {
			$orderDirn = 'ASC';
		}
		
		$link		= $params->get('link', 'info');
		$modal		= $params->get('modal', 0);
		$limit		= $params->get('limit', 5);
		$showDate	= $params->get('show_date', 1);
		$showType	= $params->get('show_type', 1);
		$readmore	= $params->get('readmore', 1);
		$readmoreText	= $params->get('readmore_text', 'Read More...');
		$showSummary	= $params->get('show_summary', 1);
		$summaryLimit	= $params->get('summary_limit', 100);
		
		$where = array();
		switch ($mode) {
			// Default Mode do nothing
			case '0':
				break;
			// Type Mode
			case '1':
				JArrayHelper::toInteger($typeIds, $typeIds);
				if (empty($typeIds)) {
					return array();
				} else {
					$where[] = 'a.event_type_id IN ( '.implode(',', $typeIds).' )'; 
				}
				break;
			// Event Mode
			case '2':
				JArrayHelper::toInteger($eventIds, $eventIds);
				if (empty($eventIds)) {
					return array();
				} else {
					$where[] = 'a.id IN ( '.implode(',', $eventIds).' )';
				}
				break;
		}
		if ($hidePast) {
			$date = JFactory::getDate();
			$where[] = 'start_date >= NOW()';
		}
		if ($hideInactive) {
			$where[] = 'is_active = 1';
		}
		if ($privacy !== '') {
			$where[] = 'is_public = '.(int)$privacy;
		}
		if ($link == 'reg') {
			$where[] = 'is_online_registration = 1';
		}
		
		$where[] = 'is_template = 0';
		$db =& JFactory::getDBO();
		
		$query = 'SELECT a.id, a.title, a.summary, a.start_date, a.end_date'
			. ', a.event_type_id, b.event_type_title'
			. ' FROM civicrm_event AS a'
			. ' LEFT JOIN ( SELECT label AS event_type_title, value AS type_id'
				. ' FROM civicrm_option_value'
				. ' WHERE option_group_id = 14 ) AS b ON b.type_id = a.event_type_id'
			. ' WHERE '.implode(' AND ', $where)
			. ' ORDER BY '.$orderBy.' '.$orderDirn
		;
		
		$db->setQuery($query, 0, $limit);
		
		return $db->loadObjectList();
	}
	
	function generateLink(&$events, $params)
	{
		$link		= $params->get('link', 'info');
		$modal		= $params->get('modal', 0);
		
		if ($modal) {
			$extra = '&tmpl=component';
		}
		
		for ($i = 0, $n = count($events); $i < $n; $i++) {
			$event = &$events[$i];
			if ($link == 'info') {
				$event->link = JRoute::_( 'index.php?option=com_civicrm&task=civicrm/event/info&reset=1&id='. $event->id.$extra );
			} else {
				$event->link = JRoute::_( 'index.php?option=com_civicrm&task=civicrm/event/register&reset=1&id='. $event->id.$extra );
			}
		}
	}
}