<?php
/*
 *	Mit der Zeit werden alle meldungen hier drinne stehen!
 * 	2012 Balthazar3k!
 * 	wenn ihr was an Rechtschreibfehler findet, so weist mich doch bitte drauf hin :)
 */
 
$raidMsg = array();

/* STATUS MELDUNGEN */

$raidMsg['updateBattleNetCharakter'][0] = "Sorry, aber das Update f&uuml;r <b>{name}</b> &uuml;ber Battle.net war nicht m&ouml;glich!";
$raidMsg['updateBattleNetCharakter'][1] = "Update f&uuml;r <b>{name}</b> &uuml;ber Battle.net war erfolgreich";

$raidMsg['noperm'] = "Sie habe nicht die n&ouml;tigen Rechte um diese Aktion auszuf&uuml;ren";
$raidMsg['noArray'] = "Diese eingabe ist kein Array!";

$raidMsg['save&sort'][0] = "Eintr&auml;ge konnten <b>nicht</b> ge&auml;ndert & Sortiert werden!";
$raidMsg['save&sort'][1] = "Eintr&auml;ge wurden ge&auml;ndert & Sortiert!";

$raidMsg['changeRecht'][0] = "Recht konnte <b>nicht</b> ge&auml;ndert werden!";
$raidMsg['changeRecht'][1] = "Recht wurde erfolgreich ge&auml;ndert!";

$raidMsg['changeRechte'][0] = "Rechte konnten <b>nicht</b> ge&auml;ndert werden!";
$raidMsg['changeRechte'][1] = "Rechte wurden ge&auml;ndert!";

$raidMsg['changeRang'][0] = "Der Gilden Rang konnte <b>nicht</b> ge&auml;ndert werden!";
$raidMsg['changeRang'][1] = "Der Gilden Rang wurde erfolgreich ge&auml;ndert!";

$raidMsg['updateRang'][0] = "R&auml;nge konnten <b>nicht</b> ge&auml;ndert werden!";
$raidMsg['updateRang'][1] = "R&auml;nge wurden erfolgreich ge&auml;ndert!";

$raidMsg['removeModuleRights'][0] = "Rechte konnten <b>nicht</b> entzogen werden!";
$raidMsg['removeModuleRights'][1] = "Rechte wurden entzogen!";

$raidMsg['setModuleRights'][0] = "Rechte konnten <b>nicht</b> Zugeteilt werden!";
$raidMsg['setModuleRights'][1] = "Rechte wurden neu Zugeteilt!";

$raidMsg['changeUser'][0] = "dieser Charakter konnte dem User nicht zugeteilt werden!";
$raidMsg['changeUser'][1] = "Charakter wurde erfolgreich dem User zugeteilt";

$raidMsg['moduleRights'][0] = "Module Rechte konnten <b>nicht</b> erfolgreich ge&auml;ndert werden!";
$raidMsg['moduleRights'][1] = "Module Rechte wurden erfolgreich ge&auml;ndert.";

$raidMsg['moduleLink'][0] = "Module Link konnte <b>nicht</b> ge&auml;ndert werden!";
$raidMsg['moduleLink'][1] = "Module Link wurde gespeichert!";

$raidMsg['removeCharakter'][0] = "Charakter <b>{name}</b> konnte <b>nicht</b> gel&ouml;scht werden!";
$raidMsg['removeCharakter'][1] = "Charakter <b>{name}</b> wurde erfolgreich gel&ouml;scht!";

$raidMsg['success'][0] = "Aktion war <b>nicht</b> erfolgreich!";
$raidMsg['success'][1] = "Aktion war erfolgreich!";
$raidMsg['success'][2] = "Achtung!";

$raidMsg['remove'][0] = "L&ouml;schen war <b>nicht</b> erfolgreich!";
$raidMsg['remove'][1] = "L&ouml;schen war erfolgreich!";

$raidMsg['removeImage'][0] = "das Bild \"{img}\" konnte <b>nicht</b> gel&ouml;scht werden!";
$raidMsg['removeImage'][1] = "L&ouml;schen von dem Bild \"{img}\" war erfolgreich!";

$raidMsg['createEvent'][0] = "Beim erstellen eines Event's ist ein Fehler aufgetreten!";
$raidMsg['createEvent'][1] = "{count} Event's wurden erfolgreich erstellt!";

$raidMsg['removeEvent'][0] = "L&ouml;schen des Events war <b>nicht</b> erfolgreich!";
$raidMsg['removeEvent'][1] = "L&ouml;schen des Events war erfolgreich!";

$raidMsg['removeEventsMulti'][0] = "L&ouml;schen der eines Multi Events war <b>nicht</b> erfolgreich!";
$raidMsg['removeEventsMulti'][1] = "L&ouml;schen der {count} Multi Events war erfolgreich!";

$raidMsg['removeEvents'][0] = "L&ouml;schen aller Events & Anmeldungen war <b>nicht</b> erfolgreich!";
$raidMsg['removeEvents'][1] = "L&ouml;schen des Events & Anmeldungen war erfolgreich!";

$raidMsg['createDungeon'][0] = "Beim erstellen von \"{alias} - {name}\" ist ein Fehler aufgetreten!";
$raidMsg['createDungeon'][1] = "Dungeon \"{alias} - {name}\" wurde erfolgreich erstellt!";

$raidMsg['editDungeon'][0] = "Beim bearbeiten von \"{alias} - {name}\" ist ein Fehler aufgetreten!";
$raidMsg['editDungeon'][1] = "Dungeon \"{alias} - {name}\" wurde erfolgreich ge&auml;ndert!";

$raidMsg['removeDungeon'][0] = "Beim L&ouml;schen von \"{alias} - {name}\" ist ein Fehler aufgetreten!";
$raidMsg['removeDungeon'][1] = "Dungeon \"{alias} - {name}\" wurde erfolgreich gel&ouml;scht!";

$raidMsg['createStatus'][0] = "Beim erstellen des Status ist ein Fehler aufgetreten!";
$raidMsg['createStatus'][1] = "Status wurden erfolgreich erstellt!";

$raidMsg['editStatus'][0] = "Beim bearbeiten von einem Status ist ein Fehler aufgetreten!";
$raidMsg['editStatus'][1] = "Status wurde erfolgreich ge&auml;ndert!";

$raidMsg['changeStatus'][0] = "Beim &auml;ndern vom Status ist ein Fehler aufgetreten!";
$raidMsg['changeStatus'][1] = "Status wurde erfolgreich ge&auml;ndert!";

$raidMsg['regist'][0] = "beim Senden der Bewerbung ist ein Fehler aufgetreten, bitte versuchen Sie es sp&auml;ter erneut!";

/* TEXTE */
$raidMsg['rekrutieren'] = "Wir suchen zur Zeite keine weiteren Mitglieder";
$raidMsg['ilchcfg'] = "Es wurden <b>Wichtige</b> einstellungen am System ge&auml;ndert! Bitte &uuml;berpr&uuml;fen Sie diese <a href='".ilch_getRaidConfigLink()."'>Einstellungen</a> im reiter <b>Raidplaner</b>!";

$aZyklus = array(
	0 => 'Einmalig',
	1 => 'T&auml;glich',
	2 => 'W&ouml;chentlich'
);

/* N/A
$raidMsg['name'] = "Name";
$raidMsg['charakter'] = "Charakter";
$raidMsg['charaktere'] = "Charaktere";
$raidMsg['username'] = "Username";
$raidMsg['recht'] = "Recht";
$raidMsg['charaktere'] = "Charaktere";
*/
?>