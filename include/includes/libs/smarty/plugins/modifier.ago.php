<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     modifier.ago.php
 * Type:     modifier
 * Name:     agoTimeMsg
 * Purpose:  Ändert datum in ein ein text string um
 * -------------------------------------------------------------
 */
function smarty_modifier_ago($string){
	## Wenn String kein Teimstamp ist dann zu einem Machen
	if( preg_match("/(\-)/", $string) )
		$string = strtotime($string);
	
    $TIME_AGO_sec = round( time() - $string );
	$TIME_AGO_min = round( $TIME_AGO_sec / 60);
	$TIME_AGO_hrs = round( $TIME_AGO_min / 60);	
	$TIME_AGO_day = round( $TIME_AGO_hrs / 24);
	$TIME_AGO_wek = round( $TIME_AGO_day / 7);
	$TIME_AGO_yea = round( $TIME_AGO_day / 365);
	$TIME_AGO_mon = round( $TIME_AGO_day / 30.42, 0); # 30,42 Tage Durschschnit für ein Monat im Jahr
	
	if($TIME_AGO_sec > ( 86400 * 365 )) 	return 'vor '. $TIME_AGO_yea .' '. ( $TIME_AGO_yea > 1 ? "Jahren" 	: "Jahr"	);
	elseif($TIME_AGO_day > 30) 				return 'vor '. $TIME_AGO_mon .' '. ( $TIME_AGO_mon > 1 ? "Monaten" 	: "Monat"	);
	elseif($TIME_AGO_sec > ( 86400 * 7 )) 	return 'vor '. $TIME_AGO_wek .' '. ( $TIME_AGO_wek > 1 ? "Wochen" 	: "Woche"	);
	elseif ($TIME_AGO_sec > 86400) 			return 'vor '. $TIME_AGO_day .' '. ( $TIME_AGO_day > 1 ? "Tagen" 	: "Tag"		);
	elseif ($TIME_AGO_sec > 3600) 			return 'vor '. $TIME_AGO_hrs .' '. ( $TIME_AGO_hrs > 1 ? "Stunden" 	: "Stunde"	);
	elseif ($TIME_AGO_sec > 60) 			return 'vor '. $TIME_AGO_min .' '. ( $TIME_AGO_min > 1 ? "Minuten" 	: "Minute"	);
	else return 'vor wenigen Sekunden';
}
?>