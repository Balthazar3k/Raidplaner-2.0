<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     modifier.icon.php
 * Type:     modifier
 * Name:     ageTimeMsg
 * Purpose:  Gibt icon images wieder {'cancel'|icon}
 * -------------------------------------------------------------
 */
function smarty_modifier_icon($str)
{	
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
		return "Das Icon exestiert nicht in der Liste";
	}
}
?>