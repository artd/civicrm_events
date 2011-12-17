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

// Include the syndicate functions only once
require_once (dirname(__FILE__).DS.'helper.php');

$events = modCiviCRMEventsHelper::getEvents($params);
modCiviCRMEventsHelper::generateLink($events, $params);

$layout = $params->get('layout', 'default');
require(JModuleHelper::getLayoutPath('mod_civicrm_events', $layout));