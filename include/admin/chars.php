<?php 
defined ('main') or die ( 'no direct access' );
defined ('admin') or die ( 'only admin access' );

switch($menu->get(1)){

	case "removeAll":
		if(!permission('removeCharakter')){ $raid->status(false, 'noperm', 'exit'); }
		$charaktere = getAssocArray('SELECT id FROM prefix_raid_charaktere');
		arrPrint(__LINE__, $charaktere);
		foreach( $charaktere as $c )
			$raid->removeCharakter( $c['id'] );
		
		$raid->setStatus(true);
	break;
	
	case "jsonUser":
		exit(json_decode(getAssocArray("SELECT id, name FROM prefix_user ORDER BY name ASC")));
	break;
	
	case "changeUser": #ajaxAction
		if(!permission('editCharakter')){ $raid->status(false, 'noperm', 'exit'); }
		$raid->status($raid->update('prefix_raid_charaktere', 'id->id:i', 'val:user:i'), 'changeUser', 3);
		$raid->setLastStatus(true);
	break;
	
	case "changeRank": #ajaxAction
		if(!permission('editCharakter')){ $raid->status(false, 'noperm', 'exit'); }
		$raid->status(db_query("UPDATE prefix_raid_charaktere SET rank='".escape($_POST['rank'], 'integer')."' WHERE id='".escape($menu->get(2), 'integer')."'"), 'changeRang', 3);
		$raid->setLastStatus(true);
	break;
	
	case "edit":
		if(!permission('editCharakter')){ $raid->status(false, 'noperm', 'exit'); }
		$design = new design ( 'Charaktere', '&Uuml;bersicht', 2 );
		$design->header();
		
		$raid->update('prefix_raid_charaktere', 'id->id:i', 'user:i', 'name:s', 'level:i', 'rassen:i', 'klassen:i', 's1:s', 's2:s', 'teamspeak:i', 'realm:s', 'rank:i', 'recht:strip' );
		arrPrint(__LINE__, $raid->status );
		wd("admin.php?chars-details-" . $_POST['id'], $raid->getLastStatus(), 3);
		$design->footer();
	break;
	
	case "modulerights": #ajaxAction
		if( !permission("editCharakter") ){ $raid->status(false, 'noperm', 'exit'); }
		
		$uid = $menu->get(2);
		$res = simpleArrayFromQuery("SELECT id FROM prefix_modules WHERE menu IN('Raidplaner', 'RaidPermission')");
		$raid->status(db_query("DELETE FROM prefix_modulerights WHERE uid='".$uid."' AND mid IN(".implode(", ", $res ).");"), NULL, NULL, __LINE__);
		
		if( isset( $_POST['mid'] ) ){
			foreach( $_POST['mid'] as $key => $value ){
				if( !empty( $uid ) && !empty( $value ) ){
					$raid->insert("prefix_modulerights", array('uid' => $uid, 'mid' => $value ), 'uid:i', 'mid:i');
				}
			}
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
		
		require_once('include/includes/class/iSmarty.php');
		$smarty = new iSmarty();
		$smarty->assign('charakter', $charakter);
		$smarty->assign("user", simpleArrayFromQuery("SELECT id, name FROM prefix_user"));
		$smarty->assign("rank", array_merge( array("" => ""), simpleArrayFromQuery("SELECT id, name FROM prefix_raid_rang ORDER BY id ASC")));
		$smarty->assign("rassen", simpleArrayFromQuery("SELECT id, rassen FROM prefix_raid_rassen WHERE " . faction()));
		$smarty->assign("klassen", simpleArrayFromQuery("SELECT id, klassen FROM prefix_raid_klassen"));
		$smarty->assign("spz", $raid->getSpz($charakter['klassen']));
		$smarty->assign("modules", allRowsFromQuery("
			SELECT a.id, a.name, a.menu,
			IF( b.mid != 'NULL', 'checked=\"checked\"', '') AS checked
			FROM prefix_modules AS a
			LEFT JOIN prefix_modulerights AS b ON b.mid = a.id AND b.uid=".$charakter['uid']."
			WHERE a.menu IN('Raidplaner', 'RaidPermission') 
			ORDER BY a.pos ASC
		"));
		
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