<?php

include_once DOL_DOCUMENT_ROOT . "/core/modules/DolibarrModules.class.php";

class modCbwarQuarterStats extends DolibarrModules
{

    public function __construct($db)
    {
        global $langs;

        // DolibarrModules is abstract in Dolibarr < 3.8
        if (is_callable('parent::__construct')) {
            parent::__construct($db);
        } else {
            global $db;
            $this->db = $db;
        }

        $this->numero = 994989;
        $this->need_dolibarr_version = array(3, 2);
        $this->phpmin = array(5, 3);
        $this->family = 'products';
        $this->module_position = 500;

        $this->name = preg_replace('/^mod/i', '', get_class($this));
        $this->const_name = 'MAIN_MODULE_' . strtoupper($this->name);
        $this->description = "Products and service stats per quarters";
        $this->editor_name = "Raphael Lisch";
        $this->editor_url = "https://raphael.lisch.fr";
        $this->version = 'development';
        $this->config_page_url = array('setup.php@cbwarquarterstats');

        $this->special = 0;
        $this->picto = '/theme/eldy/img/stats.png';

        $this->dirs = array();
        $this->depends = array();
        $this->requiredby = array();
        $this->langfiles = array("cbwarquarterstats@cbwarquarterstats");
        $this->const = array();
        $this->tabs = array();
        $this->boxes = array();
        $this->rights = array();
        $this->rights_class = 'cbwarquarterstats';

        $this->module_parts = array(
            'triggers' => false,
            'login' => false,
            'substitutions' => false,
            'menus' => true,
            'theme' => false,
            'tpl' => false,
            'barcode' => false,
            'models' => false,
            'css' => array(),
            'js' => array(),
            'hooks' => array(),
            'dir' => array(),
            'workflow' => array(),
        );

        // Add menus
        $this->menu[] =
            array(
                'fk_menu' => 'fk_mainmenu=products',    // Use 'fk_mainmenu=xxx' or 'fk_mainmenu=xxx,fk_leftmenu=yyy' where xxx is mainmenucode and yyy is a leftmenucode of parent menu
                'type' => 'left',            // This is a Left menu entry
                'titre' => $langs->trans('StatsPerQuarter'),
                'mainmenu' => 'products',
                'leftmenu' => '',
                'url' => '/cbwarquarterstats/index.php',
                'langs' => 'cbwarquarterstats@cbwarquarterstats',    // Lang file to use (without .lang) by module. File must be in langs/code_CODE/ directory.
                'position' => 100,
                'enabled' => '1',            // Define condition to show or hide menu entry. Use '$conf->monmodule->enabled' if entry must be visible if module is enabled.
                'perms' => '1',            // Use 'perms'=>'$user->rights->monmodule->level1->level2' if you want your menu with a permission rules
                'target' => '',
                'user' => 0)                // 0=Menu for internal users,1=external users, 2=both
        ;


    }

    public function init($options = '')
    {
        $this->_load_tables('/cbwarquarterstats/sql/');
        return parent::init($options);
    }

    public function remove($options = '')
    {
        return parent::remove($options);
    }


}
