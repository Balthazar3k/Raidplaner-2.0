<?php
defined ('main') or die ( 'no direct access' );
defined ('admin') or die ( 'only admin access' );

switch( $menu->get(1) )
{	
	// NEUE ZEITEN ERSTELLEN
	case "create":
		if( !permission('createEventzeiten') ) { $status->close(); }
		
		arrPrint($_POST);
		
		$stat = true;
		$stat = ( empty( $_POST['info'] ) ? $status->f("Das Feld \"Info\" ben&ouml;tigt eine Eingabe") : $stat );
		$stat = ( empty( $_POST['start'] ) ? $status->f("Das Feld \"Invite\" ben&ouml;tigt eine Zeit") : $stat );
		$stat = ( empty( $_POST['begin'] ) ? $status->f("Das Feld \"Pull\" ben&ouml;tigt eine Zeit") : $stat );
		$stat = ( empty( $_POST['ende'] ) ? $status->f("Das Feld \"Ende\" ben&ouml;tigt eine Zeit") : $stat );
		$stat = ( empty( $_POST['sperre'] ) ? $status->f("Das Feld \"Close\" ben&ouml;tigt eine Wert") : $stat );
		
		if( $stat == true ){
			db_query("INSERT INTO prefix_raid_zeit (`info`, `start`, `begin`, `ende`, `sperre`) VALUES(
					'".escape($_POST['info'] , "string")."',
					'".escape($_POST['start'] , "string")."',
					'".escape($_POST['begin'] , "string")."',
					'".escape($_POST['ende'] , "string")."',
					'".escape($_POST['sperre'] , "integer")."');");
			
			$status->t("neue Zeit wurde eingetragen");
		}
		
		$status->close();
	break;
	
	// ZEITEN BEARBEITEN
	case "update":
		if( !permission('editEventzeiten') ) { $status->close(); }
		
		arrPrint($_POST);
		
		$db->update("prefix_raid_zeit", $_POST);
		db_query("UPDATE prefix_raid_charaktere SET zeit=1 WHERE zeit=0;");
		
		$status->t("Success");
		$status->close();
	break;
	
	case "delete":
		if( !permission('removeEventzeiten') ) { $status->close(); }
		
		if( db_query("DELETE FROM prefix_raid_zeit WHERE id='".$_POST['id']."' LIMIT 1;") &&
			db_query("DELETE FROM prefix_raid_charzeiten WHERE zid='".$_POST['id']."';") )
		{
			$status->t("Success");
		}else
		{	$status->f("Mistake");
		}
		
		$status->close();
	break;
	
	default:
		$design = new design ( 'Raidzeiten', 'Erstellen/Bearbeiten', 2 );
		$design->header();
		
		require_once('include/includes/class/iSmarty.php');
		$smarty = new iSmarty();
		
		$row = allRowsFromQuery("SELECT a.* , COUNT( b.id ) AS anz FROM prefix_raid_zeit AS a LEFT JOIN prefix_raid_charzeiten AS b ON b.zid = a.id GROUP BY a.id");
		arrPrint( $row );
		
		$smarty->assign('row', $row); 
		$smarty->display('raid/raidzeiten.tpl');
		
		$design->footer();
	break;
}
?>