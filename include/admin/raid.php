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
		
		if( $_POST['time'] ){
			$zeit = getRow("
				SELECT 
					start as inv,
					begin as pull,
					ende as end,
					sperre as `lock` 
				FROM prefix_raid_zeit 
				WHERE id='".$_POST['time']."';
			");
		}else{
			$zeit = array();
			$zeit['inv'] = getPost('inv');
			$zeit['pull'] = getPost('pull');
			$zeit['end'] = getPost('end');
			$zeit['lock'] = getPost('lock');
		}
		
		list( $istd, $imin ) = explode(":", $zeit['inv'] );
		list( $pstd, $pmin ) = explode(":", $zeit['pull'] );
		list( $estd, $emin ) = explode(":", $zeit['end'] );
		
		
		list($day, $month, $year ) = explode( ".", $_POST['from'] );
		
		$day = 		escape($day, 'integer');
		$month = 	escape($month, 'integer');
		$year = 	escape($year, 'integer');
		$invDate = 	mktime( $istd, $imin, 0, $month, $day, $year); 
		$pullDate = mktime( $pstd, $pmin, 0, $month, $day, $year);
		$endDate = 	mktime( $estd, $emin, 0, $month, $day, $year);
		
		if( !empty( $_POST['to'] ) )
		{	list($day, $month, $year ) = explode( ".", $_POST['to'] );
			$theEnd = mktime( $estd, $emin, 0, $month, $day, $year);
		}else{ $theEnd = NULL; }
		
		$inv = zyklus( $_POST['cycle'], $invDate, $theEnd );
		$pull = zyklus( $_POST['cycle'], $pullDate, $theEnd );
		$end = zyklus( $_POST['cycle'], $endDate, $theEnd );
		
		$created = time();
		
		arrPrint( __LINE__, $_POST, $zeit, $inv, $pull, $end, autoInsertString($_POST));
		
		$sqlStatus = array();
		
		foreach( $inv as $k => $v )
		{	$res[$k] = DateFormat("D d.m.Y H:i", $v);
			
			$replace = array(
				'inv' => $v,
				'pull' => $pull[$k],
				'end' => $end[$k]
			);
			
			$sqlStatus[] = $raid->insert('prefix_raid_raid', 'status:i', 'leader:i', 'group:i', 'dungeon:i', 'time:i', 'cycle:i', 'from:s', 'to:s', 'inv:i', 'pull:i', 'end:i', 'lock:i', 'txt:t', $replace);

		}
		arrPrint( 'Query Status', $sqlStatus, $_POST, $res );
		$raid->status(!in_array(0, $sqlStatus), 'createEvent', 5, array('count' => count($sqlStatus)));
		$raid->setStatus(true);
	break;

	case "updateEvent":
		if( !permission($menu->get(1)) ) { exit("Sie habe nicht die N&ouml;tigen rechte!"); }
		
		if( $_POST['time'] ){
			$res = db_query("SELECT start, begin, ende, sperre FROM prefix_raid_zeit WHERE id=".$_POST['time'].";");
			$zeit = db_fetch_assoc( $res );
		}else{
			$zeit['inv'] = getPost('inv');
			$zeit['pull'] = getPost('pull');
			$zeit['end'] = getPost('end');
			$zeit['lock'] = getPost('lock');
		}
		
		list( $istd, $imin ) = explode(":", $zeit['inv'] );
		list( $pstd, $pmin ) = explode(":", $zeit['pull'] );
		list( $estd, $emin ) = explode(":", $zeit['end'] );
		
		// Datum und Zeit zu UNIX Timestamp umwandeln!
		list($day, $month, $year ) = explode( ".", $_POST['startdate'] );
		$day = escape($day, 'integer');
		$month = escape($month, 'integer');
		$year = escape($year, 'integer');
		$invDate = mktime( $istd, $imin, 0, $month, $day, $year); 
		$pullDate = mktime( $pstd, $pmin, 0, $month, $day, $year);
		$endDate = mktime( $estd, $emin, 0, $month, $day, $year);
		
		if( !empty( $_POST['enddate'] ) )
		{	list($day, $month, $year ) = explode( ".", $_POST['enddate'] );
			$theEnd = mktime( $estd, $emin, 0, $month, $day, $jahr);
		}else{ $theEnd = NULL; }
		
		$inv = zyklus( $_POST['zyklus'], $startDate, $theEnd );
		$pull = zyklus( $_POST['zyklus'], $pullDate, $theEnd );
		$end = zyklus( $_POST['zyklus'], $endDate, $theEnd );
		
		
		
		$eventsMulti = getPost('events');
		
		$sqlStatus = array();
		#$sqlStatus[] = $raid->update('prefix_raid_raid', ( $eventsMulti ? 'id->erstellt:i' : 'id->id:i'), 'id:strip', 'statusmsg:i', 'leader:i', 'gruppen:i', 'inzen:i', 'time:i', 'sperre:invsperre:i', 'code:strip', 'txt:t', 'inv:i', 'pull:i', 'ende:i');
		
		arrPrint( 'Variablen/Array', $_POST, $inv, $pull, $end);
		$raid->status(!in_array(0, $sqlStatus), 'hallo', 5);
		$raid->setStatus(true);
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
			$res = db_query("SELECT id FROM prefix_raid_raid WHERE created='".escape($_POST['created'], 'integer')."'");
			$replaceMessage['count'] = db_num_rows($res);
			
			$sqlStatus[] = db_query("DELETE FROM prefix_raid_raid WHERE created= '".escape($_POST['created'], 'integer')."'");
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
		$smarty->assign('status', db_html_options("SELECT id, status FROM prefix_raid_statusmsg WHERE sid='1'") );
		$smarty->assign('leader', db_html_options("SELECT id, name FROM prefix_raid_charaktere ORDER BY rank ASC") );
		$smarty->assign('gruppe', db_html_options("SELECT id, name FROM prefix_raid_gruppen WHERE name!='n/a' ORDER BY name ASC") );
		$smarty->assign('inzen', db_html_options("SELECT id, CONCAT( size, ' | ', alias) AS name FROM prefix_raid_dungeons ORDER BY size, name ASC") );
		$smarty->assign('time', allRowsFromQuery("SELECT id, info, CONCAT('Invite: ', start, ' Pull:',begin, ' Ende:', ende) AS time FROM prefix_raid_zeit ORDER BY info ASC") );
		$smarty->display('raid/event_create.tpl');

		exit();
	break;
		
	case "update":
		if(!permission('createEvent')){ $raid->status(false, 'noperm', 'exit'); }
		
		$smarty->assign('button', 'Event erstellen');
		$smarty->assign('bbcode', getBBCodeButtons());
		$smarty->assign('zyklus', $aZyklus);
		$smarty->assign('status', db_html_options("SELECT id, statusmsg FROM prefix_raid_status WHERE sid='1'") );
		$smarty->assign('leader', db_html_options("SELECT id, name FROM prefix_raid_charaktere ORDER BY rank ASC") );
		$smarty->assign('gruppe', db_html_options("SELECT id, name FROM prefix_raid_gruppen WHERE name!='n/a' ORDER BY name ASC") );
		$smarty->assign('inzen', db_html_options("SELECT id, CONCAT( size, ' | ', alias) AS name FROM prefix_raid_dungeons ORDER BY size, name ASC") );
		$smarty->assign('time', allRowsFromQuery("SELECT id, info, CONCAT('Invite: ', start, ' Pull:',begin, ' Ende:', ende) AS time FROM prefix_raid_zeit ORDER BY info ASC") );
		$smarty->assign('event', getRow("SELECT * FROM prefix_raid_raid WHERE id='".escape($menu->get(2), 'integer')."' LIMIT 1") );
		$smarty->display('raid/event_update.tpl');

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
					$wayn = "Raid wirklich löschen? (DKP und Anemldungen werden mitgelöscht)";
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
$smarty->assign('status', db_html_options("SELECT id, status FROM prefix_raid_statusmsg WHERE sid='1'") );
$smarty->assign('events', getAssocArray( "
	SELECT 
		a.id, a.inv, a.pull, a.end, a.group, a.cycle, a.created, a.status,
		b.alias, b.name AS nameDungeon, b.size,
		c.name AS nameGroup, 
		d.status AS nameStatus, d.color,
		e.info,
		f.name as nameLeader, 
		(SELECT COUNT(x.id) FROM prefix_raid_anmeldung as x WHERE x.rid = a.id) as registrations
	FROM prefix_raid_raid AS a 
		LEFT JOIN prefix_raid_dungeons AS b ON a.dungeon = b.id
		LEFT JOIN prefix_raid_gruppen AS c ON a.group = c.id
		LEFT JOIN prefix_raid_statusmsg AS d ON a.status = d.id
		LEFT JOIN prefix_raid_zeit AS e ON a.time = e.id
		LEFT JOIN prefix_raid_charaktere AS f ON a.leader = f.id 
	WHERE ".$kalender->where("a.inv")."
	ORDER BY d.id, a.inv  ASC
"));

$smarty->display('raid/event_list.tpl');

copyright();
$design->footer();
?>