<?php
### b3k_func.php Copyright: 2007/2008 edit 2009, 2012 By: Balthazar3k.de

require_once('include/includes/class/raid.class.php');
require_once('include/includes/class/raidTemplate.php');
require_once('include/raidplaner/language/message.php');

$status = new status();
$raid = new raidplaner();

function copyright(){
	echo "<br><div align='center' class='smallfont'>[ Raidplaner v1.4 &copy; by <a href='http://Balthazar3k.funpic.de' target='_blank'>b3k</a> ]</div>";
}

### RAIDPLANER HEADER
$ILCH_HEADER_ADDITIONS = "\n<!-- Raidplaner Header -->\n";
$ILCH_HEADER_ADDITIONS .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"include/includes/css/raidplaner.css\">\n"; 
$ILCH_HEADER_ADDITIONS .= "<script type=\"text/javascript\" src=\"include/includes/js/raidplaner.js\"></script>\n";
$ILCH_HEADER_ADDITIONS .= "<link rel=\"stylesheet\" media=\"screen\" type=\"text/css\" href=\"include/includes/libs/colorpicker/css/colorpicker.css\" />\n";
$ILCH_HEADER_ADDITIONS .= "<script type=\"text/javascript\" src=\"include/includes/libs/colorpicker/js/colorpicker.js\"></script>\n";
$ILCH_HEADER_ADDITIONS .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"http://fonts.googleapis.com/css?family=Ubuntu:regular,bold&subset=Latin&effect=shadow-multiple|3d\">\n";
$ILCH_HEADER_ADDITIONS .= "<!-- Raidplaner Header END -->\n";

### Links
function aLink( $name, $pfad, $if=0 ){
	if( $if == 0 or $if == 1 ){
		return ( $if == 0 ? '<a href="index.php?'.$pfad.'">'.$name.'</a>' :'<a href="admin.php?'.$pfad.'">'.$name.'</a>');
	}elseif( $if == 2 ){
		return '<a href="'.$pfad.'">'.$name.'</a>';
	}
}
## Links die anbindung an jQuery haben und POST Daten Senden
function pLink( $name, $href, $post=NULL, $reload=false){
	if( is_array( $post ) )
		$post = implode("&", $post);
		
	$reload = ( $reload ? " reload=\"".$reload."\"" : "");	
	return "<a href=\"".$href."\" post=\"".$post."\"".$reload.">".$name."</a>";
}
## Links die anbindung an Fancybox habe
function fLink( $name, $href ){
	return "<a href=\"". pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_BASENAME) ."?".$href."\" >".$name."</a>\n";
}
## Kalender Link
function kLink( $name, $href, $post ){
	return "<a href=\"".$href."\" kalender=\"".$post."\" >".$name."</a>\n";
}
## Module Rechte
function permission($i){
	if( is_admin() ) { return true; } 
	if( isset($_SESSION['authmod'][$i]) )
	{	return true;
	}else{ return false; }
}

##############################################
function DateFormat( $format, $timestamp=0){ # D für wochentag
	$wochentagRename = array( "Sun" => "So", "Mon" => "Mo", "Tue" => "Di", "Wed" => "Mi", "Thu" => "Do", "Fri" => "Fr", "Sat" => "Sa");
	$timestamp = ( $timestamp == 0 ? time() : $timestamp );
	$return = date( $format, $timestamp );
	if( preg_match( "/D/" , $format ) ){
		foreach( $wochentagRename as $key => $value ){
			$return = str_replace( $key, $value, $return );
		}
	}
	return ($return);	
}

##############################################
function is_img( $pfad ){
	if( preg_match('/(\.jpg|\.jpeg|\.png|\.gif|\.bmp)/', $pfad) ){
		return (true);
	}else{
		return (false);
	}
}
##############################################
function imgArray($pfad, &$setExtra=NULL){
	$images = array();
	
	if( $setExtra != NULL ){
		$images = array_merge( $images, $setExtra );
	}
	
	$open = opendir( $pfad );
	while( $pic = readdir( $open )){
		if( is_file( $pfad.$pic )){
			$images[$pfad.$pic] = $pic;
		}
	}
	closedir($open);
	
	return $images;
}

###############################################
function getPost( $str ){
	$re = $_POST[$str];
	unset( $_POST[$str] );
	return $re;
}

function class_img($i){
	$link = 'include/raidplaner/klassen_images/class_'.$i.'.jpg';
	if( file_exists($link)){
		return "<img src='".$link."'>";
	}
}
####
function pz($a, $b, $c = 0){
	if( $a == 0 or $b == 0 ){
		return (0);
	}else{
		return round( ( $a * 100 ) / $b , $c );
	}	
}

function ascape( $string ){
	if( is_integer( $string )){
		$option = 'integer';
	}elseif( is_string( $string ) && strlen( $string ) > 250 ){
		$option = 'textarea';
	}else{
		$option = 'string';
	}
	
	return escape( $string, $option );
}


function Alter( $date ){
	$date = strtotime($date);
	
	$bm = date("m", $date);
	$bt = date("d", $date);
	$bj = date("Y", $date);
	
	$j = date("Y")-$bj;
	if( $bm > date("m") ) $j--;
	if( $bm == date("m") AND $bt > date("d")) $j--;
	return $j;
}

####
function agoTimeMsg( $wert, $lastMsg = 'vor wenigen Sekunden' ){
	$TIME_AGO_sec = round( time() - $wert );
	$TIME_AGO_min = round( $TIME_AGO_sec / 60);
	$TIME_AGO_hrs = round( $TIME_AGO_min / 60);	
	$TIME_AGO_day = round( $TIME_AGO_hrs / 24);
	$TIME_AGO_wek = round( $TIME_AGO_day / 7);
	$TIME_AGO_yea = round( $TIME_AGO_day / 365);
	$TIME_AGO_mon = round( $TIME_AGO_day / 30.42, 0); # 30,42 Tage Durschschnit für ein Monat im Jahr
	
	if($TIME_AGO_sec > ( 86400 * 365 )) return 'vor '. $TIME_AGO_yea .' '.( $TIME_AGO_yea > 1 ? "Jahren" : "Jahr");
	elseif($TIME_AGO_day > 30) return 'vor '. $TIME_AGO_mon .' '.( $TIME_AGO_mon > 1 ? "Monaten" : "Monat");
	elseif($TIME_AGO_sec > ( 86400 * 7 )) return 'vor '. $TIME_AGO_wek .' Wochen';
	elseif ($TIME_AGO_sec > 86400) return 'vor '.$TIME_AGO_day.' Tagen';
	elseif ($TIME_AGO_sec > 3600) return 'vor '.$TIME_AGO_hrs.' Stunden';
	elseif ($TIME_AGO_sec > 60) return 'vor '.$TIME_AGO_min.' Minuten';
	else return $lastMsg;
}

function nuller( $i ){
	return ( strlen( $i ) == 1 ? "0".$i : $i );
}

function loadBattleNet( $name, $realm ){
	global $allgAr;
	if( !accessesToBattleNet() ){ return false; }
	$battleNet = $allgAr['charakterurl'].rawurlencode($realm)."/". rawurlencode($name)."?fields=talents&fields=stats";
	if( $jsonString = @file_get_contents($battleNet, true) )
	{	include("include/raidplaner/language/spezialiesirungs.php"); // $spezialiesirung  Includieren
		$data = json_decode($jsonString, true);
		$new['klassen'] = $data['class'];
		$new['rassen'] = $data['race'];
		$new['level'] = $data['level'];
		$new['points'] = $data['achievementPoints'];
		$new['s1'] = $spezialiesirung[$data['talents'][0]['spec']['name']];
		$new['s2'] = $spezialiesirung[$data['talents'][1]['spec']['name']];
		$new['avatar'] = copyBattleNetImages( $data['name'], $allgAr['avatarurl'] . $data['thumbnail']);
		$new['img'] = copyBattleNetImages( $data['name'], str_replace("avatar", "inset", $allgAr['avatarurl'] . $data['thumbnail']));
		//$new['rest'] = addslashes(json_encode($data['stats']));
		arrPrint(__FUNCTION__, $battleNet, $data );
		return $new;
	}else
	{	return false;
	}
}
# BSP: loadBattleNetGuild("Seven Sins", "Khaz'goroth");
function loadBattleNetGuild( $name, $realm ){
	global $allgAr, $raid;
	if( !accessesToBattleNet() ){ return false; }
	$battleNet = $allgAr['guildurl'].rawurlencode($realm)."/". rawurlencode($name)."?fields=members";
	if( $jsonString = @file_get_contents($battleNet, true) )
	{	include("include/raidplaner/language/spezialiesirungs.php");
		$data = json_decode($jsonString, true);
		$charaktere = simpleArrayFromQuery("SELECT id, name FROM prefix_raid_charaktere");
		
		foreach( $data['members'] as $key => $val ){
			$new[$key]['name'] = $val['character']['name'];
			$new[$key]['realm'] = $val['character']['realm'];
			$new[$key]['klassen'] = $val['character']['class'];
			$new[$key]['rassen'] = $val['character']['race'];
			$new[$key]['level'] = $val['character']['level'];
			$new[$key]['points'] = $val['character']['achievementPoints'];
			$new[$key]['s1'] = ( isset( $val['character']['spec']['name'] ) ? $spezialiesirung[$val['character']['spec']['name']] : '');
			$new[$key]['rank'] = $val['rank'];
			
			if( in_array( $new[$key]['name'], $charaktere ) ){
				// CharakterUpdaten wenn er schon vorhanden ist!
				// Der Nachteil ist hier das ich bei der gildenabfrage nur auf ein Spec zugreifen kann!
				// Es gäbe andere wege das zu Lösen, der Nachteil aber daran ist das das der unauthentiefizierte zufgriff auf die wow API auf 2000 zugriffe beschränkt ist
				$raid->update('prefix_raid_charaktere', $new[$key], 'id->name:s', 'realm:s', 'klassen:i', 'rassen:i', 'level:i', 'points:i', 's1:strip', 'rank:i' );
			}else{
				// Neue Charakter hinzufügen wenn er nicht nicht vorhanden ist!
				$raid->insert('prefix_raid_charaktere', $new[$key], 'name:s', 'realm:s', 'klassen:i', 'rassen:i', 'level:i', 'points:i', 's1:s', 'rank:i' );
			}
		}
		//arrPrint(__FUNCTION__,$charaktere, $new, $battleNet, $data );
		return $new;
	}else
	{	return false;
	}
}

function accessesToBattleNet(){
	# Wenn der Standart wert auf mehr als 2000 geändert wird
	# sollte man aufpassen das die IP nicht von battle.net gesperrt wird
	$max = 2000;

	$cfg = getRow("SELECT schl, wert, typextra FROM prefix_config WHERE schl='BattleNetAccesses' LIMIT 1");

	$time = strtotime($cfg['typextra']);
	
	arrPrint(__FUNCTION__, $cfg, $time);
	
	## Bin hier noch am Testen ob das so Funktioniert!
	if( time() > $time ){
		# Resette wenn ein Tag vorbei ist & setze wieder auf $max - 1 zugriffe
		ilch_updateConfig('BattleNetAccesses', ($max-1), date('Y-m-d', time() + 86400) );
		return (TRUE);
	}elseif( $cfg['wert'] ){
		# Wenn der wert noch nicht 0 erreicht hat dann ziehe in einem ab
		ilch_updateConfig('BattleNetAccesses', ($cfg['wert']-1), $cfg['typextra']);
		return (TRUE);
	}else{
		# wenn der wert 0 hat dann FALSE!
		return (FALSE);
	}
}

function copyBattleNetImages($name, $img){
	$ext = pathinfo( $img, PATHINFO_EXTENSION);
	$name = md5($name);
	if( preg_match("/avatar/", $img ) )
	{	$new =  "include/raidplaner/images/avatar/" . $name . "." . $ext;
		if( copy( $img, $new) )
		{	return	$new;
		}else
		{	return false;
		}
	}elseif(  preg_match("/inset/", $img ) )
	{	$new =  "include/raidplaner/images/inset/" . $name . "." . $ext;
		if( copy( $img, $new) )
		{	return	$new;
		}else
		{	return false;
		}
	}
}

function faction(){
	global $allgAr;
	switch( $allgAr['faction'] )
	{	case 0: return "prefix_raid_rassen.faction='0'"; break;
		case 1: return "prefix_raid_rassen.faction='1'"; break;
		case 2: return "prefix_raid_rassen.faction IN(0, 1)"; break;
	}
}

function klassenSpz( $id, $s1=FALSE, $s2=FALSE ){
	if( empty( $id ) ) { exit("Bitte w&auml;hlen Sie zu erst eine Klasse aus!"); }
	$ss1 = $ss2 = '';
	
	if( $s1 ) { $ss1=' autoSelect="'.$s1.'"'; }
	if( $s2 ) { $ss2=' autoSelect="'.$s2.'"'; }
	
	$res = db_query("SELECT s1b, s2b, s3b FROM prefix_raid_klassen WHERE id=".$id."");
	$row = db_fetch_assoc( $res );
	$kspz = "<select name=\"s1\"".$ss1." class=\"required error\"><option></option><option value=\"".$row['s1b']."\">".$row['s1b']."</option><option value=\"".$row['s2b']."\">".$row['s2b']."</option><option value=\"".$row['s3b']."\">".$row['s3b']."</option></select> ";
	$kspz .= "<select name=\"s2\"".$ss2." class=\"required error\"><option></option><option value=\"".$row['s1b']."\">".$row['s1b']."</option><option value=\"".$row['s2b']."\">".$row['s2b']."</option><option value=\"".$row['s3b']."\">".$row['s3b']."</option></select>";
	return $kspz;
}

function hex2rgba($color, $alpha){

    if ($color[0] == '#')
        $color = substr($color, 1);

    if (strlen($color) == 6)
        list($r, $g, $b) = array($color[0].$color[1],
                                 $color[2].$color[3],
                                 $color[4].$color[5]);
    elseif (strlen($color) == 3)
        list($r, $g, $b) = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
    else
        return false;

    $r = hexdec($r); $g = hexdec($g); $b = hexdec($b);

    return array('r' => $r, 'g' => $g, 'b' => $b, 'rgba' => 'rgba( '. $r . ', '. $g .', '. $b .', '. $alpha .' )'  );
}

function rgb2hex($r, $g=-1, $b=-1){

    if (is_array($r) && sizeof($r) == 3)
        list($r, $g, $b) = $r;

    $r = intval($r); $g = intval($g);
    $b = intval($b);

    $r = dechex($r<0?0:($r>255?255:$r));
    $g = dechex($g<0?0:($g>255?255:$g));
    $b = dechex($b<0?0:($b>255?255:$b));

    $color = (strlen($r) < 2?'0':'').$r;
    $color .= (strlen($g) < 2?'0':'').$g;
    $color .= (strlen($b) < 2?'0':'').$b;
    return '#'.$color;
}

function CountFiles( $pfad ){
 
 	$open = @opendir( $pfad );
	$i = 0;
 	while( $files = @readdir( $open )){
		if( is_file( $pfad . $files )){
			$i++;
		}
 	}
	@rewind($open);
 	@closedir( $open );
	return $i;
}

function icons($str = NULL){
	$iStart = "<img align='absmiddle' class='raidIcons' src='include/raidplaner/images/icons/";
	$iEnd = ".png' border='0' />";
	$icon = array(	"cancel" => $iStart . "cancel" . $iEnd,
					"smart" => $iStart . "smart" . $iEnd,
					"refresh" => $iStart . "refresh" . $iEnd,
					"forward" => $iStart . "forward" . $iEnd,
					"add" => $iStart . "add" . $iEnd,
					"info" => $iStart . "info" . $iEnd,
					"grey" => $iStart . "grey" . $iEnd);
					
	if( !empty( $str ) ){
		return $icon[$str];
	}else{
		return $icon;
	}
}

### Wenn eine Checkbox im Post String kein wert zurück gibt erstellt dise funktion eine
function setCheckboxNull( $anz, $arr ){
	if( $anz > count( $arr ) ){
		for( $i=0; $i<$anz; $i++){
			if( !isset( $arr[$i] ) ){
				$arr[$i] = 0;
			}
		}
		
		arrPrint( $arr );
		return $arr;
	}else{
		return $arr;
	}

}

function arrPrint(){
	global $allgAr, $arrPrintNr;
	
	if( $arrPrintNr == NULL )
		$arrPrintNr = 0;
	
	$arrPrintNr ++;
	
	if( $allgAr['arrPrint'] == 1 )
	{ 	$arg = func_get_args();
	
		if( is_string( $arg[0] ) || is_integer( $arg[0] ) ){
			$name = $arg[0];
			unset( $arg[0] );
		}
		
		echo "<div align='left' id='arrPrint".$arrPrintNr."'><a href='#arrPrint".$arrPrintNr."'>arrPrint:".@$name."</a><br />";
		
		foreach( $arg AS $key => $value )
		{	if( is_array( $arg ) )
			{	echo "<div class=''>
						<legend><b>Argument ".($key+1)."</b></legend>
						<pre stlye=\"text-align: left!important;\">";
				print_r( $value );
				echo "	</pre>
					</div>";
			}
		}
		echo "</div>";
	}
}

function array_transform( $arr ){
	$newArray = array();
	foreach( $arr as $k => $a ){
		if( !is_array( $a ) )
			continue;
		
		foreach( $a as $i => $v ){
			$newArray[$i][$k] = $v;
		}
	}
	arrPrint(__FUNCTION__, $newArray, $arr );
	return $newArray;
}

function db_sameKeyVal( $sql ){
	$newArray = array();
	$res = db_query( $sql );
	while( $row = mysqli_fetch_array( $res ) ){
		$newArray[$row[0]] = $row[0];
	}
	return $newArray;
}

function db_html_options( $sql ){
	$newArray = array();
	$res = db_query( $sql );
	while( $row = mysqli_fetch_array( $res ) ){
		$newArray[$row[0]] = $row[1];
	}
	//arrPrint(__FUNCTION__, $newArray);
	return $newArray;
}

class status {
	private $fehler = array();
	private $erfolg = array();
	private $achtung = array();
	protected $tpl = "";
	protected $trash = array();
	
	public function __construct(){
		$this->tpl = new raidTPL("raid/status.htm");
	}
	
	public function f($msg){
		$this->fehler[] = $msg;
		return false;
	}
	
	public function t($msg){
		$this->erfolg[] = $msg;
		return true;
	}
	
	public function a($msg){
		$this->achtung[] = $msg;
		return true;
	}
	
	public function get(){
		return $this->getMsgString();	
	}
	
	public function set(){
		echo $this->getMsgString();	
	}
	
	public function close(){
		exit($this->getMsgString());	
	}
	
	protected function getMsgString(){
		$status = array();
		
		if( is_array($this->erfolg) && count( $this->erfolg ) > 0 ){
			$str = implode("<br />", $this->erfolg );
			$status[] = $this->tpl->set_ar_get(array("status" => "erfolg", "img" => "validgreen", "msg" => $str), 0);
		}
		
		if( is_array($this->fehler) && count( $this->fehler ) > 0 ){
			$str = implode("<br />", $this->fehler );
			$status[] = $this->tpl->set_ar_get(array("status" => "fehler", "img" => "cancel", "msg" => $str), 0);
		}
		
		if( is_array($this->achtung) && count( $this->achtung ) > 0 ){
			$str = implode("<br />", $this->achtung );
			$status[] = $this->tpl->set_ar_get(array("status" => "achtung", "img" => "attention", "msg" => $str), 0);
		}
		
		$this->fehler = array();
		$this->erfolg = array();
		$this->achtung = array();
		
		if( count( $status ) > 0 ){
			$this->trash = $status;
			return implode("<br />", $status);
		}
	}
}

function getArray( $sql ){
	$i = 0;
	$newArray = array();
	$res = db_query( $sql );
	while( $row = db_fetch_assoc($res) ){
		foreach( $row as $key => $value ){
			$newArray[$key][$i] = $value;
		}
		
		$i++;
	}
	
	return $newArray;
}

function getRow( $sql ){
	return db_fetch_assoc(db_query($sql));
}

function getAssocArray( $sql ){
	$newArray = array();
	$res = db_query($sql);
	while( $row = db_fetch_assoc( $res ) )
		$newArray[] = $row;
		
	return $newArray;
}

function ilch_updateConfig($schl, $wert, $typextra = NULL){
	global $raid;
	$nowMsg = array("BattleNetAccesses");
	db_query("UPDATE prefix_config SET wert='".$wert."', typextra='".$typextra."' WHERE schl='".$schl."';");
	if( !in_array( $schl, $nowMsg ) )
		$raid->status(2, 'ilchcfg', 0);
}

function ilch_getRaidConfigLink(){
	return "admin.php?allg#tabs-raidplaner";
}

/* Ausgemustert

function ilch_setRaidConfigLinkInModules(){
	if( is_admin() )
		$raid->status( db_query("UPDATE prefix_modules SET url='".ilch_getRaidConfigTabId()."' WHERE menu='Raidplaner' AND name='Einstellungen' LIMIT 1"), 'moduleLink');
}

function ilch_getRaidConfigTabId(){
	$arr = simpleArrayFromQuery("SELECT DISTINCT kat FROM `prefix_config` WHERE hide = 0 ORDER BY `kat`,`pos`,`typ` ASC");
	$key = array_search('Raidplaner', $arr ) + 1;
	return "allg#tabs-".$key;
}



Ausgemustert */
?>