<?php
namespace pomelodev\wp\plugins\syntax_highlighter;
if ( !defined( 'ABSPATH' ) || !function_exists( 'add_action' ) ) exit;


class adminPageHelper {

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
    |   API
    |--------------------------------------------
    */
    public function getCurrentTabUrl(){
        return adminPageManager::$baseUrl.'&tab='.adminPageManager::$currentTab;
    }

    public function echoNonceField($action = null){
        wp_nonce_field( 'pomelodev-syntax-highlighter', 'pomelo-nonce' );

        if($action !== null)
            echo '<input type="hidden" name="action" value="'.$action.'" />';
    }

    public function verifyNonceField($action = null){
        if($action === null){

            return (bool)(
                check_admin_referer( 'pomelodev-syntax-highlighter', 'pomelo-nonce' )
            );

        }else {

            return (bool)(
                isset($_POST['action'])
                && $_POST['action'] === $action
                && check_admin_referer( 'pomelodev-syntax-highlighter', 'pomelo-nonce' )
            );

        }
    }

    public function getGithubUrl($username){
        return 'https://github.com/'.$username;
    }

}