<?php 
#print_r($_SESSION); 
defined ('main') or die ( 'no direct access' );
defined ('admin') or die ( 'only admin access' );

require_once('include/includes/class/iSmarty.php');
$smarty = new iSmarty();

switch($menu->get(1)){
	case "createEvent":
		if( !permission($menu->get(1)) ) { exit("Sie habe nicht die N&ouml;tigen rechte!"); }
		
		arrPrint( 'Post werte', $_POST );
		
		if( $_POST['time'] ){
			$zeit = $raid->getRow("
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
				'created' => $created,
				'inv' => $v,
				'pull' => $pull[$k],
				'end' => $end[$k],
				'lock' => $zeit['lock']
			);
			
			$sqlStatus[] = $raid->insert('prefix_raid_raid', 'status:i', 'leader:i', 'group:i', 'dungeon:i', 'time:i', 'cycle:i', 'from:s', 'to:s', 'inv:i', 'pull:i', 'end:i', 'lock:i', 'txt:t', $replace);

		}
		arrPrint( 'Query Status', $sqlStatus, $_POST, $res );
		$raid->status(!in_array(0, $sqlStatus), 'createEvent', 5, array('count' => count($sqlStatus)));
		$raid->setStatus(true);
	break;

	case "updateEvent":
		if( !permission($menu->get(1)) ) { exit("Sie habe nicht die N&ouml;tigen rechte!"); }
		
		$created = getPost('created');
	
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
		
		$sqlStatus = array();
		#$sqlStatus[] = $raid->update('prefix_raid_raid', ( $eventsMulti ? 'id->erstellt:i' : 'id->id:i'), 'id:strip', 'statusmsg:i', 'leader:i', 'gruppen:i', 'inzen:i', 'time:i', 'sperre:invsperre:i', 'code:strip', 'txt:t', 'inv:i', 'pull:i', 'ende:i');
		
		arrPrint( 'Variablen/Array', $_POST, $ids, $inv, $pull, $end);
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
		$smarty->assign('status', $db->simpleArray("SELECT id, status FROM prefix_raid_status WHERE sid='eventStatus'") );
		$smarty->assign('leader', $db->simpleArray("SELECT id, name FROM prefix_raid_charaktere ORDER BY rank ASC") );
		$smarty->assign('gruppe', $db->simpleArray("SELECT id, name FROM prefix_raid_gruppen WHERE name!='n/a' ORDER BY name ASC") );
		$smarty->assign('inzen', $db->simpleArray("SELECT id, CONCAT( size, ' | ', alias) AS name FROM prefix_raid_dungeons ORDER BY size, name ASC") );
		$smarty->assign('time', $db->getRows("SELECT id, info, CONCAT('Invite: ', start, ' Pull:',begin, ' Ende:', ende) AS time FROM prefix_raid_zeit ORDER BY info ASC") );
		$smarty->display('raid/event_create.tpl');

		exit();
	break;
		
	case "update":
		if(!permission('createEvent')){ $raid->status(false, 'noperm', 'exit'); }
		
		$smarty->assign('button', 'Events bearbeiten');
		$smarty->assign('bbcode', getBBCodeButtons());
		$smarty->assign('zyklus', $aZyklus);
		$smarty->assign('status', $db->simpleArray("SELECT id, status FROM prefix_raid_status WHERE sid='eventStatus'") );
		$smarty->assign('leader', $db->simpleArray("SELECT id, name FROM prefix_raid_charaktere ORDER BY rank ASC") );
		$smarty->assign('gruppe', $db->simpleArray("SELECT id, name FROM prefix_raid_gruppen WHERE name!='n/a' ORDER BY name ASC") );
		$smarty->assign('inzen', $db->simpleArray("SELECT id, CONCAT( size, ' | ', alias) AS name FROM prefix_raid_dungeons ORDER BY size, name ASC") );
		$smarty->assign('time', $db->getRows("SELECT id, info, CONCAT('Invite: ', start, ' Pull:',begin, ' Ende:', ende) AS time FROM prefix_raid_zeit ORDER BY info ASC") );
		$smarty->assign('event', $db->getRow("SELECT * FROM prefix_raid_raid WHERE id='".escape($menu->get(2), 'integer')."' LIMIT 1") );
		$smarty->display('raid/event_update.tpl');

		exit();
	break;
	
	case "changeStatus":
		if(!permission($menu->get(1))){ $raid->status(false, 'noperm', 'exit'); }
		
		$eventID = escape($menu->get(2), 'integer');
		$statusID = escape($menu->get(3), 'integer');
		
		$raid->status( $db->query("UPDATE prefix_raid_raid SET status='".$statusID."' WHERE id='".$eventID."'", false), $menu->get(1));
	break;
}

$kalender = new kalender;

$design = new design ( 'Admins Area', 'Events vom ' . date("m.Y", $kalender->getDate()), 2 );
$design->header();

$raid->setStatus(false);

if( $menu->getA(1) == 's' ){
	$statusID = $menu->getE(1);
	$sqlWhere = "a.status='".$statusID."'";
}else{
	$sqlWhere = $kalender->where02("a.inv");
}


$smarty->assign('kalenderDate', $kalender->getDate());
$smarty->assign('months', $kalender->months());
$smarty->assign('monthName', $kalender->monthName());
$smarty->assign('jahre', $kalender->years());
$smarty->assign('status', $raid->getRows("SELECT * FROM prefix_raid_status WHERE sid='eventStatus'") );
$smarty->assign('events', $db->getRows( "
	SELECT 
		a.id, a.inv, a.pull, a.end, a.group, a.cycle, a.created, a.status,
		b.alias, b.name AS nameDungeon, b.size,
		c.name AS nameGroup, 
		d.status AS nameStatus, d.color, d.style,
		e.info,
		f.name as nameLeader, 
		(SELECT COUNT(x.id) FROM prefix_raid_anmeldung as x WHERE x.rid = a.id) as registrations
	FROM prefix_raid_raid AS a 
		LEFT JOIN prefix_raid_dungeons AS b ON a.dungeon = b.id
		LEFT JOIN prefix_raid_gruppen AS c ON a.group = c.id
		LEFT JOIN prefix_raid_status AS d ON a.status = d.id
		LEFT JOIN prefix_raid_zeit AS e ON a.time = e.id
		LEFT JOIN prefix_raid_charaktere AS f ON a.leader = f.id 
	WHERE ".$sqlWhere."
	ORDER BY d.id, a.inv  ASC
"));

$smarty->display('raid/event_list.tpl');

copyright();

$design->footer();
?>