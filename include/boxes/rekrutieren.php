<?php
defined ('main') or die ( 'no direct access');

//require_once("include/raidplaner/function.php");
rekrutierung();
function rekrutierung()
{	global $raid;
	$img_path = "include/raidplaner/images/klassen/class_";
	$res = db_query("
		SELECT 
			id, klassen, color,
			CONCAT(IF( rs1b>0, '<span class=\'green\'>', '<span class=\'red\'>'), rs1b, 'x ', s1b, '</span>') AS rek1, 
			CONCAT(IF( rs2b>0, '<span class=\'green\'>', '<span class=\'red\'>'), rs2b, 'x ', s2b, '</span>') AS rek2, 
			CONCAT(IF( rs3b>0, '<span class=\'green\'>', '<span class=\'red\'>'), rs3b, 'x ', s3b, '</span>') AS rek3 
		FROM `prefix_raid_klassen` 
		WHERE rs1b>0 OR rs2b>0 OR rs3b>0
		ORDER BY id ASC;
	"); 
	
	if( db_num_rows( $res ) == 0 )
	{
		$raid->status(2, 'noEntrys');
		$raid->setStatus();
	}else{
		while( $row = db_fetch_assoc( $res ) )
		{	$rek = array();
			array_push( $rek, $row['rek1'], $row['rek2'], $row['rek3']);
			$img = "<img align=\"absmiddle\" src=\"" . $img_path . $row['id'] . ".jpg\" />";
			
			echo "
				<div class=\"CharakterKlassenBackground  padding3\" style=\"border-radius: 8px; border-bottom: 3px solid ".$row['color']."; border-top: 1px solid ".$row['color']."; height: auto; line-height: 18px; color: ". $row['color'] ."; margin-bottom: 3px;\">
					<span style=\"font-size: 14px; font-weight: bold; float: left;\">". $img . " " . $row['klassen'] . "</span><br />
					<span style=\"float: right; text-align: right;\" class=\"small padding3px\">".implode(", ", $rek)."</span>
					<br class=\"clear\" />
				</div>
			";
		}
		
		echo "<div align=\"center\"><a href=\"index.php?bewerbung\">Bewerben</a></div>"; 
	}

}

?>
