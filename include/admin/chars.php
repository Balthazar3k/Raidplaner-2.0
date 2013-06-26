<?php 
defined ('main') or die ( 'no direct access' );
defined ('admin') or die ( 'only admin access' );

switch($menu->get(1)){

	case "removeAll":
		if(!permission('removeCharakter')){ $raid->status(false, 'noperm', 'exit'); }
		$charaktere = getAssocArray('SELECT id FROM prefix_raid_charaktere');
		foreach( $charaktere as $c )
			$raid->removeCharakter( $c['id'] );
		
		$raid->setStatus(true);
	break;
	
	case "jsonUser":
		exit(json_decode(getAssocArray("SELECT id, name FROM prefix_user ORDER BY name ASC")));
	break;
	
	case "changeUser": #ajaxAction
		if(!permission('editCharakter')){ $raid->status(false, 'noperm', 'exit'); }
		
		if( isset($_POST['uid']) &&  !empty($_POST['uid']) ){
			# Wenn der Charakter noch keine User hat, können ihm auch keine Rechte entzogen werden.
			$raid->removeModuleRights(escape($_POST['olduid'], 'integer'));										#Modulrechte vom Alten user entfernen!
		}
		
		$raid->status($raid->update('prefix_raid_charaktere', 'id->id:i', 'val:user:i', 'olduid:strip', 'rang:strip'), 'changeUser', 3);
		$raid->setModuleRights(escape($_POST['val'], 'integer') ,escape($_POST['rang'], 'integer'));			#Modulrechte dem neuem User zuteilen!
		arrPrint(__LINE__, $_POST );
		$raid->setStatus(true);
	break;
	
	case "changeRank": #ajaxAction
		if(!permission('editCharakter')){ $raid->status(false, 'noperm', 'exit'); }
		$resStatus = array();
		$raid->changeModuleRights(escape($menu->get(3), 'integer') ,escape($_POST['rank'], 'integer'));
		$resStatus[] = db_query("UPDATE prefix_raid_charaktere SET rank='".escape($_POST['rank'], 'integer')."' WHERE id='".escape($menu->get(2), 'integer')."'");
		$raid->status(( in_array( 0, $resStatus) ? FALSE : TRUE ), 'changeRang', 3);
		$raid->setStatus(true);
	break;
	
	case "edit":
		if(!permission('editCharakter')){ $raid->status(false, 'noperm', 'exit'); }
		$design = new design ( 'Charaktere', '&Uuml;bersicht', 2 );
		$design->header();
		$raid->changeModuleRights($_POST['user'], $_POST['rank']);
		$raid->update('prefix_raid_charaktere', 'id->id:i', 'user:i', 'name:s', 'level:i', 'rassen:i', 'klassen:i', 's1:s', 's2:s', 'teamspeak:i', 'realm:s', 'rank:i', 'recht:strip' );
		wd("admin.php?chars-details-" . $_POST['id'], $raid->getLastStatus(), 3);
		$design->footer();
	break;
	
	case "modulerights": #ajaxAction
		## Manuelle Modulrechte einstellung
		if( !permission("editCharakter") ){ $raid->status(false, 'noperm', 'exit'); }
		
		$uid = $menu->get(2);
		
		## Löschen der Rechte!
		$resModules = simpleArrayFromQuery("SELECT id FROM prefix_modules WHERE menu='Raidplaner'");
		$resRechte = simpleArrayFromQuery("SELECT id FROM prefix_raid_rechte");
		
		$sqlStatus = array();
		$sqlStatus[] = db_query("DELETE FROM prefix_modulerights WHERE uid='".$uid."' AND mid IN(".implode(", ", $resModules ).");");
		$sqlStatus[] = db_query("DELETE FROM prefix_raid_userrechte WHERE uid='".$uid."' AND mid IN(".implode(", ", $resRechte ).");");
		
		$aTable = array(
			'Permissions' => 'prefix_raid_userrechte',
			'Raidplaner' => 'prefix_modulerights'
		);
		
		if( isset( $_POST['mid'] ) ){
			foreach( $_POST['mid'] as $rechte => $arr ){
				foreach( $arr as $k => $v ){
					if( !empty( $uid ) && !empty( $v ) ){
						$sqlStatus[] = $raid->insert($aTable[$rechte], array('uid' => $uid, 'mid' => $v ), 'uid:i', 'mid:i');
					}
				}
			}
		}
		
		if( !in_array(0, $sqlStatus) ){
			$raid->status( true, 'changeRechte', 'exit');
		}else{
			$raid->status( false, 'changeRechte', 'exit');
		}
		
		$raid->setStatus(true);
	break;
	
	case "loadGuild":
		$design = new design ( 'Gilden Daten', 'Laden', 2 );
		$design->header();
		loadBattleNetGuild($_POST['guild'], $_POST['realm']);
		$raid->status(2, 'Gilden Daten werden versucht zu Laden!');
		wd('admin.php?chars', $raid->getLastStatus(), 3);
		$design->footer();
	break;
	
	default:
		$design = new design ( 'Charaktere', '&Uuml;bersicht', 2 );
		$design->header();
		
		$raid->setStatus();
			
		require_once('include/includes/class/iSmarty.php');
		$smarty = new iSmarty();
		$smarty->assign("user", getArray("SELECT id, name FROM prefix_user ORDER BY name ASC"));
		$smarty->assign('klassen', allRowsFromQuery("SELECT id, klassen FROM prefix_raid_klassen WHERE klassen!='TestChar' ORDER BY id ASC"));
		$smarty->assign('rechte', array_merge( array("" => ""), simpleArrayFromQuery("SELECT id, CONCAT('#', id, '. ', name) AS name FROM prefix_raid_rang ORDER BY id ASC")));	
		
		# Charatker Liste Laden
		$search = ( isset( $_POST['search'] ) ? 'WHERE '. $_POST['from'].' LIKE \''.$_POST['search'].'%\' ' : '' );
		
		if( isset( $_POST['klassen'] ) ){
			$key = array_keys( $_POST['klassen'] );
			$search .= "AND b.id = ".$key[0]." ";
		}
		
		$sql = "SELECT 
					a.id, a.name, a.user, a.regist, a.s1, a.s2, a.level, a.rank,
					b.id AS kid, b.klassen, b.color,
					c.id AS uid, c.name as username, c.gebdatum,
					d.id AS rid, d.name AS rangname
				FROM prefix_raid_charaktere AS a 
					LEFT JOIN prefix_raid_klassen AS b ON a.klassen = b.id 
					LEFT JOIN prefix_user AS c ON a.user = c.id 
					LEFT JOIN prefix_raid_rang AS d ON a.rank = d.id
				".$search."
				ORDER BY a.rank ASC ";
		$char = allRowsFromQuery($sql);
		
		$smarty->assign('char', $char);
		$smarty->display('raid/charakter_list.tpl');
		arrPrint(__LINE__, $char);
		$design->footer();
	break;
	
	case "details":
		$charakter = db_fetch_assoc(db_query("
			SELECT 
				a.id, a.name, a.level, a.klassen, a.rassen, a.rank,
				a.s1, a.s2, a.warum,
				a.teamspeak, 
				a.realm, 
				b.id AS rid, b.name as rechtname,
				c.id AS uid, c.name AS username
			FROM prefix_raid_charaktere AS a 
				LEFT JOIN prefix_user AS c ON a.user=c.id 
				LEFT JOIN prefix_grundrechte AS b ON c.recht=b.id 
			WHERE
			a.id = ".$menu->get(2)." 
			LIMIT 1
		"));
		
		arrPrint(__LINE__, $charakter);
		
		$moduleRechteSQL = "
			(SELECT
				id, name, menu, pos,
					IF( prefix_modulerights.mid != 'NULL', 'checked=\"checked\"', '') AS checked
			FROM prefix_modules
				LEFT JOIN prefix_modulerights ON prefix_modulerights.mid=prefix_modules.id AND prefix_modulerights.uid=".$charakter['uid']."
			WHERE
				prefix_modules.menu='Raidplaner')

			UNION

			(SELECT
				id, name, menu, pos,
					IF( prefix_raid_userrechte.mid != 'NULL', 'checked=\"checked\"', '') AS checked
			FROM prefix_raid_rechte
				LEFT JOIN prefix_raid_userrechte ON prefix_raid_userrechte.mid=prefix_raid_rechte.id AND prefix_raid_userrechte.uid=".$charakter['uid']."
			WHERE
				prefix_raid_rechte.menu='Permissions')
					
			ORDER BY pos ASC
		";
		
		require_once('include/includes/class/iSmarty.php');
		$smarty = new iSmarty();
		$smarty->assign('charakter', $charakter);
		$smarty->assign("user", simpleArrayFromQuery("SELECT id, name FROM prefix_user"));
		$smarty->assign("rank", array_merge( array("" => ""), simpleArrayFromQuery("SELECT id, name FROM prefix_raid_rang ORDER BY id ASC")));
		$smarty->assign("rassen", simpleArrayFromQuery("SELECT id, rassen FROM prefix_raid_rassen WHERE " . faction()));
		$smarty->assign("klassen", simpleArrayFromQuery("SELECT id, klassen FROM prefix_raid_klassen"));
		$smarty->assign("spz", $raid->getSpz($charakter['klassen']));
		$smarty->assign("modules", allRowsFromQuery($moduleRechteSQL));
		
		defined ('main') or die ( 'no direct access' );
		defined ('admin') or die ( 'only admin access' );
		
		$design = new design ( 'Bearbeiten von ' . $charakter['name'], '<a href="admin.php?chars">&Uuml;bersicht</a> &#8250; Bearbeiten von ' . $charakter['name'], 2 );
		$design->header();
		
		$raid->setStatus();
		
		$smarty->display('raid/charakter_update.tpl');
		
		copyright();
		
		$design->footer();
	break;
}

?>