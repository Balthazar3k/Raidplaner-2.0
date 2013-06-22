<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     modifier.age.php
 * Type:     modifier
 * Name:     ageTimeMsg
 * Purpose:  Ändert datum in alter um
 * -------------------------------------------------------------
 */
function smarty_modifier_age($string)
{	$date = strtotime( $string );
    $bm = date("m", $date);
	$bt = date("d", $date);
	$bj = date("Y", $date);
	
	$j = date("Y")-$bj;
	if( $bm > date("m") ) $j--;
	if( $bm == date("m") AND $bt > date("d")) $j--;
	return $j;
}
?>