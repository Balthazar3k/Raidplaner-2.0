<?php 
#print_r($_SESSION); 
defined ('main') or die ( 'no direct access' );
defined ('admin') or die ( 'only admin access' );

require_once('include/includes/class/iSmarty.php');
$smarty = new iSmarty();

$tpl = new tpl ( 'raid/raid.htm',1 );

switch($menu->get(1)){
	case "createEvent":
		if( !permission($menu->get(1)) ) { exit("Sie habe nicht die N&ouml;tigen rechte!"); }
		
		arrPrint( 'Post werte', $_POST );
		$res = db_query("SELECT start, begin, ende, sperre FROM prefix_raid_zeit WHERE id=".$_POST['time'].";");
		$zeit = db_fetch_assoc( $res );
		arrPrint( 'Zeiten', $zeit );
		
		list( $istd, $imin ) = explode(":", $zeit['start'] );
		list( $pstd, $pmin ) = explode(":", $zeit['begin'] );
		list( $estd, $emin ) = explode(":", $zeit['ende'] );
		
		
		list($tag, $monat, $jahr ) = explode( ".", $_POST['startdate'] );
		$tag = escape($tag, 'integer');
		$monat = escape($monat, 'integer');
		$jahr = escape($jahr, 'integer');
		$startDate = mktime( $istd, $imin, 0, $monat, $tag, $jahr); 
		$pullDate = mktime( $pstd, $pmin, 0, $monat, $tag, $jahr);
		$endDate = mktime( $estd, $emin, 0, $monat, $tag, $jahr);
		
		if( !empty( $_POST['enddate'] ) )
		{	list($tag, $monat, $jahr ) = explode( ".", $_POST['enddate'] );
			$theEnd = mktime( $estd, $emin, 0, $monat, $tag, $jahr);
		}else{ $theEnd = NULL; }
		
		$inv = zyklus( $_POST['zyklus'], $startDate, $theEnd );
		$pull = zyklus( $_POST['zyklus'], $pullDate, $theEnd );
		$end = zyklus( $_POST['zyklus'], $endDate, $theEnd );
		
		$erstellt = time();
		
		$sqlStatus = array();
		foreach( $inv as $k => $start )
		{	//echo DateFormat("D d.m.Y H:i", $start) . "<br />";
			$sqlStatus[] = db_query("INSERT INTO `prefix_raid_raid` (`statusmsg` ,`leader` ,`gruppen` ,`inzen` ,`time` ,`inv` ,`pull` ,`ende`, `invsperre`, `txt`, `erstellt`, `cid`, `multi`,`von`,`bis` )
			VALUES ('".
			escape($_POST['statusmsg'], "integer")."', '".
			escape($_POST['leader'], 	"integer")."', '".
			escape($_POST['gruppen'], 	"integer")."', '".
			escape($_POST['inzen'], 	"integer")."', '".
			escape($_POST['time'], 		"interger")."', '".
			escape($start, 				"integer")."', '".
			escape($pull[$k], 			"integer")."', '".
			escape($end[$k], 			"integer")."', '".
			escape($zeit['sperre'], 	"integer")."', '".
			escape($_POST['txt'], 		"textarea")."', '".
			escape($erstellt, 			"integer")."', '".
			escape($_SESSION['authid'], "integer")."', '".
			escape($_POST['zyklus'], 	"integer")."', '".
			escape($_POST['startdate'], "string")."', '".
			escape($_POST['enddate'], 	"string")."');");
		}
		arrPrint( 'Query Status', $sqlStatus );
		$raid->status(!in_array(0, $sqlStatus), 'createEvent', 5, array('count' => count($sqlStatus)));
		$raid->setStatus(true);
	break;
	case "editsave":
		if( !permission('editCharakter') ) { exit("Sie habe nicht die N&ouml;tigen rechte!"); }
		
		$res = db_query("SELECT start, begin, ende, sperre FROM prefix_raid_zeit WHERE id=".$_POST['time'].";");
		$zeit = db_fetch_assoc( $res );
		
		list( $istd, $imin ) = explode(":", $zeit['start'] );
		list( $pstd, $pmin ) = explode(":", $zeit['begin'] );
		list( $estd, $emin ) = explode(":", $zeit['ende'] );
		
		list($tag, $monat, $jahr ) = explode( ".", $_POST['startdate'] );
		$startDate = mktime( $istd, $imin, 0, $monat, $tag, $jahr); 
		$pullDate = mktime( $pstd, $pmin, 0, $monat, $tag, $jahr);
		$endDate = mktime( $estd, $emin, 0, $monat, $tag, $jahr);
		

		if(
			db_query("UPDATE prefix_raid_raid SET 
					`statusmsg`='".$_POST['statusmsg']."', 
					`cid`='".$_POST['leader']."', 
					`gruppen`='".$_POST['gruppen']."', 
					`inzen`='".$_POST['inzen']."', 
					`treff`='".$_POST['treff']."', 
					`inv`='".$startDate."', 
					`pull`='".$pullDate."', 
					`ende`='".$endDate."', 
					`invsperre`='".$zeit['sperre']."', 
					`txt`='".$_POST['txt']."'
					WHERE `id`=".$menu->get(2)) )
		{
			exit('Update war erfolgreich!');
		}else
		{
			exit('Update war nicht erfolgreich!');
		} 
		exit();
	break;
	case "removeEvent":
		if( permission('removeEvent') ){
			$sqlStatus = array();
			$sqlStatus[] = db_query("DELETE FROM prefix_raid_raid WHERE id = '".escape($_POST['id'], 'integer')."'");
			$sqlStatus[] = db_query("DELETE FROM prefix_raid_anmeldung WHERE rid = '".escape($_POST['id'], 'integer')."'");
			$raid->status(!in_array(0, $sqlStatus), $menu->get(1));
			$raid->setStatus(true);
		}else{
			exit('don\'t Premission<br>');
		}
	break;
	case "removeEventsMulti":
		if( permission($menu->get(1)) ){
			$sqlStatus = array();
			$res = db_query("SELECT id FROM prefix_raid_raid WHERE erstellt='".escape($_POST['erstellt'], 'integer')."'");
			$replaceMessage['count'] = db_num_rows($res);
			
			$sqlStatus[] = db_query("DELETE FROM prefix_raid_raid WHERE erstellt= '".escape($_POST['erstellt'], 'integer')."'");
			while( $row = db_fetch_assoc( $res )){
				$sqlStatus[] = db_query("DELETE FROM prefix_raid_anmeldung WHERE rid = '".escape($row['id'], 'integer')."'");
			}
			arrPrint( __LINE__, $sqlStatus);
			$raid->status(!in_array(0, $sqlStatus), $menu->get(1), 3000, $replaceMessage);
			$raid->setStatus(true);
		}else{
			exit('don\'t Premission<br>');
		}
	break;
	case "removeEvents":
		if( permission('removeEvents') ){
			$sqlStatus = array();
			$sqlStatus[] = db_query("TRUNCATE prefix_raid_raid;");
			$sqlStatus[] = db_query("TRUNCATE  prefix_raid_anmeldung;");
			$raid->status(!in_array(0, $sqlStatus), $menu->get(1));
			$raid->setStatus(true);
		}else{
			exit('don\'t Premission<br>');
		}
	break;
	
	case "create":
		if(!permission('createEvent')){ $raid->status(false, 'noperm', 'exit'); }
		
		$smarty->assign('button', 'Event erstellen');
		$smarty->assign('bbcode', getBBCodeButtons());
		$smarty->assign('zyklus', $aZyklus);
		$smarty->assign('status', db_html_options("SELECT id, statusmsg FROM prefix_raid_statusmsg WHERE sid='1'") );
		$smarty->assign('leader', db_html_options("SELECT id, name FROM prefix_raid_charaktere ORDER BY rank ASC") );
		$smarty->assign('gruppe', db_html_options("SELECT id, name FROM prefix_raid_gruppen WHERE name!='n/a' ORDER BY name ASC") );
		$smarty->assign('inzen', db_html_options("SELECT id, CONCAT( size, ' | ', alias) AS name FROM prefix_raid_dungeons ORDER BY size, name ASC") );
		$smarty->assign('time', allRowsFromQuery("SELECT id, info, CONCAT('Invite: ', start, ' Pull:',begin, ' Ende:', ende) AS time FROM prefix_raid_zeit ORDER BY info ASC") );
		$smarty->display('raid/event_create.tpl');

		$raid->setStatus(true);
	break;
	
	case "edit":	
		$db = "prefix_raid_raid";
		$res = db_query(" SELECT * FROM prefix_raid_raid WHERE id = '".$menu->get(2)."'");
		$row = db_fetch_assoc( $res );
		$row['PFAD'] = "admin.php?raid-editsave-".$menu->get(2);
		## Selectboxen F�llen
		$tpl->db_array("status", "SELECT id, statusmsg FROM prefix_raid_statusmsg WHERE sid='1'", 																array("id", $row['statusmsg'], "select", "selected=\"selected\""));
		$tpl->db_array("leader", "SELECT id, name FROM prefix_raid_charaktere WHERE rang>='10'", 																array("id", $row['leader'], "select", "selected=\"selected\""));
		$tpl->db_array("gruppen", "SELECT id, gruppen FROM prefix_raid_gruppen WHERE gruppen!='n/a' ORDER BY gruppen ASC", 										array("id", $row['gruppen'], "select", "selected=\"selected\""));
		$tpl->db_array("inzen", "SELECT id, name FROM prefix_raid_dungeons ORDER BY name ASC",																		array("id", $row['inzen'], "select", "selected=\"selected\""));
		$tpl->db_array("time", "SELECT id, info, CONCAT('Invite: ', start, ' Pull:',begin, ' Ende:', ende) AS time FROM prefix_raid_zeit ORDER BY info ASC", 	array("id", $row['time'], "select", "selected=\"selected\""));
		$row['startdate'] = date("d.m.Y", $row['inv']);
		$row['button'] = "&Auml;ndern";
		$row['treff'] = $row['treff'];
		$row['txt'] = $row['txt'];
		
		$tpl->set_ar_out( $row, "editEventForm");
		exit();
	break;

	
	case "kalender":
		$kalender = new kalender();
		$res = db_query( "	SELECT 
								a.id, a.inv, a.gruppen as grp, a.multi,
								b.name as inzen,
								c.name AS grpname, 
								d.statusmsg, d.color,
								f.name as leader, 
								(SELECT COUNT(x.id) FROM prefix_raid_anmeldung as x WHERE x.rid = a.id) as anmeld
							FROM prefix_raid_raid AS a 
								LEFT JOIN prefix_raid_dungeons AS b ON a.inzen = b.id
								LEFT JOIN prefix_raid_gruppen AS c ON a.gruppen = c.id
								LEFT JOIN prefix_raid_statusmsg AS d ON a.statusmsg = d.id 
								LEFT JOIN prefix_raid_charaktere AS f ON a.leader = f.id 
							WHERE ".$kalender->where("a.inv")."
							ORDER BY d.id, a.inv  ASC
						");

		while( $row = db_fetch_assoc( $res )){
			
				$uts = $row['inv'];
				
				if( permission('editRaid') ){
					$row['inv'] = DateFormat("H:i", $row['inv']);
					$row['edit'] = "<a href='admin.php?raid-edit-".$row['id']."' fancybox='inline'><img src='include/images/icons/edit.gif'></a>";
				}else{
					$row['inv'] = DateFormat("H:i", $row['inv']);
					$row['edit'] = "";
				}
				
				if( permission('removeRaid') ){ #$_SESSION['authright']
					$wayl = "admin.php?raid-del-" . $row['id'];
					$wayn = "Raid wirklich l�schen? (DKP und Anemldungen werden mitgel�scht)";
					$row['del'] = "<a href=\"".$wayl."\" confirm=\"".$wayn."\" remove=\"#eventlist".$row['id']."\"><img src='include/images/icons/del.gif'></a>";
				}else{
					$row['del'] = "";
				}

				$inhalt = $tpl->set_ar_get( $row, 'kalenderInhalt');
				
				$kalender->fill( $uts, $inhalt );
		}
		$kalender->out();
		exit();
	break;
}

$design = new design ( 'Admins Area', 'Events', 2 );
$design->header();

$raid->setStatus(false);
$kalender = new kalender;

$smarty->assign('months', $kalender->months());
$smarty->assign('monthName', $kalender->monthName());
$smarty->assign('jahre', $kalender->years());
$smarty->assign('events', getAssocArray( "
	SELECT 
		a.id, a.inv, a.gruppen as grp, a.multi, a.erstellt,
		b.name as inzen, b.alias, b.size,
		c.name AS grpname, 
		d.statusmsg, d.color,
		e.start, e.ende, e.begin, e.info,
		f.name as leader, 
		(SELECT COUNT(x.id) FROM prefix_raid_anmeldung as x WHERE x.rid = a.id) as anmeld
	FROM prefix_raid_raid AS a 
		LEFT JOIN prefix_raid_dungeons AS b ON a.inzen = b.id
		LEFT JOIN prefix_raid_gruppen AS c ON a.gruppen = c.id
		LEFT JOIN prefix_raid_statusmsg AS d ON a.statusmsg = d.id
		LEFT JOIN prefix_raid_zeit AS e ON a.time = e.id
		LEFT JOIN prefix_raid_charaktere AS f ON a.leader = f.id 
	WHERE ".$kalender->where("a.inv")."
	ORDER BY d.id, a.inv  ASC
"));

$smarty->display('raid/event_list.tpl');

copyright();
$design->footer();
?>