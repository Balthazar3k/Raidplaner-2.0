<?php
defined ('main') or die ( 'no direct access' );
defined ('admin') or die ( 'only admin access' );

## Da die Rechte und Modullinks gleich aufgebaut sind, werden sie in 2 unterschiedliche
## Datanbank Tabellen aufgeteilt.
$aTable = array(
			'Permissions' => 'prefix_raid_rechte',
			'Raidplaner' => 'prefix_modules'
		);

switch( $menu->get(1) ){
	case "create":
		$res = db_query("INSERT INTO ". $aTable[$_POST['menu']] ." (`url`,`name`,`gshow`,`ashow`,`fright`,`menu`,`pos`) VALUES(
					'". $_POST['url'] ."',
					'". $_POST['name'] ."',
					'". ( isset($_POST['gshow']) ? 1 : 0 ) ."',
					'". ( isset($_POST['ashow']) ? 1 : 0 )  ."',
					'". ( isset($_POST['fright']) ? 1 : 0 )  ."',
					'". $_POST['menu'] ."',
					'". $_POST['pos'] ."')"
		);
		
		$raid->status( $res );
	break;
	
	case "update":
		$newArray = $id = $res = array();
		
		$id = $_POST['id'];
		$table = $_POST['menu'];
		$anz = count($id);
		
		unset( $_POST['id'] );
		
		foreach( $_POST['pos'] as $i => $arr ){
			$_POST['pos'][$i] = $i;
		}
		
		foreach( $_POST as $key => $arr ){
			$_POST[$key] = setCheckboxNull( $anz, $_POST[$key] );
			foreach( $_POST[$key] as $i => $value ){
				$newArray[$i][$key] = "`". $key ."`='". $value ."'";
			}
		}
			
		foreach( $newArray as $i => $update ){
			$res[$i] = db_query("UPDATE `". $aTable[$table[$i]] ."` SET ". implode(", ", $update) ." WHERE `id`='".$id[$i]."';");
		}
		
		if( !in_array(0, $res) ){
			$raid->status( true, 'save&sort', 'exit');
		}else{
			$raid->status( false, NULL, 'exit' );
		}
	break;
	
	case "remove":
		$res = db_query("DELETE FROM prefix_raid_rechte WHERE id=".$_POST['id'].";");
		$raid->status( $res, NULL, 'exit');
	break;
}

$design = new design ( 'Raid Rechte', 'Raid Rechte', 2 );
$design->header();

$status->set();
arrPrint( $_POST, @$newArray, @$res, $_SESSION );

$raidRechteSQL = "
	(SELECT id, url, name, gshow, ashow, fright, menu, pos FROM prefix_modules WHERE menu='Raidplaner')
	UNION
	(SELECT id, url, name, gshow, ashow, fright, menu, pos FROM prefix_raid_rechte)
	ORDER BY pos ASC
";

$raidMenuRechteSQL = "
	(SELECT DISTINCT menu FROM prefix_raid_rechte)
	UNION
	(SELECT DISTINCT menu FROM prefix_modules WHERE menu='Raidplaner')
";

require_once('include/includes/class/iSmarty.php');
$smarty = new iSmarty();
$smarty->assign('data', allRowsFromQuery($raidRechteSQL) );
$smarty->assign('menu', db_sameKeyVal($raidMenuRechteSQL));
$smarty->display('raid/raidrecht.tpl');

copyright();

$design->footer();
?>