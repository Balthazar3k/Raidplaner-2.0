<?php 
defined ('main') or die ( 'no direct access' );
defined ('admin') or die ( 'only admin access' );

switch( $menu->get(1) ){
	case 'rekrutieren':
		$raid->update( 'prefix_raid_klassen', 'id->id:i', 'rs1b:i', 'rs2b:i', 'rs3b:i', 'update:strip' );
		$raid->setStatus(true);
	break;
	
	case 'createConfig':
		$raid->insert('prefix_raid_config', 'type:s', 'key:s', 'value:s');
	break;
	
	case 'updateConfig': #ajaxAction		
		foreach( array_transform( $_POST ) as $k => $arr )
			$raid->update('prefix_raid_config', $arr, 'id->id:i', 'key:s', 'value:s', 'type:s', array('pos' => $k));
			
		$raid->setStatus(true);
	break;
	
	case 'removeConfig': #ajaxAction
		$raid->delete('prefix_raid_config', 'id->id:i' );
		$raid->setLastStatus(true);
	break;
}

$design = new design ( 'Admins Area', 'Admins Area', 2 );
$design->header();

$raid->setStatus();

require_once('include/includes/class/iSmarty.php');
$smarty = new iSmarty();
$smarty->assign('ilchRaidConfig', ilch_getRaidConfigLink());
$smarty->assign('rekrutieren', allRowsFromQuery("SELECT * FROM prefix_raid_klassen ORDER BY id ASC"));
$smarty->assign('config', allRowsFromQuery("SELECT * FROM prefix_raid_config ORDER BY type, pos ASC"));
$smarty->assign('config_type', db_sameKeyVal("SELECT DISTINCT type FROM prefix_raid_config ORDER BY type ASC"));
$smarty->display('raid/raidconfig.tpl');

$design->footer();

?>