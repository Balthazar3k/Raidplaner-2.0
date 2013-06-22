<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     block.status.php
 * Type:     block
 * Name:     status
 * Purpose:  Status in einem Design
 * -------------------------------------------------------------
 */
function smarty_block_status($params, $content, &$smarty)
{
	if( isset( $content ) ){		
		return "<div class=\"status\"><div class=\"status_".$params['id']."\"><ol><li>".$content."</li></ol></div></div>";
	}
}
?>