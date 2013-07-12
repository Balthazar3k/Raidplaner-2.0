<?php
class kalender {
	var $startDate = '';
	var $endDate = '';
	var $getArray = array();
	
	var $dayNow;
	var $monthNow;
	var $yearNow;
	
	var $smartyEngine = "include/includes/libs/smarty/Smarty.class.php";
	var $smartyTemplateDir = "include/templates/raid/";
	
	var $kalenderTemplate = "class.kalender.tpl";
	
	# Attribute für die Kalendertage
	var $today = array(
		'class' => 'Cdark'
	);
	
	var $thisMonth = array(
		'class' => 'Cnorm'
	);
	
	var $otherMonth = array(
		'class' => 'Cmite'
	);
	
	public function __construct($date=false, $merge=false){
		# Starte den Monats Array
		$this->getDateArray($date);
		
		# Füge daten in den Array Hinzu
		if( is_array( $merge ) ){
			$this->getArray = array_merge_recursive($this->getArray, $merge);
		}
		
	}
	
	public function fill( $timestamp, $text, $attributes=null ){	
		$y = intval(date("Y", $timestamp));
		$m = intval(date("m", $timestamp));
		$d = intval(date("d", $timestamp));
		
		if( is_array( $attributes ) ){
			$attr = array();
			foreach( $attributes as $a => $v ):
				$attr[] = $a ."=\"".$v."\"";
			endforeach;
			$attributes = implode(" ", $attr);
		}
		
		$this->getArray[$y][$m][$d][] = "<div class=\"event\" ".$attributes.">".$text."</div>";
	}
	
	
	public function getDate($date=false){
		if( isset( $_REQUEST['day'] ) && isset( $_REQUEST['month'] ) && isset( $_REQUEST['year'] ) ){
			return mktime( 0, 0, 0, $_REQUEST['month'], $_REQUEST['day'], $_REQUEST['year'] );
		}elseif( isset( $_REQUEST['date'] ) && is_string( $_REQUEST['date'] ) ){
			return strtotime( $_REQUEST['date'] );
		}elseif( $date && is_string( $date ) ){
			return strtotime( $date );
		}else{
			return time();
		}
	}
	
	private function getDateArray($date=false){
		# Hier wird eine Array erstellt
		# Das einem Monats Plat eines Kalenders gleich kommt
		$time = $this->getDate();
		
		$maxDays = 42;											# Ein Kalenderblatt hat insgesamt 42 Tage für eine Optimale darstellung
		$countDays = 0;											# Zähler um den nächsten Monat zu berechnen
		$this->dayNow = $dayNow   = intval(date("d", $time)); 	# Der Heutige Tag
		$this->monthNow = $monthNow = intval(date("m", $time));	# Der aktuelle Monat
		$this->yearNow = $yearNow  = intval(date("Y", $time));	# Das aktuelle Jahr
		$aDaysNow = date("t", $time); 							# Anzahl der Tage in diesem Monat
		
		# Berechnung der letzten Tage der vorigen Monats auf dem aktuellen Monatsplatt
		$aDaysLast = 		date("t", mktime(0,0,0,$monthNow-1,1,$yearNow)); 				# Anzahl der Tage im vorigem Monate
		$aWeekDaysLast = 	date("w", mktime(0,0,0, $monthNow-1, $aDaysLast, $yearNow));	# Ermittel den Letzten wochentag
		$aWeekDaysLast = 	( $aWeekDaysLast == 0 ? 6 : $aWeekDaysLast-1);					# Ermittelt wiviele Tage vor dem Letzten Monat liegt für ein Kalender Monatsblatt
		
		# Schleife für den Monat davor
		$firstDayLastMonth = ($aDaysLast-$aWeekDaysLast);
		$this->startDate = mktime( 0, 0, 0, $monthNow-1, $firstDayLastMonth, $yearNow);
		
		for( $i=$firstDayLastMonth; $i<$aDaysLast+1; $i++){
			$countDays++;
			$this->getArray[$yearNow][$monthNow-1][$i] = array("attributes" => $this->otherMonth);
			$this->getArray[$yearNow][$monthNow-1][$i][] = "<span class='dayTitle'>".$i."</span>";
		}
		
		# Schleife für den aktuellen Monat
		for( $i=1; $i<$aDaysNow+1; $i++){
			$countDays++;
			if( ($dayNow.".".$monthNow) == ($i.".".intval(date('m'))) ){
				$this->getArray[$yearNow][$monthNow][$i] = array("attributes" => $this->today);
				$this->getArray[$yearNow][$monthNow][$i][] = "<span class='dayTitle'>".$i." <span class='small'>today</span></span>";
			}else{
				$this->getArray[$yearNow][$monthNow][$i] = array("attributes" => $this->thisMonth);
				$this->getArray[$yearNow][$monthNow][$i][] = "<span class='dayTitle'>".$i."</span>";
			}
		}
		
		# Schleife für den letzten Monat
		$lastDayNextMonth = ($maxDays-$countDays)+1;
		
		$this->endDate = mktime( 0, 0, 0, $monthNow+1, $lastDayNextMonth, $yearNow);
		
		for( $i=1; $i<$lastDayNextMonth; $i++){
			$this->getArray[$yearNow][$monthNow+1][$i] = array("attributes" =>  $this->otherMonth);
			$this->getArray[$yearNow][$monthNow+1][$i][] = "<span class='dayTitle'>".$i."</span>";
		}
		
		return $this->getArray;
	}
	
	public function where($feld, $format="U"){
		$startDate = date( $format, $this->startDate);
		$endDate = date( $format, $this->endDate);
		return $feld .">'".$startDate."' AND ". $feld ."<'".$endDate."'"; 
	}
	
	public static function monthName(){
		$monat = array(	
			1 => "Januar",
			2 => "Februar",
			3 => "M&auml;rz",
			4 => "April",
			5 => "Mai",
			6 => "Juni",
			7 => "Juli",
			8 => "August",
			9 => "September",
			10 => "Oktober",
			11 => "November",
			12 => "Dezember"
		);
		
		return $monat;
	}
	
	public static function weekDays(){
		$weekDays = array(	
			1 => "Montag",
			2 => "Dienstag",
			3 => "Mittwoch",
			4 => "Donnerstag",
			5 => "Freitag",
			6 => "Samstag",
			0 => "Sonntag"
		);
		
		return $weekDays;
	}
	
	public static function years(){
		return db_sameKeyVal("
			SELECT DISTINCT 
				DATE_FORMAT(FROM_UNIXTIME(inv), '%Y') AS year
			FROM prefix_raid_raid
			ORDER BY year ASC
		");
	}
	
	public function set($size=980){
		$rowSize = $size/7;
		$ceilCounter = 0;
		$month = $this->monthName();
		
		if( $this->monthNow == 1 ){
			$lastMonth = 12;
			$lastYear = $this->yearNow-1;
		}else{
			$lastMonth = $this->monthNow-1;
			$lastYear = $this->yearNow;
		}
		
		if( $this->monthNow == 12 ){
			$nextMonth = 1;
			$nextYear = $this->yearNow+1;
		}else{
			$nextMonth = $this->monthNow+1;
			$nextYear = $this->yearNow;
		}
		
		?>
		<table id="kalender" cellpadding="2" cellspacing="1" border="0" class="border"><?php
		?>	<tr>
				<td class="Chead" align="center" width="<?php echo $rowSize; ?>" colspan="7">
					<div class="buttonset kalender">
						<a fancybox="kalender" style="width: 125px;" href="index.php?raidlist-kalender&date=<?php echo $this->dayNow.".".$lastMonth.".".$lastYear;?>"><?php echo $month[$lastMonth]; ?></a>
						<a class="ui-state-active" href="#" slide=".kalenderNavigation" style="width: 200px;"><?php echo $month[intval($this->monthNow)]." ".$this->yearNow; ?></a>
						<a fancybox="kalender" style="width: 125px;" href="index.php?raidlist-kalender&date=<?php echo $this->dayNow.".".$nextMonth.".".$nextYear;?>"><?php echo $month[$nextMonth]; ?></a>
					</div>
				</td>
			</tr>
			
			<tr class="">
				<td class="Cdark" align="center" width="<?php echo $rowSize; ?>" colspan="7">
				
					<div class="buttonset kalenderNavigation hide" style="margin-bottom: 3px;">
						<?php foreach( $month as $key => $value ):
						?><a 
							href="index.php?raidlist-kalender&date=<?php echo $this->dayNow; ?>.<?php echo $key; ?>.<?php echo $this->yearNow; ?>" 
							class="<?php echo ( $this->monthNow == $key ? 'ui-state-active' : 'ui-state-default'); ?>"
							fancybox="kalender">
								<?php echo $value; ?>
						</a><?php
						endforeach;?>
					</div>
					
					<div class="buttonset kalenderNavigation hide">
						<?php foreach( $this->years() as $key => $value ):
						?><a 
							href="index.php?raidlist-kalender&date=<?php echo $this->dayNow; ?>.<?php echo $this->monthNow; ?>.<?php echo $key; ?>" 
							class="<?php echo ( $this->yearNow == $key ? 'ui-state-active' : 'ui-state-default'); ?>"
							fancybox="kalender">
								<?php echo $value; ?>
						</a><?php
						endforeach;?>
					</div>
				</td>
			</tr>
			
			<tr><?php
		foreach( $this->weekDays() as $key => $value): ?>
			<td class="Chead" align="center" width="<?php echo $rowSize; ?>"><?php echo $value; ?></td>
		<?php endforeach;
		?>	</tr>
			<?php
		foreach( $this->getArray as $year => $yearArray):
			foreach( $yearArray as $month => $daysArray):
				foreach( $daysArray as $day => $dayArray):
					$ceilCounter++; 
					if(($ceilCounter-1) % 7 == 0 ){ echo "<tr>"; }
					?>
						
					<td valign="top" <?php foreach( $dayArray['attributes'] as $attr => $val): echo $attr ."=\"". $val . "\""; endforeach; ?> width="<?php echo $rowSize; ?>" height="<?php echo $rowSize; ?>"><?php
					foreach( $dayArray as $i => $event ):
						if( !is_array( $event ) ){
							 echo $event; 
						}
					endforeach;
					echo "</td>";
					if($ceilCounter % 7 == 0 ){ echo "</tr>"; }
				endforeach;
			endforeach;
		endforeach;
		?><?php
		?></table>
		<?php
	}
}
?>