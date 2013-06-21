<?php
require_once('include/includes/libs/smarty/Smarty.class.php');
	
	
class raidSmarty extends Smarty {
    public function __construct() {	
		parent::__construct();
        $this->left_delimiter = '{';
        $this->right_delimiter = '}';
        if (defined('admin')) {
            $this->addTemplateDir(array('include/admin/templates', 'include/admin/templates/raid'));
        } else {
            $this->addTemplateDir(array(
				'include/designs/' . tpl::get_design() . '/templates',
				'include/templates/raid/',
                'include/templates'
            ));
        }
        $this->setCompileDir('include/cache/smarty_compile');
        $this->addPluginsDir('include/includes/libs/smarty/plugins');
		//arrPrint(__METHOD__, $this );
    }
}
?>