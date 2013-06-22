<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     block.perm.php
 * Type:     block
 * Name:     perm
 * Author: 	 Balthazar3k
 * Purpose:  Show or Hide a Block
 * Example:  {perm authright=-6}Hallo World 123{/perm}
 * -------------------------------------------------------------
 * Für das ilch1.2 CMS (Module:Raidplaner)
 */
function smarty_block_perm($params, $content, &$smarty, &$repeat){

   if ( isset($content) ){
		if( is_admin() ){
			return $content;
		}elseif( isset( $params['authright'] ) ){
			if( $_SESSION['authright'] <= $params['authright'] ){
				return $content;
			}
		}elseif( isset( $params['authmod'] ) ){
			if( isset($_SESSION['authmod'][$params['authmod']]) ){
				return $content;
			}
		}else{
			return NULL;
		}
    }
	
}
?>