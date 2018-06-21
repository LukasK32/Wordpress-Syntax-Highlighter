<?php
namespace pomelodev\wp\plugins\syntax_highlighter;
if ( !defined( 'ABSPATH' ) || !function_exists( 'add_action' ) ) exit;


class prismManager {

    private function __construct() {}
    private function __clone() {}

    private static $decodedJson = null;

    private static function getDecodedJson(){
        if(self::$decodedJson === null){
            $file = file_get_contents(__DIR__.'/../assets/prism/components.json', FILE_USE_INCLUDE_PATH);

            if(!$file)
                return;

            self::$decodedJson = json_decode($file, true);
        }

        //Delete META
        unset(self::$decodedJson['themes']['meta']);
        unset(self::$decodedJson['languages']['meta']);

        return self::$decodedJson;
    }

    public static function getAvailableThemes(){
        return self::getDecodedJson()['themes'];
    }

    public static function getAvailableLanguages(){
        return self::getDecodedJson()['languages'];
    }

    public static function getDefaultLanguages(){
        $languages = [];

        foreach(self::getDecodedJson()['languages'] as $slug => $data){

            if(array_key_exists( 'option', $data ) && $data['option'] == 'default' )
                $languages[] = $slug;
                
        }

        return $languages;
    }
}