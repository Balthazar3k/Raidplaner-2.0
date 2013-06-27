<?php
#### Erweiterung von der class tpl von ilch
#### Erweiterung von der class tpl von ilch
#### Erweiterung von der class tpl von ilch
#### Erweiterung von der class tpl von ilch
class raidTPL extends tpl
{	var $_SQL = array();
	var $arr = array();
	
	protected $menu = ""; // OBJECT->get()
	
	public $_UTF8 = true;
	public	$_ERROR = array();
	#public $parts = array(); Abschnitte vom template

	function raidTPL($file, $ort = 0)
	{	global $menu; 
	
		$this->menu = $menu; // IMPORTIERE gestartete klasse(object) ...... $menu von ilch
		
		$this->__construct($file, $ort);
		$this->setBoxes();									# Nun ist es auch möglich {_boxes_nextraid} in normalen Seiten zu packen.
		
		array_walk($this->parts, array($this,"config"));		
		array_walk($this->parts, array($this,"setTPL"));	# Filtert {TPL:xxx}[code]{/TPL} und nimmt diese in $this->parts[xxx] auf, kann mit $this->set_ar_out( array(), "xxx"); verarbeitet werden.
		array_walk($this->parts, array($this,"setMenu"));	# SETZT ALLE {menu(nr)} VON DER ADRESSZEILE
		array_walk($this->parts, array($this,"perm"));		# ÜBERPRÜFT RAIDPLANER RECHTE {PERM:(rang)}<HTML>[{ELSE}(<HTML>)]{/PERM}
		//array_walk($this->parts, array($this,"setEval"));	# Die Evel Funktion wird mit {$date("d.m.Y");} aufgerufen
		array_walk($this->parts, array($this,"SQL"));		# LADET ALLE {SQL[=($KEY)]}(SQL_QUERY){/SQL}. $key bsp. wäre sinvoll {_array_($key)_ <HTML>%ARRAY%</HTML> _}
		
	}

	public function stringFunction($tpl)
	{	$replace = $to = array();
		preg_match_all("/\$\{(.*)\:(.*)(\:(.*))?\}/",$this->parts[$tpl],$ar);
	}
	
	private function config(&$item)
	{	$replace = $to = array();
		preg_match_all("/\{CFG:(.*)\}(.*)(\{ELSE\}(.*))?\{\/CFG\}/Us", $item, $arr );
		unset( $arr[3] );
		
		foreach( $arr[0] as $k => $v )
		{	array_push( $replace, $v );
			$cfg = @db_result(@db_query("SELECT wert FROM prefix_config WHERE `schl`='".$arr[1][$k]."' LIMIT 1"),0);
			array_push( $to, (  $cfg ? $arr[2][$k] : ( !empty( $arr[4][$k] ) ? $arr[4][$k] : "" )));
		}
		
		//arrPrint( $replace, $to );
		preg_match_all("/\{CFG=(.*)\}/Us", $item, $arr );
		foreach( $arr[1] as $k => $key )
		{	array_push( $replace, $arr[0][$k] );
			array_push( $to, @db_result(@db_query("SELECT wert FROM prefix_config WHERE `schl`='".$key."' LIMIT 1"),0) );
		}
		
		
		$item = str_replace($replace, $to, $item);
	}
	
	
	### ARRAY WALK : CALLBACK Funktion
	### EVAL CODE
	### {$(function|array|var);}
	function setEval(&$item)
	{	$replace = $to = array();
		preg_match_all("/\{[\$]{1}(.*[\;]{1})\}/", $item, $arr);
		
		foreach( $arr[1] as $key => $value )
		{	$replace[$key] = $arr[0][$key];
			@eval("\$res=".$value);
			$to[$key] = $res; 
		}
		
		//arrPrint( $replace );
		//arrPrint( $to );
		$item = str_replace($replace, $to, $item);
	}
	
	### ARRAY WALK : CALLBACK Funktion
	### MENU
	### {menu(nr)}
	function setMenu(&$item)
	{	$replace = $to = array();
		preg_match_all("/\{menu([0-9]{1,2})\}/", $item, $arr);
		foreach( $arr[1] as $key => $value )
		{	$replace[$key] = $arr[0][$key];
			$to[$key] = $this->menu->get($value); 
		}
		
		$item = str_replace($replace, $to, $item);
	}
	
	### ARRAY WALK : CALLBACK Funktion
	### Überprüft ob der Besucher rechte hat!!!
	### !!!! MIT EINER RAIDPLANER FUNKTION !!!!
	### Bsp. "{PERM:(SESSION:RANG)}(HTML|TEXT)[{ELSE}(HTML|TEXT)]?{/PERM}"
	private function perm(&$item)
	{	$replace = array(); $to = array();
		preg_match_all("/\{PERM:(.*)\}(.*)(\{ELSE\}(.*))?\{\/PERM\}/Us", $item, $arr );

		if( count($arr[0]) > 0 )
		{	foreach( $arr[1] as $k => $v )
			{	$replace[] = $arr[0][$k];
				$to[] = ( permission($v) ? $arr[2][$k] : ( !empty( $arr[4][$k] ) ? $arr[4][$k] : "" ));
			}
			
			//arrPrint( $replace, $to, $arr);
			$item = str_replace($replace, $to, $item );
		}
	}
	
	### ARRAY WALK : CALLBACK Funktion
	### SQL Bsp. {SQL=(name)}SQL_QUERY{/SQL}
	private function SQL(&$item, $key)
	{	preg_match_all("/\{SQL(=([A-Za-z0-9]*))?\}(.*)\{\/SQL\}/Us", $item, $arr);
		
		if( count($arr[0]) > 0 )
		{	$replace = $to = array();
			foreach( $arr[3] as $k => $SQL )
			{	$replace[] = $arr[0][$k];
				
				if( $arr[2][$k] == "return" )
				{	$to[] = db_result(db_query($SQL), 0);
				}elseif( empty( $arr[2][$k] ) )
				{	$to[] = ( is_admin() ? "<!--SQL:AUSGEFÜRT-->" : "" );
					$this->_SQL[$key] = db_fetch_assoc(db_query($SQL));
				}else
				{	$to[] = ( is_admin() ? "<!--SQL:AUSGEFÜRT-->" : "" );
					$this->_SQL[$key][$arr[2][$k]] = $this->db_array($SQL);
				}
			}
			$item = str_replace($replace, $to, $item);
		}
		
	}
	
	### Erweiterung von TPL CODE nun mit {_array_[arraykey]_%[arrayvalue]%(%[arrayvalue]%)_}
	### $sql = ist GLOBAL in der Klasse und eine SQL kann in der gesamten Template abgerufen werden;
	### $sql=int(1)... nimmt er die SQL aus nr.0{EXPLODE}nr.1 (Wenn hier eine SQL ausgefürht wurde){EXPLODE}
	public function xset_ar_out( $arr, $obj, $sql = false )
	{	//$arr = array_merge( $arr, $this->_SQL );
		//arrPrint( $arr );
		@$this->parts[$obj] = $this->set_list( $this->parts[$obj], $this->_SQL );
		array_walk($this->parts, array($this,"setEval"));
		$this->stringFunction($obj);
		parent::set_ar_out($arr, $obj);
	}
	
	
	### -"-  -"-  -"-
	public function xout( $obj, $sql = false )
	{	//arrPrint( $this->_SQL );
		@$this->parts[$obj] = $this->set_list( $this->parts[$obj], $this->_SQL );
		//arrPrint( $this->parts );
		parent::out($obj);
	}
	
	### LADET {_boxes_(FILE)} aus "include/boxes/(FILE).php"
	function setBoxes()
	{	foreach( $this->parts as $k => $v )
		{	
			$this->parts[$k] = $this->include_data("boxes", "include/boxes/", $this->parts[$k] );
		}
	}
	
	private function include_data( $search, $pfad, $string ){
		global $allgAr;
		preg_match_all("/\{_".$search."_([^\{\}]+)\}/", $string, $array );
		
		foreach( $array[1] as $key => $value ){
			ob_start();
			include($pfad.$value.".php");
			$buffer[$key] = ob_get_contents();
			ob_end_clean();
			$string = str_replace($array[0][$key], $buffer[$key] ,$string);
		}
		
		return $string;
	}
	
	private function set_list( $file, $arr )
	{	global $status;
		if( !is_array( $arr ) )
		{	$status->f("Diese eingabe ist kein Array!");
			$status->set();
			return (FALSE);
		}

		preg_match_all("/{_array_([A-Za-z0-9]*)_(.*)_}/Us", $file, $res );
		
		$replace = $to = array();

		foreach( $res[1] as $k => $v )
		{	$replace[$k] = $res[0][$k];
			if( is_array( $arr[$v] ) )
			{	foreach($arr[$v] as $key => $val )
				{	foreach( $arr[$v][$key] as $nr => $str )
					{	if( empty( $qArr[$k][$nr] ) )
						{	$qArr[$k][$nr] = $res[2][$k];
						}
						$qArr[$k][$nr] = str_replace("%". $key ."%", $str, $qArr[$k][$nr] );
					}
					
					$to[$k] = implode("\n", $qArr[$k] );
				}
			}
			
			if( !isset($to[$k]) )
			{	$to[$k] = "";
			}
		}
		
		//arrPrint($replace, $to);
		
		return str_replace( $replace, $to, $file );
	}
	
	public function db_array($name, $sql, $select = FALSE) 
	{	global $status;
		if( empty( $sql ) )
		{	$status->f("der SQL String ist leer");
			return (FALSE);
		}
		
		if( isset( $this->_SQL[$name] ) )
		{	unset( $this->_SQL[$name] );
		}
		
		if( $res = db_query( $sql ) )
		{	$a = array();
			while( $row = db_fetch_assoc( $res ) )
			{	foreach( $row as $k => $v )
				{	$a[$k][] = $v;
					if( is_array($select) && $k == $select[0] && $v == $select[1]  )
					{	$a[$select[2]][] = $select[3];
					}elseif( is_array($select) && $k == $select[0] )
					{	$a[$select[2]][] = '';
					}
				}
			}
		
			$this->_SQL[$name] = $a;
			//arrPrint( $this->_SQL );
		}else{
			$status->f("Konnte SQL nicht ausführen (".$sql.")");
			$status->set();
			return (FALSE);
		}
	}
	
	### CALLBACK FUNCKTION FÜR {TPL:xxx}{/TPL} Speichert es dann in $this->parts
	private function setTPL(&$part)
	{	$replace = array(); $to = array();
		preg_match_all("/\{TPL:(.*)(\:hide)?\}(.*)\{\/TPL\}/Us", $part, $arr );
		//arrPrint( $arr );
		if( count($arr[0]) > 0 )
		{	foreach( $arr[1] as $k => $v )
			{	$this->parts[$arr[1][$k]] = $arr[3][$k];
				$replace[] = $arr[0][$k];
				$to[] = ( $arr[2][$k] == ":hide" ? "" : $arr[3][$k] );
			}

			$part = str_replace($replace, $to, $part );
		}
	}
}

### KALENDER #################################################
### KALENDER #################################################
### KALENDER #################################################
### KALENDER #################################################
### KALENDER #################################################
class kalender
{	
	var $arr = array();
	var $size = 150;
	
	public function __construct($arr=NULL)
	{	$this->arr = $arr;
		//arrPrint( $arr );
		//arrPrint( $this->arr );
	}
	
	public static function where($feld)
	{	if( isset( $_POST['kalenderJahr'] ) && isset( $_POST['kalenderMonat'] ) )
		{	$monat = nuller($_POST['kalenderMonat']);
			$jahr = nuller($_POST['kalenderJahr']);
		}else
		{	$monat = date("m");
			$jahr = date("Y"); 
		}
		
		$ersterTag = mktime( 0, 0, 0, $monat, 1, $jahr );
		$tageImMonat = date("t", $ersterTag);
		$lezterTag = mktime( 23, 59, 59, $monat, $tageImMonat, $jahr );
		
		return $feld .">".$ersterTag." AND ". $feld ."<".$lezterTag;
	}
	
	public function size($size)
	{	$this->size = $size;
	}

	public function merge( $arr )
	{	$this->arr = array_merge_recursive( $this->arr, $arr );
	}
	
	public function fill( $timestamp, $text )
	{	$j = date("Y", $timestamp);
		$m = date("m", $timestamp);
		$t = date("d", $timestamp);
		$this->arr[$j][$m][$t][] = $text;
	}
	
	public function out($arr=FALSE)
	{	echo $this->get($arr);
	}

	public function get($arr=FALSE)
	{	### Merge Kalender Array
		if( $arr != FALSE )
			$this->merge( $arr );
			
			//arrPrint( $this->arr );
	
		### Lade Kalender Template
		$kal = new raidTPL("raid/kalender.htm");
		$tpl = $kal->get(0);
		unset( $kal );
		
		### Aktuelle Kalender anzeige Datum beziehen
		if( isset( $_POST['kalenderJahr'] ) && isset( $_POST['kalenderMonat'] ) )
		{	$monat = nuller($_POST['kalenderMonat']);
			$jahr = nuller($_POST['kalenderJahr']);
		}else
		{	$monat = date("m");
			$jahr = date("Y"); 
		}
		
		### Vohrigen Monat
		if( $monat == 1 )
		{	$prev_monat = 12; 
			$prev_jahr = $jahr - 1; 
		}else{
			$prev_monat = $monat - 1; 
			$prev_jahr = $jahr;
		}
		### Nächsten Monat
		if( $monat == 12 )
		{	$next_monat = 1; 
			$next_jahr = $jahr + 1; 
		}else{
			$next_monat = $monat + 1; 
			$next_jahr = $jahr;
		}
		
		$heute = date("d");
		$uts = mktime(0, 0, 0, $monat, $heute, $jahr);
		
		$monats_namen = array(	"00" => NULL,
								"01" => "(1) Januar",
								"02" => "(2) Februar",
								"03" => "(3) M&auml;rz",
								"04" => "(4) April",
								"05" => "(5) Mai",
								"06" => "(6) Juni",
								"07" => "(7) Juli",
								"08" => "(8) August",
								"09" => "(9) September",
								"10" => "(10) Oktober",
								"11" => "(11) November",
								"12" => "(12) Dezember" );
		
		$url = pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_BASENAME) ."?". $_SERVER['QUERY_STRING'];
		$set['prev'] = kLink("< Zur&uuml;ck", $url, "kalenderMonat=".$prev_monat."&kalenderJahr=".$prev_jahr);
		$set['next'] = kLink("N&auml;chste >", $url, "kalenderMonat=".$next_monat."&kalenderJahr=".$next_jahr);
		$set['monat'] = $monats_namen[$monat];
		$set['jahr'] = $jahr;
		$set['size'] = $this->size;
		
		$tageImMonat = date("t", $uts); # WIEVIELE TAGE DER MONAT HAT
		$replace = $to = array();
		
		$zeile = "0";
		
		for( $t = 1; $t < $tageImMonat+1; $t++ )
		{	$t = nuller( $t );
			
			if( $t.".".$monat == date("d.m") ) $tag = $t;
			else $tag = $t;
				
			$wochentag = date("w", mktime(0,0,0,$monat, $t, $jahr) );

			if( is_array( @$this->arr[$jahr][$monat][$t] ) )
			{	$res = implode("\n", @$this->arr[$jahr][$monat][$t]);
			}else{ $res = @$this->arr[$jahr][$monat][$t]; }
			
			$replace[] = "{".$zeile.":".$wochentag."}";
			$to[] = "<div class=\"kalender_tag\">".$tag."</div><div class=\"kalender_inhalt\">". $res ."</div>";
			if( $wochentag == 6 ) $zeile++;
		}
		
		$tpl = str_replace($replace, $to, $tpl );

		$r = array("/\<td class=\"(.*)\"(.*)\>\<div(.*)\>(".( $monat == date("m") ? $heute : "0" ).")\<\/div\>/");
		$t = array("<td id=\"today\" class=\"Cdark\" valign=\"top\"><div\\3>\\4</div>" );
		
		if( !in_array('{0:0}', $replace) )
		{	$r[] = "/\<\!--REM1--\>(.*)\<\!--REM1--\>/Us";
			$t[] = "";
		}
		
		if( !in_array('{5:1}', $replace) && !in_array('{6:0}', $replace) )
		{	$r[] = "/\<\!--REM2--\>(.*)\<\!--REM2--\>/Us";
			$t[] = "";
		}
		
		$r[] = "/\{[0-9]\:[0-9]\}/Us";
		$t[] = "";
		
		$tpl = preg_replace($r, $t, $tpl );
		
		$_SESSION['KalenderMonat'] = $monat;
		$_SESSION['KalenderJahr'] = $jahr;
		
		$tpl = new raidTPL($tpl, 3);
		$re = $tpl->set_ar_get($set, 0);
		return $re;
	}
}
?>