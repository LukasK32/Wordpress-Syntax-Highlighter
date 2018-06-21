<?php
namespace pomelodev\wp\plugins\syntax_highlighter;
if ( !defined( 'ABSPATH' ) || !function_exists( 'add_action' ) ) exit;

class cacheManager {

    /*
    |--------------------------------------------
    |   Singleton
    |--------------------------------------------
    */
    private static $instance = null;

    private function __construct() {}
    private function __clone() {}

    public static function getInstance(){
        
        if(self::$instance === null){
            self::$instance = new self();
        }

        return self::$instance;
    }

    /*
    |--------------------------------------------
    |   Cache checking
    |   Returns True if cache is outdated
    |--------------------------------------------
    */
    public function isCacheOutdated(){
        return false;
        
        return (bool)(
            settingsManager::getInstance()->getSetting('settingsSavedTimestamp') 
            >
            settingsManager::getInstance()->getSetting('cacheTimestamp')
        );
    }

    /*
    |--------------------------------------------
    |   Cache builder
    |--------------------------------------------
    */
    
}