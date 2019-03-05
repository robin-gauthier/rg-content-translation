<?php


spl_autoload_register(function($class) {
    if(file_exists(__DIR__ . '/inc/'.$class.'.php')) {
        include(__DIR__ . '/inc/'.$class.'.php');
    }
});

/*
* Use either Polylang or WPML from active plugin
*/

if(in_array('polylang/polylang.php',  get_option('active_plugins'))) {
    $translationTool = new PolylangService();
} elseif(in_array('sitepress-multilingual-cms/sitepress.php',  get_option('active_plugins'))) {
    $translationTool = new WpmlService();
} else {
    $translationTool = null;
}


/*
* if plugin exists load scripts
*/

if(is_null($translationTool)) {
    add_action( 'admin_notices', 'pluginMissing' );
} else {
    require_once('ajax-functions.php');
    add_action('admin_menu', 'add_menus');
    add_action('admin_enqueue_scripts', 'addJavascripts');

    $currentPluginLanguage = isset($_GET['lang'])?$_GET['lang']:$translationTool->getCurrentLang();
    $currentPage = 1;
}

/*
* Top level actions
*/

function addJavascripts() {
    wp_enqueue_script( 'rgct_js', plugin_dir_url( __FILE__ ) . '/assets/js/rgct.js' );
    wp_enqueue_style( 'rgct_css', plugin_dir_url( __FILE__ ) . '/assets/css/rgct.css' );
}

function pluginMissing() {
    echo '<div class="error notice">
            <p>'. _e( "Polylang or WPML need to be configure to use " . PLUGIN_NAME, PLUGIN_NAME).'</p>
          </div>';
}

function add_menus() 
{
    add_menu_page('Traductions', 'Traductions', 'edit_posts', 'rg-content-translation/list.php', '', 'dashicons-editor-paste-text', 100 );
}