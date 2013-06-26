<?php
defined ('main') or die ( 'no direct access' );
defined ('admin') or die ( 'only admin access' );

switch( $menu->get(1) ){
	case "create":
		if( isset($_POST['rechte']) )
			$_POST['rechte'] = json_encode( $_POST['rechte'], true);
			
		$raid->insert('prefix_raid_rang', 'id:i', 'name:s', 'rechte:t');
		$raid->updateRangId();
		$raid->status($raid->updateModuleRights(), 'moduleRights', 3);
	break;
	
	case "update": #ajaxAction
		$resStatus = array();
		foreach( $_POST['id'] as $k => $v ){
			$arr['id'] = $v;
			$arr['name'] = $_POST['name'][$k];
			$arr['rechte'] = ( isset( $_POST['rechte'][$v] ) ? json_encode( $_POST['rechte'][$v], true) : NULL );
			$resStatus[] = $raid->update('prefix_raid_rang', $arr, 'id->id:i', 'name:s', 'rechte:t');
		}
		
		$resStatus[] = $raid->updateModuleRights();
		$raid->status(( in_array( 0, $resStatus) ? FALSE : TRUE ),'updateRang', 2);
		$raid->setStatus(true);
	break;
	
	case "remove":
		$raid->delete('prefix_raid_rang',$_POST['id']);
		$raid->updateRangId();
		$raid->status($raid->updateModuleRights(), 'moduleRights', 3);
		$raid->setLastStatus(true);
	break;
	
	case "removeAllRanks":
		$raid->status(db_query('DELETE FROM prefix_raid_rang WHERE id != "0"'), 'remove', 3);
		$raid->status($raid->updateModuleRights(), 'moduleRights', 3);
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
$smarty->assign('recht', allRowsFromQuery("
	(SELECT id, name, gshow, ashow, fright, menu, pos FROM prefix_modules WHERE menu='Raidplaner')
	UNION
	(SELECT id, name, gshow, ashow, fright, menu, pos FROM prefix_raid_rechte)
	ORDER BY pos ASC") );
$smarty->display('raid/raidrang.tpl');

copyright();

$design->footer();
?>