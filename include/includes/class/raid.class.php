<?php
##
####
######
# #	Raidplaner Klasse 2.0
# #	Balthazar3k.funpic.de
# #	17.10.2012, 2013
######
####
## 	

class raidplaner {
	public function __construct(){
		arrPrint(__METHOD__, $_POST, $_SESSION);

		$this->updateRaidplaner();			# Wenn es nach einem Update SQL änderungen gab, installiert er sie automatisch
		$this->removeUser();				# wenn ein ilch USER gelöscht wird löscht er die Charakter daten mit
		$this->sessionCharakter(); 			# Setzt Main Charakter in die Session
		$this->confirmedCharakter();		# Bewerbungs Formular, Regestrierungs bestätigungs link abwarten bis charakter erstellt wird
		$this->checkRaidStatus();			# Überprüft ob Raid's ausgelaufen sind
	}

	##
	####
	######
	# USER METHODEN
	######
	####
	##
	
	### Hängt sich an den auslöser von ilch USER Löschen
	private function removeUser(){
		if( isset( $_POST['action'] ) && $_POST['action'] == 'deleteUser' )
		{	$uid = escape($_POST[ 'uid' ], 'integer');
		
			$res = db_query("SELECT id, user, avatar, img FROM prefix_raid_charaktere WHERE user='". $uid ."'");
			if( db_num_rows( $res ) > 0 )
			{
				while( $row = db_fetch_assoc( $res ) )
				{	$this->removeCharakter($row['id']);
				}
			}
		}
	}
	
	##
	####
	######
	# RAID METHODEN
	######
	####
	##
	public function removeModuleRights($uid){
		// Vorhandene Module Rechte Löschen
		$resStatus = array();
		
		$res = simpleArrayFromQuery("SELECT id FROM prefix_modules WHERE menu='Raidplaner'");
		$resStatus[] = db_query("DELETE FROM prefix_modulerights WHERE uid='".$uid."' AND mid IN(".implode(", ", $res ).");");
		
		$res = simpleArrayFromQuery("SELECT id FROM prefix_raid_rechte");
		$resStatus[] = db_query("DELETE FROM prefix_raid_userrechte WHERE uid='".$uid."' AND mid IN(".implode(", ", $res ).");");
		
		$this->status(( in_array( 0, $resStatus) ? FALSE : TRUE ), 'removeModuleRights', 5);
	}
	
	public function setModuleRights($uid, $rang){
		$resStatus = array();
		
		// Neue Modulrechte erstellen
		$aTable = array(
				'Permissions' => 'prefix_raid_userrechte',
				'Raidplaner' => 'prefix_modulerights'
		);
		
		$res = db_query("SELECT rechte FROM prefix_raid_rang WHERE id='".$rang."' LIMIT 1;");
		$row = db_fetch_assoc( $res );
		$rechte = json_decode($row['rechte'], true);
		
		if( is_array( $rechte ) ){
			foreach( $rechte as $table => $array ){			
				foreach( $array as $v ){			
					$resStatus[] = $this->insert($aTable[$table], array('uid' => $uid, 'mid' => $v), 'uid:i', 'mid:i');
				}
			}
			
			arrPrint(__FUNCTION__, $resStatus );
			$lastStatus = ( in_array( 0, $resStatus) ? FALSE : TRUE );
			$this->status($lastStatus, 'setModuleRights', 5);
			return $lastStatus;
		}
	}
	
	public function changeModuleRights($uid, $rang){
		$this->removeModuleRights($uid);
		$this->setModuleRights($uid, $rang);
	}
	
	public function updateModuleRights(){
		$stat = array();
		$charaktere = $this->mainCharaktere();
		foreach( $charaktere['user'] as $i => $uid )
			$stat[] = $this->changeModuleRights($uid, $charaktere['rank'][$i]);
		
		return ( in_array( 0, $stat) ? FALSE : TRUE );
	}
	
	private function checkRaidStatus(){
		if( permission('editRaid') ){
			### Raids auf Gültigkeit überprüfen
			$res = db_query("SELECT id, ende FROM prefix_raid_raid WHERE statusmsg=1 AND ende<=".(time()-7200) );
			while( $row = db_fetch_assoc( $res )){
				@db_query("UPDATE prefix_raid_raid SET statusmsg=17 WHERE id=".$row['id'] );
			}
		
			### Wenn's ausstehende Raids gibt wird man Informiert.
			$res = db_query("SELECT id, inv FROM prefix_raid_raid WHERE statusmsg=17");
			while( $row = db_fetch_assoc( $res )){
				$this->status(2, "Ausstehender Raid vom: <a href='admin.php?raid-edit-".$row['id']."' fancybox='inline'>". DateFormat("D d.m.Y H:i", $row['inv']) ."</a>", 10,__METHOD__);
			}
		}
	}
	
	public function updateRangId(){
		## Damit ränge immer durchgehend durch nummerriert sind und nicht 1, 2, 5 ergibt
		$array = simpleArrayFromQuery("SELECT id as oid FROM prefix_raid_rang ORDER BY id ASC");
		foreach( $array as $id => $oid ){
			@db_query("UPDATE prefix_raid_rang SET id='".$id."' WHERE id='".$oid."' LIMIT 1");
		}
		
		## ilchConfig Update
		$extra = getArray('SELECT id AS `keys`, name AS `values` FROM prefix_raid_rang ORDER BY id ASC');
		ilch_updateConfig('bewerberrang', count( $extra['keys'] )-1, json_encode($extra) );
	}
	
	##
	####
	######
	# LOG METHODEN
	######
	####
	##
	var $logFileName;
	
	public function log(){
		$this->logFileName = 'log/' . date('Y-m-d');
		// Überprüfen ob es den cache/log Ordner gibt
		if( !is_dir( $this->cacheDir . "/log" ) ){
			mkdir( $this->cacheDir . "/log" );
			@chmod( $this->cacheDir . "/log", 0777);
		}
		
		$logTime = time();
		$args = func_get_args();
		$log = $this->getCache($this->logFileName);
		
		foreach( $args as $k => $arg ){

			if( is_array( $arg ) ){
				ob_start();
				
					echo "<pre>";
					var_dump( $arg );
					echo "</pre>";
					
				$arg = ob_get_contents();
				ob_end_clean();
			}
			
			
			$log = "[".date('d.m.Y H:i:s', $logTime). "|" . $_SESSION['authname'] ."]: ". $arg ."\n" . $log;
		}
		
		$this->setCache($this->logFileName, $log);
	}
	
	public function logToday(){
		$this->logFileName = 'log/' . date('Y-m-d');
		return $this->getCache($this->logFileName, true);
	}
	
	
	
	##
	####
	######
	# CHARAKTER METHODEN
	######
	####
	##
	public function mainCharakter($user = false){
		return getRow("
			SELECT 
				a.id, a.user, a.name, a.rank, a.klassen,
				b.rechte,
				(SELECT COUNT(zid) as time FROM prefix_raid_charzeiten WHERE cid=a.id) as time
			FROM prefix_raid_charaktere AS a
				LEFT JOIN prefix_raid_rang AS b ON a.rank=b.id
			WHERE a.user = '".( $user ? $user : $_SESSION['authid'] )."'
			GROUP BY a.user 
			ORDER BY a.rank ASC
			LIMIT 1
		");
	}
	
	public function mainCharaktere(){
		return getArray("
			SELECT 
				a.id, a.user, a.name, a.rank,
				b.rechte
			FROM prefix_raid_charaktere AS a
				LEFT JOIN prefix_raid_rang AS b ON a.rank=b.id
			WHERE user != 0 
			GROUP BY a.user 
			ORDER BY a.rank ASC
		");
	}
	
	public function createCharakter($arr=false ,$user=false  ){
		if( !is_array( $arr ) ){
			$arr = $_POST;
		}
		
		if( $user ){
			$arr['user'] = $user;
		}
		
		arrPrint(__FUNCTION__, $arr);
		
		$this->insert(
			'prefix_raid_charaktere', 
			$arr,
			'user:i', 
			'name:s', 
			'level:i', 
			'rassen:i', 
			'klassen:i', 
			's1:s', 
			's2:s', 
			'points:i', 
			'avatar:s', 
			'img:s', 
			'realm:s',
			'warum:textarea',
			'time:strip',
			'recht:strip',
			'gebdatum:strip'
		);
		
		return ( $res ? true : false );
	}
	
	public function removeCharakter( $id, $exit = NULL ){
		// Lösche alle Charakter informationen/einträge
		$row = db_fetch_assoc( db_query("SELECT id, name, user, avatar, img FROM prefix_raid_charaktere WHERE id='". $id ."'"));
		$nr[] = db_query("DELETE FROM prefix_raid_charaktere WHERE id = '".$row['id']."'");
		$nr[] = db_query("DELETE FROM prefix_raid_charzeiten WHERE cid = ".$row['id']."");
		$nr[] = db_query("DELETE FROM prefix_raid_anmeldung WHERE `char` = ".$row['id']."");
		@unlink( $row['avatar'] );
		@unlink( $row['img'] );
		
		// Setze aus Sicherheits gründen die Modulrechte neu
		// Sofern der Charakter einen User hat
		// Macht somit den nächst Höchsten Charakter zum Main
		// $_POST['action'] damit er nicht rechte ändert wenn ein User gelöscht wird
		if( $row['user'] != 0 && !isset($_POST['action']) && $_POST['action'] != 'deleteUser' ){ 
			$mainCharakter = $this->mainCharakter($row['user']);
			$this->changeModuleRights($row['user'], $mainCharakter['rank']);
		}
		
		$this->status(!in_array(0, $nr), 'removeCharakter', 3, $row);
	}
	
	private function confirmedCharakter(){
		global $allgAr;
		### Bewerbung wo confirmed Charaktere gecachet wird
		if( isset( $_SESSION['authname'] ) && !isset( $_SESSION['charname'] ) ){
			$cache = "include/cache/". md5( $_SESSION['authname'] ) .".raid";
			if( file_exists($cache) ){
				if( $data = file_get_contents( $cache ) ){
					$data = unserialize( $data );
					$this->createCharakter( $data, $_SESSION['authid'] );
					$this->setCharakterTime( $data['name'], $data );
					db_query("UPDATE prefix_user SET gebdatum='".$data['gebdatum']."', recht='".$allgAr['bewerberrang']."' WHERE id=".$_SESSION['authid']." LIMIT 1;");
					unset( $data );
					@unlink( $cache );
				}
			}
		}
	}
	
	public function sessionCharakter(){
		if( $_SESSION['authid'] > 0 && (!isset($_SESSION['charname']) || $_SESSION['charname'] <= 0 )){
			$mainCharakter = $this->mainCharakter();
		}
		
		if( count($mainCharakter) > 0 ){
			$_SESSION['charid'] = $mainCharakter['id'];
			$_SESSION['charname'] = $mainCharakter['name'];
			$_SESSION['charrank'] = $mainCharakter['rank'];
			$_SESSION['charklasse'] = $mainCharakter['klassen'];
			$_SESSION['charzeiten'] = $mainCharakter['time'];
			$this->sessionAuthmod($_SESSION['authid']);
		}else{
			$_SESSION['charzeiten'] = $_SESSION['charname'] = $_SESSION['charid'] = $_SESSION['charklasse'] = 0;
		}
	}
	
	private function sessionAuthmod( $uid ){
		## Zusätzliche rechte in die Session aufnehmen
		$res = db_query("
			SELECT
				b.url
			FROM prefix_raid_userrechte AS a
				LEFT JOIN prefix_raid_rechte AS b ON a.mid=b.id
			WHERE
				a.uid = '".$uid."'
		");
		
		while( $row = db_fetch_assoc( $res ) ){
			$_SESSION['authmod'][$row['url']] = true;
		}
	}
	
	public function getSpz($kid){
		$newArray = array();
		$res = db_fetch_assoc(db_query("SELECT s1b, s2b, s3b FROM prefix_raid_klassen WHERE id=".$kid." LIMIT 1;"));
		foreach( $res as $val ){
			$newArray[$val] = $val;
		}
		
		return $newArray;
	}
	
	##
	####
	######
	# ZEIT METHODEN
	######
	####
	##
	
	public function setCharakterTime( $cid, $arr=false ){
		
		if( !is_array( $arr ) ){
			$arr = $_POST;
		}
		
		if( !isset( $arr['time'] ) ){
			return (FALSE);
		}
		
		if( is_string( $cid ) ){
			$cid = db_result(db_query("SELECT id FROM prefix_raid_charaktere WHERE name='".$cid."' LIMIT 1;"), 0);
		}
			
		$newTime = array();
		foreach( $arr['time'] as $value )
		{
			$newTime[] = "('".$cid."', '".$value."')";
		}
			
		$newTime = implode(", ", $newTime);
		return ( db_query("INSERT INTO prefix_raid_charzeiten (`cid`, `zid`) VALUES".$newTime.";") ? true : false );
	}
	
	##
	####
	######
	# STATUS METHODEN
	######
	####
	## 
	
	var $status = array();
	var $lastStatus = array();
	var $fromStatus;
	var $lastStatusKey;
	var $maxTimeout;
	
	public function status( $status, $message = NULL, $timeout = 0, $script = NULL){
		global $raidMsg;
		
		# Fehler selber Definieren
		if( $message == NULL )
			$message = ( $status ? 'success' : 'mistake' );
		
		# Wenn es in der "include/raidplaner/language/message.php" definiert ist Text ersetzen
		if( array_key_exists( $message, $raidMsg ) )
			$message = $raidMsg[$message];
		
		# Wenn Message ein Array ist aus dem Status Filten
		if( is_array( $message ) )
			$message = $message[$status];
		
		# Ersetze MessageCode {(.*)} wenn vorhanden!
		if( preg_match_all("/\{([A-Za-z0-9\_\-]*)\}/", $message, $res ) ){
			
			$replace = $to = array();
			
			foreach( $res[0] as $i => $rep ){
				$replace[$i] = $rep;
				$key = $res[1][$i];
				
				if( is_array( $script ) && isset( $script[$key] )  ){
					$to[$i] = $script[$key];
				}elseif( isset( $_POST[$key] )){
					$to[$i] =  $_POST[$key];
				}elseif( isset( $_GET[$key] )){
					$to[$i] =  $_GET[$key];
				}else{
					$to[$i] = '<b>[Keine Replace information!]</b>';
				}
			}
			
			$message = str_replace($replace, $to, $message);
		
		}
	
		# Timeout berecehnen in MilliSekunden
		if( $timeout != 0 && $timeout != 'exit')
			$timeout = $timeout*1000;
		
		# Maximalen Timout Speichern
		if( is_numeric( $timeout) && $timeout > $this->maxTimeout )
			$this->maxTimeout = $timeout;
		
		# Wenn Status Leer ist dann setze ihn auf 0
		$status = ( $status == NULL ? 0 : $status );
		
		## Teste log
		$this->log( $message . " \"" . $_SERVER['REQUEST_URI'] ."\"");
		
		$i = count( @$this->status[$status]['status'] );
		
		$this->status[$status]['status'][$i] = $status;
		$this->status[$status]['message'][$i] = $message; 
		$this->status[$status]['timeout'][$i] = $timeout;
		$this->status[$status]['script'][$i] = $script;
		
		$this->lastStatus = $status;
		$this->lastStatusKey = $i;
		
		# Wenn exit aufgerufen gibt er die fehler aus und stopt das script
		if( is_string( $timeout ) && $timeout == 'exit' ){
			$this->setStatus(true);
		}
	}
	
	public function getStatus(){
		//arrPrint(__METHOD__, $this->status, $_POST, $_SESSION, $_SERVER );
		
		require_once('include/includes/class/iSmarty.php');
		$smarty = new iSmarty();
		$smarty->assign('maxTimeout', $this->maxTimeout);
		$smarty->assign('status', $this->status);
		return $smarty->fetch('file:include/templates/raid/status.tpl');
	}
	
	public function getLastStatus(){
	
		$status[$this->lastStatus]['status'][$this->lastStatusKey] = $this->status[$this->lastStatus]['status'][$this->lastStatusKey];
		$status[$this->lastStatus]['message'][$this->lastStatusKey] = $this->status[$this->lastStatus]['message'][$this->lastStatusKey]; 
		$status[$this->lastStatus]['timeout'][$this->lastStatusKey] = $this->status[$this->lastStatus]['timeout'][$this->lastStatusKey];
		$status[$this->lastStatus]['script'][$this->lastStatusKey] = $this->status[$this->lastStatus]['script'][$this->lastStatusKey];
		
		unset(
			$this->status[$this->lastStatus]['status'][$this->lastStatusKey],
			$this->status[$this->lastStatus]['message'][$this->lastStatusKey],
			$this->status[$this->lastStatus]['timeout'][$this->lastStatusKey],
			$this->status[$this->lastStatus]['script'][$this->lastStatusKey]
		);
		
		arrPrint(__METHOD__, $this->lastStatus, $status);
		require_once('include/includes/class/iSmarty.php');
		$smarty = new iSmarty();
		$smarty->assign('status', $status);
		return $smarty->fetch('file:include/templates/raid/status.tpl');
	}
	
	public function setLastStatus($exit=false){
		$status = $this->getLastStatus($exit);
		if( $exit ){
			exit($status);
		}else{
			echo $status;
		}
	}
	
	public function setStatus( $exit = false){
		$status = $this->getStatus();
		if( $exit ){
			exit($status);
		}else{
			echo $status;
		}
	}
	
	public function statusJSON(){
		return json_encode( $this->status );
	}


	##
	####
	######
	# RAIDPLANER METHODEN
	######
	####
	## 	
	
	
	private function updateRaidplaner(){
		if( is_admin() ){
			if( file_exists( "include/raidplaner/sql/install.sql" ) ){
				$sql = file_get_contents( "include/raidplaner/sql/install.sql" );
				if( db_query($sql) ){
					if( @unlink("include/raidplaner/sql/install.sql") ){
						$this->status(true, "Datenbank änderung vorgenommen.");
					}else{
						$this->status(false, "Wichtig! Bitte L&ouml;schen Sie die \"include/raidplaner/sql/install.sql\" sie konnte nicht vom System entfernt werden!");
					}
				}
			}
		}
	}
	
	public function uploadFile($key, $newname, $path, &$uploadStatus=NULL ){
		if( !isset( $_FILES[$key] ) || $_FILES[$key]['error'] > 0 ){ return false; }
		if( $_FILES[$key]['size'] == 0 ){ return false; }
		
		$ext = ".".pathinfo($_FILES[$key]['name'], PATHINFO_EXTENSION);
		$newname = urlencode($newname) . $ext;
		
		if( move_uploaded_file($_FILES[$key]['tmp_name'], $path . $newname) ){
			
			$uploadStatus = array('status' => true,
									'name' => $newname, 
									'path' => $path . $newname, 
									'size' => $_FILES[$key]['size']
			);
			
			$this->status(true, 'Bild "'.$_FILES[$key]['name'].'" wurde erfolgreich Hochgeladen!', 3);
		}
	
	}
	
	##
	####
	######
	# DATENBANK METHODEN
	######
	####
	## 
	
	var $escapes = array(
		"string" => "string", 
		"integer" => "integer", 
		"textarea" => "textarea", 
		"s" => "string", 
		"i" => "integer", 
		"t" => "textarea"
	);
	
	### db_insert( MySQL Table, Array||string([ArrayKey:Escape]) [,ArrayKey:Escape][,ArrayKey:Escape][,ArrayKey:Escape]*, Array||string([ArrayKey:Escape]) );
	### arg0 = MySQL Tabellen name!
	### arg1 = array(); oder [,ArrayKey:Escape]
	### Letzte Argument kann wieder ein array, geht dann array_merge
	### Escapes: integer, string, textarea
	public function insert(){
		$arg = func_get_args();
		$table = $arg[0];
		$mergeArgKey = 0; #um später das letzt argument zu finden
		unset($arg[0]);
		
		$arr = $key = $val = array();
		
		## Wenn Argument 2(key1) ein Array ist, dann wird es als quelle benuzt ansonsten $_POST
		if( is_array( $arg[1] ) ){
			$arr = $arg[1];
			unset( $arg[1] );
			$mergeArgKey++; # Um 1 erhöhen da noch ein Argument mit "Unset" entfernt wurde
		}else{
			$arr = $_POST;
		}
		
		## Wenn das Letzte Argument ein Array ist dann mergen
		if( is_array( $arg[count($arg)+$mergeArgKey] ) ){
			$arr = array_merge( $arr, $arg[count($arg)+$mergeArgKey]);
			unset( $arg[count($arg)+$mergeArgKey] );
			$debug = $arr;
		}
		
		foreach( $arg as $k => $v ){
			$option = explode(":", $v);
			
			$ak = $option[0];
			$at = $option[1];
			
			if( isset( $option[2] ) ){
				@$arr[$at] = @$arr[$ak];
				$at = $option[2];
				unset( $arr[$ak] );
				$ak = $option[1];
			}
			
			## Array Element entfernen
			if( $at == 'strip' ){
				unset($arr[$ak]);
				continue;	## Ab hier können wir abbrechen und das nächste element im Array bearbeiten
			}
			
			$arr[$ak] = escape($arr[$ak], $this->escapes[$at]);
		}
		
		foreach( $arr as $s => $w ){
			$key[] = '`'.$s.'`';
			$val[] = "'". $w ."'";
		}
		
		$sql = "INSERT INTO `". $table ."` (". implode(", ", $key) .") VALUES(". implode(", ", $val) .");";
		return db_query($sql);
	}

	### db_update( MySQL Table, Array||string([ArrayKey:Escape]) [,ArrayKey:Escape][,ArrayKey:Escape][,ArrayKey:Escape]*, Array||string([ArrayKey:Escape]) );
	### Where Schlüssel definieren [,[id|where]->ArrayKey:Escape]
	### arg0 = MySQL Tabellen name!
	### arg1 = array(); oder [,ArrayKey:Escape]
	### Letzte Argument kann wieder ein Array sein, geht dann array_merge
	### Escapes: integer, string, textarea
	public function update(){
		$arg = func_get_args();
		$table = $arg[0];
		$mergeArgKey = 0; #um später das letzt argument zu finden
		unset($arg[0]);
		
		$arr = $key = $val = array();
		
		## Wenn Argument 2(key1) ein Array ist, dann wird es als quelle benuzt ansonsten $_POST
		if( is_array( $arg[1] ) ){
			$arr = $arg[1];
			unset( $arg[1] );
			$mergeArgKey++; # Um 1 erhöhen da noch ein Argument mit "Unset" entfernt wurde
		}else{
			$arr = $_POST;
		}
		
		## Wenn das Letzte Argument ein Array ist dann mergen
		if( is_array( $arg[count($arg)+$mergeArgKey] ) ){
			$arr = array_merge( $arr, $arg[count($arg)+$mergeArgKey]);
			unset( $arg[count($arg)+$mergeArgKey] );
		}
		
		foreach( $arg as $k => $v ){
			
			if( preg_match("/id\-\>(.*)\:(.*)/", $v, $a) ){
				$wKey = $a[1];
				$wValue = escape($arr[$a[1]], $this->escapes[$a[2]]);
				unset( $arr[$a[1]], $a );
				continue;
			}
			
			$option = explode(":", $v);
			
			$ak = $option[0];
			$at = $option[1];
			
			if( isset( $option[2] ) ){
				$arr[$at] = $arr[$ak];
				$at = $option[2];
				unset( $arr[$ak] );
				$ak = $option[1];
			}
			
			if( !isset( $arr[$ak] ) )
				continue;
			
			## Array Element entfernen
			if( $at == 'strip' ){
				unset($arr[$ak]);
				continue;	## Ab hier können wir abbrechen und das nächste element im Array bearbeiten
			}
			
			$arr[$ak] = escape($arr[$ak], $this->escapes[$at]);
		}
		
		foreach( $arr as $s => $w ){
			$val[] = "`".$s."`='". $w ."'";
		}
		
		$sql = "UPDATE `". $table ."` SET ". implode(", ", $val) ." WHERE `".$wKey."`='".$wValue."';";
		return db_query($sql);
	}

	public function delete( $table, $id ){
		 if( preg_match("/(.*)\-\>(.*)\:(.*)/", $id, $a) ){
			$this->status(db_query("DELETE FROM ". $table ." WHERE `".$a[1]."`='".escape($_POST[$a[2]], $this->escapes[$a[3]])."';"));
		}else{
			$this->status(db_query("DELETE FROM ". $table ." WHERE id='".$id."';"));
		}
	}
	
	##
	####
	######
	# CACHE METHODEN
	######
	####
	## 
	
	var $cacheDir = 'include/cache/raid/';
	var $cacheExt = '.raidCache';
	var $autoPush = true;
	
	public function setCache($name, $cache){
		$cacheName = $this->cacheDir . $name . $this->cacheExt;
		
		// Überprüfen ob es den cache Ordner gibt
		if( !is_dir( $this->cacheDir ) ){
			mkdir( $this->cacheDir );
			@chmod( $this->cacheDir, 0777);
		}
		
		
		// Wenn es CacheFile schon gibt Push
		if( file_exists( $cacheName ) && $this->autoPush ){
			return $this->pushCache( $name, $cache );
		}else{
			// Wenn es ein Array ist, zu JSON umwandeln
			if( is_array($cache) )
				$cache = json_encode( $cache, true);
			
			return @file_put_contents( $cacheName, $cache);
		}
	}
	
	public function getCache($name, $remove = false){
		$cacheName = $this->cacheDir . $name . $this->cacheExt;
		if( $cache = @file_get_contents( $cacheName ) ){
			// Ist Cache ein Array?
			$cacheArray = json_decode( $cache, true);
			if( is_array( $cacheArray ) )
				$cache = $cacheArray;
				
			if( !$remove )
				@unlink( $cacheName );
				
			return $cache;
		}else{
			return false;
		}
	}
	
	public function pushCache( $name, $cache ){
		//$cacheName = $this->cacheDir . $name . $this->cacheExt;
		if( $res = $this->getCache( $name ) ){
			
			if( is_array( $res ) ){
				$cache = array_merge_recursive( $res, $cache );
			}else{
				$cache = $res . $cache;
			}
			
			return $this->setCache( $name, $cache );
		}
	}
	
	public function getCacheFiles(){
		if( $res = scandir( $this->cacheDir ) ){
			$res = array_slice( $res, 2);
			array_walk( $res, function(&$item, $key, $dir){
				$item = $dir.$item;
			}, $this->cacheDir);
			
			return $res;
		}else{
			return false;
		}
	}
	
	public function clearCache(){
		if( $files = $this->getCacheFiles() ){
			array_walk( $files, function( &$item ){
				@unlink( $item );
			});
		}
	}
	
}
?>