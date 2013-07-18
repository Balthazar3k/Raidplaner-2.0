<?php #print_r($_SESSION); 
#print_r($_POST);
defined ('main') or die ( 'no direct access' );
defined ('admin') or die ( 'only admin access' );

switch($menu->get(1)){
	case "create":
		$raid->status($raid->insert("prefix_raid_status", "id:strip", "sid:s", "status:s", "color:s", "style:t"), 'createStatus', 3);
	break;
	case "update":
		$raid->status($raid->update("prefix_raid_status", "id->id:i", "sid:s", "status:s", "color:s", "style:t"), 'editStatus', 10);
	break;
	case 'jsonData':
		exit(json_encode($raid->getRow("SELECT * FROM prefix_raid_status WHERE id='".$menu->get(2)."' LIMIT 1")));
	break;
}

require_once('include/includes/class/iSmarty.php');
$smarty = new iSmarty();


$design = new design ( 'Status\'s Erstellen/Bearbeiten', 'Erstellen/Bearbeiten', 2 );
$design->header();
$raid->setStatus(false);

$smarty->assign('sid', $raid->sameKeyVal("SELECT DISTINCT sid FROM prefix_raid_status"));
$smarty->assign('status', $raid->query('SELECT * FROM `prefix_raid_status` ORDER BY sid, id ASC'));
$smarty->display('raid/raidstatus.tpl');

copyright();

$design->footer();

?>