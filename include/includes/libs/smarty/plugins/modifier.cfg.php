<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     modifier.cfg.php
 * Type:     modifier
 * Name:     cfg
 * Purpose:  AllgmeinArray von Ilch
 * -------------------------------------------------------------
 */
function smarty_modifier_cfg($str)
{	global $allgAr;
	return ( isset( $allgAr[$str] ) ? $allgAr[$str] : "not defined in allgAr");
}
?>