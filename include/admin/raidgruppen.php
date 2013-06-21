<?php 
defined ('main') or die ( 'no direct access' );
defined ('admin') or die ( 'only admin access' );
switch( $menu->get(1) ){
	case 'create':
		if(!permission('createGruppen')){ $raid->status(false, 'noperm', 'exit'); }
		$raid->uploadFile('imgUpload', escape($_POST['name'], 'string'), 'include/raidplaner/images/gruppen/', $img );
		$user = array('uid' => $_SESSION['authid'], 'img' => ( $_POST['img'] == 'upload' ? $img['path'] : $_POST['img'] ) );
		$raid->insert("prefix_raid_gruppen", "id:strip", "name:s", "leader:i", "recht:i", "img:s", "uid:i", "beschreibung:t", $user);
	break;
	
	case 'update':
		if(!permission('updateGruppen')){ $raid->status(false, 'noperm', 'exit'); }
		$raid->uploadFile('imgUpload', escape($_POST['name'], 'string'), 'include/raidplaner/images/gruppen/', $img );
		$merge = array('img' => ( $_POST['img'] == 'upload' ? $img['path'] : $_POST['img'] ));
		$raid->update("prefix_raid_gruppen", "id->id:i", "name:s", "leader:i", "recht:i", "img:s", "beschreibung:t", $merge);
	break;

	case 'remove':
		if(!permission('removeGruppen')){ $raid->status(false, 'noperm', 'exit'); }
		@unlink($_POST['img']);
		$raid->delete('prefix_raid_gruppen', 'name->name:string');
		$raid->setStatus(true);
	break;
	
	case 'sortable':
		if(!permission('updateGruppen')){ $raid->status(false, 'noperm', 'exit'); }
		if( !isset( $_POST['pos'] ) ){	$raid->status(false, 'Kein Array', 'exit'); }
		foreach( $_POST['pos'] as $pos => $id ){
			$raid->update('prefix_raid_gruppen', array('id' => $id, 'pos'=>$pos), 'id->id:i', 'pos:i' );
		}
		$raid->setStatus(true);
	break;
	
	case 'jsonData':
		if(!permission('updateGruppen')){ $raid->status(false, 'noperm', 'exit'); }
		exit(json_encode(db_fetch_assoc(db_query("SELECT * FROM prefix_raid_gruppen WHERE id='".$menu->get(2)."' LIMIT 1"))));
	break;
}

$design = new design ( "Gruppen Verwalten" , "Verwalten", 2 );
$design->header();

$raid->setStatus();

$grp = allRowsFromQuery("
	SELECT 
		a.*,
		b.name AS leader,
		c.name AS rechtname,
		d.name AS ersteller
	FROM prefix_raid_gruppen AS a
		LEFT JOIN prefix_raid_charaktere AS b ON a.leader=b.id
		LEFT JOIN prefix_grundrechte AS c ON a.recht=c.id
		LEFT JOIN prefix_user AS d ON a.uid=d.id
	ORDER BY a.pos, a.name ASC
");
		
$recht = simpleArrayFromQuery("SELECT * FROM prefix_grundrechte ORDER BY id DESC");		
$leader = simpleArrayFromQuery("SELECT id, name FROM prefix_raid_charaktere ORDER BY name ASC");

$imgMerge = array("" => "", "upload" => "Bild Hochladen");
$images = imgArray('include/raidplaner/images/gruppen/', $imgMerge);


require_once('include/includes/class/iSmarty.php');
$smarty = new iSmarty();
$smarty->assign('leader', $leader);
$smarty->assign('recht', $recht);
$smarty->assign('images',  $images);
$smarty->assign('grp', $grp);
$smarty->display('raid/raidgruppen.tpl');

arrPrint(__LINE__,$leader, $recht, $images, $grp);
$design->footer();
?>