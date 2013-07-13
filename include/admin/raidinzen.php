<?php #print_r($_SESSION); 
#print_r($_POST);
defined ('main') or die ( 'no direct access' );
defined ('admin') or die ( 'only admin access' );

switch($menu->get(1)){
	case "create":
		$raid->uploadFile('imgUpload', $_POST['alias'], 'include/raidplaner/images/dungeons/');
		$raid->status($raid->insert("prefix_raid_dungeons", "id:strip", "alias:s", "name:s", "level:i", "size:i", "img:s", "info:t"), 'createDungeon', 3);
	break;
	case "update":
		$raid->uploadFile('imgUpload', $_POST['alias'], 'include/raidplaner/images/dungeons/');
		$raid->status($raid->update("prefix_raid_dungeons", "id->id:i", "alias:s", "name:s", "level:i", "size:i", "img:s", "info:t"), 'editDungeon', 10);
	break;
	case "remove":
		$action = json_decode($_POST['action'], true);
		$raid->status(db_query("DELETE FROM prefix_raid_dungeons WHERE id='". $action['id'] ."' LIMIT 1"), 'removeDungeon', 5, $action);
		$raid->status(@unlink($action['img']), 'removeImage', 5, $action);
		$raid->setStatus(true);
	break;
	case "removeAll":
		$raid->status($raid->truncate('prefix_raid_dungeons'), 'remove', 10);
		$raid->setStatus(true);
	break;
	case 'jsonData':
		exit(json_encode(getRow("SELECT * FROM prefix_raid_dungeons WHERE id='".$menu->get(2)."' LIMIT 1")));
	break;
}

require_once('include/includes/class/iSmarty.php');
$smarty = new iSmarty();


$design = new design ( 'Dungeon\'s Erstellen/Bearbeiten', 'Erstellen/Bearbeiten', 2 );
$design->header();
$raid->setStatus(false);

$imgMerge = array("" => "", "upload" => "Bild Hochladen");
$images = imgArray('include/raidplaner/images/dungeons/', $imgMerge);
$smarty->assign('images',  $images);
$smarty->assign('inzen', allRowsFromQuery('SELECT * FROM `prefix_raid_dungeons` ORDER BY name ASC, level DESC'));
$smarty->display('raid/raidinzen.tpl');

copyright();

$design->footer();

?>