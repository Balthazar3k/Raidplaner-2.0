<?php
defined ('main') or die ( 'no direct access' );
defined ('admin') or die ( 'only admin access' );

function updateModuleRights(){
	global $raid;
	$stat = array();
	$charaktere = $raid->mainCharaktere();
	arrPrint(__FUNCTION__, $charaktere);
	foreach( $charaktere['user'] as $i => $uid )
		$stat[] = ilch_setModuleRights($uid, json_decode($charaktere['rechte'][$i]));
	
	return ( in_array( 0, $stat) ? FALSE : TRUE );
}

switch( $menu->get(1) ){
	case "create":
		if( isset($_POST['recht']) )
			$_POST['recht'] = json_encode( $_POST['recht'] );
			
		$raid->insert('prefix_raid_rang', 'id:i', 'name:s', 'recht:rechte:t');
		$raid->updateRangId();
		$raid->status(updateModuleRights(), 'moduleRights', 3);
	break;
	
	case "update":
		foreach( $_POST['id'] as $k => $v ){
			$arr['id'] = $v;
			$arr['name'] = $_POST['name'][$k];
			$arr['rechte'] = ( isset( $_POST[$v] ) ? json_encode( $_POST[$v], true) : NULL );
			$raid->update('prefix_raid_rang', $arr, 'id->id:i', 'name:s', 'rechte:t');
		}
		
		updateModuleRights();
		$raid->setStatus(true);
	break;
	
	case "remove":
		$raid->delete('prefix_raid_rang',$_POST['id']);
		$raid->updateRangId();
		$raid->status(updateModuleRights(), 'moduleRights', 3);
		$raid->setLastStatus(true);
	break;
	
	case "removeAllRanks":
		$raid->status(db_query('DELETE FROM prefix_raid_rang WHERE id != "0"'), 'remove', 3);
		$raid->status(updateModuleRights(), 'moduleRights', 3);
	break;
}

$design = new design ( 'Raidplaner', 'Bearbeiten', 2 );
$design->header();

$raid->setStatus();
require_once('include/includes/class/iSmarty.php');
$smarty = new iSmarty();
$smarty->assign('charaktere', allRowsFromQuery("
	SELECT 
		a.id, a.name, a.rank, 
		b.name AS rangname,
		c.id as cid, c.color
	FROM prefix_raid_charaktere AS a 
		LEFT JOIN prefix_raid_rang AS b ON a.rank=b.id 
		LEFT JOIN prefix_raid_klassen AS c ON a.klassen=c.id
	GROUP BY a.rank 
	ORDER BY a.rank, a.name ASC
"));
$smarty->assign('rang', allRowsFromQuery("SELECT * FROM prefix_raid_rang ORDER BY id"));
$smarty->assign('recht', simpleArrayFromQuery("SELECT id, name, menu FROM prefix_modules WHERE menu IN('Raidplaner', 'RaidPermission') ORDER BY pos ASC") );
$smarty->display('raid/raidrang.tpl');

copyright();

$design->footer();
?>