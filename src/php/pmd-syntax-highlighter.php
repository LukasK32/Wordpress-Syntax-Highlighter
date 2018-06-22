<?php
/*
Plugin Name: Syntax highlighter
Plugin URI: https://github.com/LukasK32/Wordpress-Syntax-Highlighter
Description: TODO
Author: Pomelodev
Version: 1.0
Author URI: https://pomelodev.pl
License: MIT
Text Domain: pomelodev_syntax_highlighter


MIT License

Copyright (c) 2018 Łukasz Kirylak

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
*/

namespace pomelodev\wp\plugins\syntax_highlighter;
if ( !defined( 'ABSPATH' ) || !function_exists( 'add_action' ) ) exit;


/*
|--------------------------------------------
|   Hooks
|--------------------------------------------
*/
add_action( 'init', __NAMESPACE__.'\registerSettings');
add_action( 'admin_menu', __NAMESPACE__.'\registerAdminPage' );
add_action( 'admin_enqueue_scripts', __NAMESPACE__.'\registerAdminPageScripts' );
add_action( 'wp_enqueue_scripts', __NAMESPACE__.'\registerFrontend' );

//Gutenberg
add_action( 'enqueue_block_editor_assets', __NAMESPACE__.'\registerGutenbergPlugin' );

//TinyMCE
add_filter( 'mce_css', __NAMESPACE__.'\registerTinymceTheme' );
add_filter( 'mce_buttons', __NAMESPACE__.'\registerTinymceToolbarButton' );
add_filter(	'mce_external_plugins', __NAMESPACE__.'\registerTinymcePlugin' );
add_filter( 'tiny_mce_before_init', __NAMESPACE__.'\registerTinymceSettings' );


/*
|--------------------------------------------
|   Helpers
|--------------------------------------------
*/
function translate($text){
    return __($text, 'pomelodev_syntax_highlighter');
}


/*
|--------------------------------------------
|   Autoloader
|--------------------------------------------
*/
spl_autoload_register(function( $class ){
    if ( strpos( $class, __NAMESPACE__.'\\' ) === false ) {
        return;
    }

    include('classes/'.str_replace(__NAMESPACE__.'\\', '', $class).'.class.php');
});


/*
|--------------------------------------------
|   Functions
|--------------------------------------------
*/
function registerSettings(){

    //Pism's theme
    settingsManager::getInstance()->registerSetting('theme', 'prism.css');

    //Pism's languages
    if(is_admin())
        settingsManager::getInstance()->registerSetting('languages', prismManager::getDefaultLanguages());
    else
        settingsManager::getInstance()->registerSetting('languages', []);

    //Pism's plugins
    //settingsManager::getInstance()->registerSetting('plugins', '');

    //Cache timestamp
    settingsManager::getInstance()->registerSetting('cacheTimestamp', 0, 'number');

}

function registerAdminPage(){

    adminPageManager::$baseUrl = 'options-general.php?page=syntax-highlighter';
    
    adminPageManager::registerTab(translate('Theme'), 'theme');
    adminPageManager::registerTab(translate('Languages'), 'languages');
    //adminPageManager::registerTab(translate('Plugins'), 'plugins');
    //adminPageManager::registerTab(translate('Cache'), 'cache');
    adminPageManager::registerTab(translate('About'), 'about');

    add_submenu_page(
        'options-general.php',
        translate('Syntax highlighter'),
        translate('Syntax highlighter'),
        'administrator',
        'syntax-highlighter',
        array(
            __NAMESPACE__.'\adminPageManager',
            'render'
        )
    );

}

function registerAdminPageScripts($hook){
    if($hook != 'settings_page_syntax-highlighter') {
        return;
    }

    //Admin script
    wp_enqueue_script(
        'pomelodev_syntax_highlighter_example',
        plugins_url( '/assets/js/admin.js', __FILE__ ), 
        array('jquery'),
        '1.0',
        true
    );


    //Prism base
    wp_enqueue_script(
        'pomelodev_syntax_highlighter_prism_core',
        plugins_url( '/assets/prism/js/prism-core.min.js', __FILE__ ), 
        array(),
        '1.0',
        true
    );

    //Markdown
    wp_enqueue_script(
        'pomelodev_syntax_highlighter_prism_markup',
        plugins_url( '/assets/prism/js/prism-markup.min.js', __FILE__ ), 
        array('pomelodev_syntax_highlighter_prism_core'),
        '1.0',
        true
    );

    //CSS
    wp_enqueue_script(
        'pomelodev_syntax_highlighter_prism_css',
        plugins_url( '/assets/prism/js/prism-css.min.js', __FILE__ ), 
        array('pomelodev_syntax_highlighter_prism_core'),
        '1.0',
        true
    );

    //Current prism theme
    $theme = settingsManager::getInstance()->getSetting('theme');

    if($theme != 'no-theme'){
        wp_enqueue_style( 
            'pomelodev_syntax_highlighter_theme',
            plugins_url(
                '/assets/prism/css/'.$theme.'.css',
                __FILE__
            ) 
        );
    }

}

function registerFrontend(){
    //Prism theme
    $theme = settingsManager::getInstance()->getSetting('theme');

    if($theme != 'no-theme'){
        wp_enqueue_style( 
            'pomelodev_syntax_highlighter_theme',
            plugins_url(
                '/assets/prism/css/'.$theme.'.css',
                __FILE__
            ) 
        );
    }

    //Cache?
    //Disabled for now
    //if(cacheManager::getInstance()->isCacheOutdated()){
        //Enqueue all prism scripts
        
        //Prism base
        wp_enqueue_script(
            'pomelodev_syntax_highlighter_prism_core',
            plugins_url( '/assets/prism/js/prism-core.min.js', __FILE__ ), 
            array(),
            '1.0',
            true
        );

        //Languages
        $activeLanguages = settingsManager::getInstance()->getSetting('languages');
        $languages = prismManager::getAvailableLanguages();

        foreach($activeLanguages as $lang){
            $requires = [
                'pomelodev_syntax_highlighter_prism_core'
            ];

            if(array_key_exists('require', $languages[$lang])){

                if(is_array($languages[$lang]['require'])){

                    foreach($languages[$lang]['require'] as $required_slug)
                        $requires[] = 'pomelodev_syntax_highlighter_prism_lang_'.$required_slug;

                }else
                    $requires[] = 'pomelodev_syntax_highlighter_prism_lang_'.$languages[$lang]['require'];
            }

            wp_enqueue_script(
                'pomelodev_syntax_highlighter_prism_lang_'.$lang,
                plugins_url( '/assets/prism/js/prism-'.$lang.'.min.js', __FILE__ ), 
                $requires,
                '1.0', 
                true
            );

        }

    /*}else{
        //Enqueue cached script
    }*/
}

//Gutenberg extension
function registerGutenbergPlugin(){
    $activeLanguages = settingsManager::getInstance()->getSetting('languages');
    $languages = prismManager::getAvailableLanguages();
    
    ?>
    <script type="text/javascript">

        window.pmd_syntax_highlighter_languages = [
            { value: '', label: 'Don\'t use syntax highlighting.'},
        <?php

            foreach($activeLanguages as $lang){

                echo "{ value: '$lang', label: '".$languages[$lang]['title']."'},";

            }

        ?>
        ];
    </script>
    <?php

    wp_enqueue_script(
        'pomelodev-syntax-highlighter',
        plugins_url( 'assets/js/gutenberg.js', __FILE__ ),
        array( 'wp-blocks', 'wp-i18n', 'wp-element' ),
        '1.0.0'
    );

}

//TinyMCE extension
function registerTinymceTheme( $stylesheets ){

    if ( ! empty( $stylesheets ) )
		$stylesheets .= ',';

    $stylesheets .= plugins_url('assets/tinyMCE.css', __FILE__);

    return $stylesheets;
}

function registerTinymceToolbarButton( $toolbar_buttons ){
    
    array_push( $toolbar_buttons, 'separator', 'pmd_syntax_highlighter' );

    return $toolbar_buttons;
}

function registerTinymcePlugin( $mce_plugins ){

    $mce_plugins['pmd_syntax_highlighter'] = plugins_url( 'assets/js/tinyMCE.js', __FILE__ );

    return $mce_plugins;
}

function registerTinymceSettings( $mce_settings ){

    $activeLanguages = settingsManager::getInstance()->getSetting('languages');
    $languages = prismManager::getAvailableLanguages();

    $output = "[ { text: 'Don\'t use syntax highlighting.', value: '' },";

    foreach($activeLanguages as $lang){

        $output .= "{ text: '".$languages[$lang]['title']."', value: '$lang' },";

    }

    $output .= ']';

    $mce_settings['pmd_syntax_highlighter_languages'] = $output;

    return $mce_settings;
}