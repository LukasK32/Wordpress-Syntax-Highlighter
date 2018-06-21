<?php
namespace pomelodev\wp\plugins\syntax_highlighter;
if ( !defined( 'ABSPATH' ) || !function_exists( 'add_action' ) ) exit;


class settingsManager {

    private const settingsGroupName = 'pomelodev_syntax_highlighter';
    private const settingsPrefix = self::settingsGroupName.'_';

    protected $registeredSettings = [];

    /*
    |--------------------------------------------
    |   Singleton
    |--------------------------------------------
    */
    private static $instance = null;

    private function __construct() {

        self::registerSetting('settingsSavedTimestamp', 0, 'integer');

    }

    private function __clone() {}

    public static function getInstance(){
        
        if(self::$instance === null){
            self::$instance = new self();
        }

        return self::$instance;
    }

    /*
    |--------------------------------------------
    |   Settings API wrappers
    |--------------------------------------------
    */
    public function getSetting($name){
        $value = \get_option(
            self::settingsPrefix.$name,
            null
        );

        if($value === null && array_key_exists($name, $this->registeredSettings))
            $value = $this->registeredSettings[$name];
        
        return $value;
    }

    public function setSetting($name, $value, $updateTimestamp = true){

        \update_option(
            self::settingsPrefix.$name,
            $value
        );

        if($updateTimestamp)
            \update_option(
                self::settingsPrefix.'settingsSavedTimestamp',
                time()
            );

    }

    public function registerSetting($name, $default_value, $type = 'string'){

        if(array_key_exists($name, $this->registeredSettings))
            return false;
        
        $this->registeredSettings[$name] = $default_value;

        \register_setting(
            self::settingsGroupName,
            self::settingsPrefix.$name,
            [
                'type' => $type,
                'default' => $default_value
            ]
        );
    }
}