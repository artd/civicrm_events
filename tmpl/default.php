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

$link		= $params->get('link', 'info');
$modal		= $params->get('modal', 0);
$showDate	= $params->get('show_date', 1);
$showType	= $params->get('show_type', 1);
$readmore	= $params->get('readmore', 1);
$readmoreText	= $params->get('readmore_text', 'Read More...');
$showSummary	= $params->get('show_summary', 1);
$summaryLimit	= $params->get('summary_limit', 100);

if ($modal) {
	JHTML::_('behavior.modal', 'a.mod_civicrm_events_link');
}

foreach ($events as &$event) {
	if (strlen($event->summary) >= $summaryLimit) {
		$event->summary = wordwrap($event->summary, $summaryLimit);
		$event->summary = substr($event->summary,0,strpos($event->summary,"\n")).'...';
	}
	$startDate	= date('d.m.Y', strtotime($event->start_date));
	$endDate	= date('d.m.Y', strtotime($event->end_date));
	
	$extraInfo = array();
	if ($showDate) {
		$extraInfo[] = ($startDate == $endDate ? $startDate : $startDate.' - '.$endDate);
	}
	if ($showType) {
		$extraInfo[] = $event->event_type_title;
	}
?>
<p>
	<a href="<?php echo $event->link; ?>" title="<?php echo $event->title; ?>" class="mod_civicrm_events_link"<?php echo $modal ? ' rel="{handler: \'iframe\', size: {x: 580, y: 450}}"' : ''; ?>><strong><?php echo $event->title; ?></strong></a><br />
<?php if (!empty($extraInfo)) { ?>
	<small><em><?php echo implode('; ', $extraInfo); ?></em></small><br />
<?php } ?>
<?php if ($showSummary) { ?>
	<?php echo $event->summary; ?>
	<?php if ($readmore) { ?>
	<a href="<?php echo $event->link; ?>" title="<?php echo $event->title; ?>" class="mod_civicrm_events_link"<?php echo $modal ? ' rel="{handler: \'iframe\', size: {x: 580, y: 450}}"' : ''; ?>><?php echo $readmoreText; ?></a>
	<?php } ?>
<?php } ?>
</p>
<?php
}