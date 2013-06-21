<?php 
defined ('main') or die ( 'no direct access' );

if( isset( $_POST['kid'] ))
{	exit(klassenSpz($_POST['kid']));
	//ALTER TABLE `prefix_raid_charaktere` CHANGE `s1` `s1` VARCHAR( 55 ) NOT NULL DEFAULT '0', CHANGE `s2` `s2` VARCHAR( 55 ) NOT NULL DEFAULT '0'
}

switch($menu->get(1)){
	### Überprüft ob der Name schon einmal in der Datenbank vorhanden ist. wenn er nicht vorhanden ist versuchen ob man battle net charakter daten bekommt
	case "battleNet":
		$isset = db_result(db_query("SELECT COUNT(id) FROM prefix_raid_charaktere WHERE name='".escape($_GET['name'], 'string')."'"), 0);
		if( $isset != 0 )
		{	$status->f("Ein Charakter mit dem Name \"".$_GET['name']."\" gibt es bereits! Wenn Sie den Charakter nicht bereits erstellt haben dann wenden sie sich an den Gildenmeister");
			$stat = array("status" => $status->get() );
			exit(json_encode( $stat ));
		}
		
		if( $battleNet = loadBattleNet( $_GET['name'], $_GET['realm']) )
		{	array_walk( $battleNet, function(&$item, $key){ $item = utf8_encode(html_entity_decode( $item )); });
			exit(json_encode( $battleNet ));
		}else{ exit(json_encode(array("find" => false))); }
	break;
	
	### UPDATE von BattleNet wenn möglich
	case "updateBattleNet":
		$row = getRow("SELECT id, name, realm FROM prefix_raid_charaktere WHERE id='".escape( $_POST['id'], 'integer')."' LIMIT 1;");
		
		if( $battleNet = loadBattleNet( $row['name'], $row['realm'] ) )
		{	$battleNet['id'] = $row['id'];
			$raid->update("prefix_raid_charaktere", $battleNet, 'id->id:i');
			$raid->status(1 , "updateBattleNetCharakter", 3, $row);
		}else
		{	$raid->status(0 , "updateBattleNetCharakter", 3, $row);
		}
		
		$raid->setLastStatus(true);
	break;
	
	#### Neuen Charanlegen.
	case "addChar":
		if( $_SESSION['authright'] <= $allgAr['addchar'] ){
			$cCharakter = db_result(db_query("SELECT COUNT(user) FROM prefix_raid_charaktere WHERE user = '".$_SESSION['authid']."'"),0);
			if( $allgAr['maxchars'] > $cCharakter ){
			}else{
				$raid->status(2, "Sie haben kein Charakter slot mehr Frei!", 'exit');
			}
		}else{
			$raid->status(0, "Sie haben nicht die n&ouml;tigen Rechte um ein Charakter zu erstellen!", 'exit');
		}
		
		$isset = db_query("SELECT COUNT(id) FROM prefix_raid_charaktere WHERE name='".escape($_POST['name'], 'string')."'");
		
		if( $isset < 0 )
		{	$raid->status(2, "Ein Charakter mit dem Name \"".$_POST['name']."\" gibt es bereits!", 'exit');
		}
		
		## Überprüfe Formular Daten
		$stat = true;
		$stat = ( empty( $_POST['realm'] ) ? $raid->status( 2, "Bitte geben Sie einen Realm an!") : $stat );
		$stat = ( empty( $_POST['name'] ) ? $raid->status( 2, "Bitte geben Sie einen Namen an") : $stat );
		$stat = ( empty( $_POST['level'] ) ? $raid->status( 2, "Bitte geben Sie ihr Level an") : $stat );
		$stat = ( empty( $_POST['rassen'] ) ? $raid->status( 2, "Bitte w&auml;len sie einen Rasse aus") : $stat );
		$stat = ( empty( $_POST['klassen'] ) ? $raid->status( 2, "Bitte w&auml;len sie einen Klasse aus") : $stat );
		$stat = ( !isset( $_POST['s1'] ) ? $raid->status( 2, "Bitte geben sie die 1. Spezialiesirung an") : $stat );
		$stat = ( !isset( $_POST['s2'] ) ? $raid->status( 2, "Bitte geben sie die 2. Spezialiesirung an") : $stat );
		if( !$stat ){ $raid->setStatus(true); }

		if( $raid->createCharakter(NULL, $_SESSION['authid']) )
		{	############################################################
			if( isset( $_POST['time'] ) && is_array( $_POST['time'] ) )
			{	$id = db_result(db_query("SELECT id FROM prefix_raid_charaktere WHERE name='". $_POST['name'] ."' LIMIT 1"), 0);
				
				foreach( $_POST['time'] as $k => $value )
				{	db_query("INSERT INTO prefix_raid_charzeiten (`cid` ,`zid`) VALUES ('".$id."', '".$value."');");
				}
			}
			
			$raid->status(true, "Charakter , \"".$_POST['name']."\" wurde erfolgreich gespeichert." . $msg);
		}
		
		$raid->setStatus(true);
	break;
	
	case "remove": #ajaxAction
		if( !permission('removeCharakter') ){ $raid->status(false, 'noperm', 'exit'); }
		$raid->removeCharakter( $menu->get(2) );
		$raid->setStatus(true);
	break;
	
	#### Char Bearbeiten
	case "editChar":
	
		## Überprüfe Formular Daten
		$stat = true;
		$stat = ( empty( $_POST['realm'] ) ? $status->f("Bitte geben Sie einen Realm an!") : $stat );
		$stat = ( empty( $_POST['level'] ) ? $status->f("Bitte geben Sie ihr Level an") : $stat );
		$stat = ( empty( $_POST['rassen'] ) ? $status->f("Bitte w&auml;len sie einen Rasse aus") : $stat );
		$stat = ( empty( $_POST['klassen'] ) ? $status->f("Bitte w&auml;len sie einen Klasse aus") : $stat );
		$stat = ( !isset( $_POST['s1'] ) ? $status->f("Bitte geben sie die 1. Spezialiesirung an") : $stat );
		$stat = ( !isset( $_POST['s2'] ) ? $status->f("Bitte geben sie die 2. Spezialiesirung an") : $stat );
		if( !$stat ){ $status->close(); }
		
		##Charakter ID
		$cid = $menu->get(2);
		
		## SQL UPDATE für den Charakter
		$res = db_query("
			UPDATE prefix_raid_charaktere SET 
				`realm` = '".		escape($_POST['realm'], 	'string')."',
				`name` = '".		escape($_POST['name'], 		'string')."',
				`level` = '".		escape($_POST['level'], 	'integer')."',
				`rassen` = '".		escape($_POST['rassen'], 	'integer')."',
				`klassen` = '".		escape($_POST['klassen'], 	'integer')."',
				`s1` = '".			escape($_POST['s1'], 		'string')."',
				`s2` = '".			escape($_POST['s2'], 		'string')."',
				`teamspeak` = '".	escape($_POST['teamspeak'], 'integer')."',
				`avatar` = '".		escape($_POST['avatar'], 	'string')."',
				`img` = '".			escape($_POST['img'], 		'string')."' 
			WHERE `id` = '".		escape($cid, 		'integer')."' LIMIT 1;
		");
		
		db_query("DELETE FROM prefix_raid_charzeiten WHERE cid = '".escape($cid, 'integer')."';");
		
		if( count($_POST['time']) > 0 && $allgAr['charakterzeiten'] > 0 )
		{	
			$newTime = array();
			foreach( $_POST['time'] as $value )
			{
				$newTime[] = "('".$cid."', '".$value."')";
			}
			
			$newTime = implode(", ", $newTime);
			if( $res && db_query("INSERT INTO prefix_raid_charzeiten (`cid`, `zid`) VALUES".$newTime.";") )
			{	
				$status->t("Charakter \"".$_POST['name']."\" wurde erfolgreich editiert, und die Raidzeiten wurden eingetragen!");
			}else
			{	$status->f("2 Charakter \"".$_POST['name']."\" konnte nicht editiert werden!");
			}
		}elseif( $res )
		{	$status->t("Charakter \"".$_POST['name']."\" wurde erfolgreich editiert.");
		}else
		{	$status->f("1 Charakter \"".$_POST['name']."\" konnte nicht editiert werden!");
		}
		
		arrPrint(__LINE__, $_POST, $newTime, $res );
		
		$status->close();
	break;
	
	#### Neuer Charakter Formular
	case "newchar":
		$rassen = 	allRowsFromQuery("SELECT id, rassen FROM prefix_raid_rassen WHERE ".faction()." ORDER BY id ASC");
		$klassen = 	allRowsFromQuery("SELECT id, klassen, color FROM prefix_raid_klassen WHERE klassen!='Testchar' ORDER BY id ASC");
		$time = 	allRowsFromQuery("SELECT id, info, start, begin, ende FROM prefix_raid_zeit ORDER BY info ASC");
		
		require_once('include/includes/class/iSmarty.php');
		$smarty = new iSmarty();
		$smarty->assign('rassen', $rassen);
		$smarty->assign('klassen', $klassen); 
		$smarty->assign('time', $time);
		
		arrPrint(__LINE__, $rassen, $klassen, $time );
		
		$smarty->display('raid/charakter_create.tpl');
		
		exit();
	break;
	
	#### Bearbeiten Char Formular.
	case "edit":
		$charakter = db_fetch_assoc(db_query("
			SELECT 
				a.*
			FROM prefix_raid_charaktere AS a 
			WHERE a.id = ". $menu->get(2) ." LIMIT 1;
		"));
		
		$rassen = simpleArrayFromQuery("SELECT id, rassen FROM prefix_raid_rassen WHERE ".faction()." ORDER BY id ASC");
		$klassen = simpleArrayFromQuery("SELECT id, klassen, color FROM prefix_raid_klassen WHERE klassen!='Testchar' ORDER BY id ASC");
		
		$time = allRowsFromQuery("
			SELECT a.id, a.info, a.start, a.ende,
			IF( b.cid != 'NULL', 'checked=\"checked\"', '') AS checked
			FROM prefix_raid_zeit AS a
			LEFT JOIN prefix_raid_charzeiten AS b ON b.zid = a.id AND b.cid='".$charakter['id']."'"
		);
		
		require_once('include/includes/class/iSmarty.php');
		$smarty = new iSmarty();
		$smarty->assign('char', $charakter );
		$smarty->assign('rassen', $rassen);
		$smarty->assign('klassen', $klassen);
		$smarty->assign('spz', klassenSpz($charakter['klassen'], $charakter['s1'], $charakter['s2']));
		$smarty->assign('time', $time);
		
		arrPrint( __LINE__, $charakter, $rassen, $klassen, $time );
		
		$smarty->display('raid/charakter_edit.tpl');
		
		exit();
	break;
	
	case "show":
		$char = db_fetch_assoc(db_query("SELECT 
											a.*,
											b.id as klassenid, b.klassen, b.color,
											d.name AS username,
											c.rassen,
											e.name AS rechtname
										 FROM prefix_raid_charaktere AS a 
											LEFT JOIN prefix_raid_klassen AS b ON a.klassen = b.id 
											LEFT JOIN prefix_raid_rassen AS c ON a.rassen = c.id 
											LEFT JOIN prefix_user AS d ON a.user = d.id 
											LEFT JOIN prefix_raid_rang AS e ON a.rank=e.id
										 WHERE a.id = ".$menu->get(2)));

		$zeit =  allRowsFromQuery("SELECT b.info, b.start, b.ende FROM prefix_raid_charzeiten AS a LEFT JOIN prefix_raid_zeit AS b ON a.zid=b.id WHERE a.cid ='".$char['id'] ."'");
		
		$twink = allRowsFromQuery("SELECT 
										a.id, a.name, a.level,
										b.rassen,
										c.klassen, c.color 
									FROM prefix_raid_charaktere AS a 
										LEFT JOIN prefix_raid_rassen AS b ON a.rassen=b.id 
										LEFT JOIN prefix_raid_klassen AS c ON a.klassen=c.id 
									WHERE 
										a.user ='".$char['user'] ."'
										AND a.user != '0'
										AND a.id!='".$char['id'] ."'");
							
						
		$design = new design ( "Details von ". $char['name'] , "<a href='index.php?chars'>Charakterliste</a> &#8250; " . "Details von ". $char['name'] );
		$design->header();
		
		arrPrint(__LINE__,$_SESSION, $char, $zeit, $twink );
		
		require_once('include/includes/class/iSmarty.php');
		$smarty = new iSmarty();
		$smarty->assign('char', $char);
		$smarty->assign('zeit', $zeit); 
		$smarty->assign('twink', $twink);
		$smarty->display('raid/charakter_details.tpl');
		$design->footer();
	break;
	
	default:
		$design = new design ( "Charakterliste" , "Charakterliste" );
		$design->header();
		arrPrint(__LINE__,$_SESSION, $_SERVER, $_POST);
		$tpl = new raidTPL ('raid/CHARS_LIST.htm');

		$c['COUNT_CHARS'] = db_result(db_query("SELECT COUNT(id) FROM prefix_raid_charaktere"),0);
		//*Bearbeiten*//$c['COUNT_MAINS'] = db_result(db_query("SELECT COUNT(id) FROM prefix_raid_charaktere WHERE rang >= 4"),0);
		$c['COUNT_LEVEL'] = db_result(db_query("SELECT COUNT(level) FROM prefix_raid_charaktere WHERE level = '".$allgAr['maxlevel']."'"),0);
		$c['COUNT_EIGENE'] = db_result(db_query("SELECT COUNT(user) FROM prefix_raid_charaktere WHERE user = '".$_SESSION['authid']."'"),0);
		//*Bearbeiten*//$c['COUNT_BEWERBER'] = db_result(db_query("SELECT COUNT(id) FROM prefix_raid_charaktere WHERE rang = 1"),0);
		
		if( $_SESSION['authright'] <= $allgAr['addchar'] ){
			if( $allgAr['maxchars'] > $c['COUNT_EIGENE'] ){
				$c['USER'] = "<a class='button' href='index.php?chars-newchar' fancybox=\"inline\">Neuer Charakter</a>";
			}else{
				$c['USER'] = "Max. ".$c['COUNT_EIGENE']."/".$allgAr['maxchars']." Charaktere erreicht";
			}
		}else{
			$c['USER'] = "Sie haben keine Rechte ein Charakter zu erstellen!";
		}
		### Klassen Liste erstellen
		$erg = db_query("SELECT id, klassen, color FROM prefix_raid_klassen ORDER BY id ASC");
		$l_klassen = "<a href='index.php?chars'>".$img_del."</a> ";
		while( $row = db_fetch_assoc( $erg )){
			$c['list_klassen'] .= "
				<a href='index.php?chars-".$row['id']."' tooltip='Filtert ".$row['klassen']."'>
					<img style='border-radius: 5px; border: 3px solid ".$row['color']."; box-shadow: 1px 1px 2px #000;' src='include/raidplaner/images/klassen/class_".$row['id'].".jpg' border=0>
				</a>\n
			";
		}
		### Ausgabe der Daten.
		$tpl->set_ar_out( $c , 0 );
		### CHARS AUFLISTEN ################################################################################################################

		$sort = ( $menu->get(1) != "" ? "AND a.klassen='".$menu->get(1)." '" : "" );

		$q = $_POST['search'];
		$res = db_query("SELECT 
							a.id, a.name, a.s1, a.s2, a.realm, a.user, a.points, a.level, a.avatar,
							b.id as klassenid, b.klassen, b.color, 
							c.rassen, 
							d.name AS username, d.recht,
							e.name AS rechtname
						 FROM prefix_raid_charaktere AS a 
							LEFT JOIN prefix_raid_klassen AS b ON a.klassen = b.id 
							LEFT JOIN prefix_raid_rassen AS c ON a.rassen = c.id
							LEFT JOIN prefix_user AS d ON a.user = d.id 
							LEFT JOIN prefix_raid_rang AS e ON a.rank=e.id
						 WHERE 
						 	a.name LIKE '$q%' 
							   
							".$sort." 
						 ORDER BY a.klassen ASC, d.recht DESC ");

		while( $row = db_fetch_assoc( $res )){
			$klassen = $row['klassen'];
			### Ausgaben Ändern/Hinzufügen
			## Charakter eigentümer
			$row['owner'] = ( $row['user'] == $_SESSION['authid'] || is_admin() ? true : false );
			
			if( $klassen_to_change != $row['klassen'] ){
				$c_klassen = db_result(db_query("SELECT COUNT(id) FROM prefix_raid_charaktere WHERE name LIKE '$q%' AND klassen=".$row['klassenid']),0);
				$c_klassen .= "<a name='".$row['klassen']."'></a>";
				$tpl->set_ar_out( array( "klass_name" => $row['klassen'], "COUNT_KLASSEN" => $c_klassen, "img" => $row['img'], 'color' => $row['color'] ), 1 );
			}
	
			$tpl->set_ar_out( $row, 2 );
			$klassen_to_change = $klassen;
		}
		 
		$tpl->out(3);
		$tpl->out(4);
		$design->footer();
		break;
}
?>