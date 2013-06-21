<?php 
defined ('main') or die ( 'no direct access' );

$design = new design ( 'Bewerbung' , 'Bewerbung' );
$design->addheader("\n<script type=\"text/javascript\" src=\"include/includes/js/jquery/jquery.validate.js\"></script>");
$design->header();

switch( $menu->get(1) )
{	case "submitted":
		arrPrint( __LINE__ , $_POST );
		
		### Regestriere USER im ilchSystem
		$res = user_regist($_POST['name'], $_POST['email'], $_POST['pass'] );
		unset(  $_POST['email'], $_POST['pass'], $_POST['password2'] );
		
		## Wenn erfolg cache'e Charakter details
		if( $res && $allgAr['forum_regist_confirm_link'] == 1 ){
			$status->a("Deine Bewerbung wurde abgeschickt, 
						sie erhalten in k&uuml;rze eine eMail mit einen best&auml;tigungs Link,
						mit dem Sie ihr Account freischalten k&ouml;nnen! 
						Die Bewerbung wird so schnell wie m&ouml;glich bearbeitet,
						wir nehmen mit ihnen Kontakt auf!");
			
			# cache'e Charakter details, bis User Aktivierungslink bestätigt wird.
			$_POST['recht'] = $allgAr['bewerberrang'];
			file_put_contents( "include/cache/". md5( $_POST['name'] ) .".raid", serialize( $_POST ) );
			
		}elseif( $res && $allgAr['forum_regist_confirm_link'] == 0 ){
			$uid = db_result(db_query("SELECT id FROM prefix_user WHERE name='".$_POST['name']."' LIMIT 1;"), 0);
			db_query("UPDATE prefix_user SET gebdatum='".$_POST['gebdatum']."', recht='".$allgAr['bewerberrang']."' WHERE id=".$uid." LIMIT 1;");
			
			$_POST['user'] = $uid;
			$cres = $raid->createCharakter(); ## $_POST Incomming
			$tres = $raid->setCharakterTime( $_POST['name'] );
			if(	$cres && $tres )
			{	$status->a("Ihre Bewerbung wurde abgeschickt und 
							wird so schnell wie m&ouml;glich bearbeitet,
							wir werden mit Ihnen Kontakt aufnehmen!.
							Sie k&ouml;nnen sich nun mit ihren Daten einloggen.");
			}else{
				$status->f("Es ist ein Fehler aufgetreten, bitte versuchen Sie es sp&auml;ter erneut! Zeile: ".__LINE__);
			}
		}else{
			$status->f("Es ist ein Fehler aufgetreten, bitte versuchen Sie es sp&auml;ter erneut! Zeile: ".__LINE__);
		}
		
		$status->set();
	break;
	
	default:
		$rassen = simpleArrayFromQuery("SELECT id, rassen FROM prefix_raid_rassen WHERE ".faction()." ORDER BY id ASC");
		$klassen = simpleArrayFromQuery("SELECT id, klassen, color FROM prefix_raid_klassen WHERE klassen!='Testchar' ORDER BY id ASC");
		$time = allRowsFromQuery("SELECT id, info, start, ende FROM prefix_raid_zeit");

		require_once('include/includes/class/iSmarty.php');
		$smarty = new iSmarty();
		$smarty->assign('rassen', $rassen);
		$smarty->assign('klassen', $klassen);
		$smarty->assign('time', $time);
		$smarty->assign('mingebdate', mktime(0,0,0, date("m"), date("d"), date("Y") - $allgAr['maxDate'] ) );

		arrPrint(__LINE__,$_POST, $rassen, $klassen, $time );

		$smarty->display('raid/charakter_apply.tpl');
	break;
}


copyright();
$design->footer();
?>