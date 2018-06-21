<?php
namespace pomelodev\wp\plugins\syntax_highlighter;
if ( !defined( 'ABSPATH' ) || !function_exists( 'add_action' ) ) exit;


class adminPageManager {

    private function __construct() {}
    private function __clone() {}

    public static $baseUrl = '';

    private static $tabs = [];
    public static $currentTab = null;

    public static function render(){
        if(!current_user_can('administrator'))
            wp_die( translate( 'You do not have sufficient permissions to access this page.' ) );
        ?>

        <div class="wrap">
            <h1><?php echo translate('Syntax highlighter'); ?></h1>

            <?php
            $tab = array_keys(self::$tabs)[0];

            if(isset( $_GET[ 'tab' ] ))
                $tab = $_GET['tab'];
            ?>

            <h2 class="nav-tab-wrapper">
            <?php
                foreach(self::$tabs as $slug => $data){
                    ?>
                    <a href="<?php echo admin_url( self::$baseUrl.'&tab='.$slug ); ?>" class="nav-tab<?php if($tab === $slug) echo ' nav-tab-active'; ?>">
                        <?php echo $data['name']; ?>
                    </a>
                    <?php
                }
            ?>
            </h2>

            <?php
                if( array_key_exists($tab, self::$tabs) ){
                    echo '<h2 id="pmd_syntax_highlighter_tab_'.$tab.'">'.self::$tabs[$tab]['name'].'</h2>';
                    self::$currentTab = $tab;
                    include(__DIR__.'/../pages/'.$tab.'.php');
                }
                else
                    echo translate('Page not found.');

                if(cacheManager::getInstance()->isCacheOutdated()):
                    ?>
                    <div class="notice notice-warning">
                        <p><?php echo sprintf(
                            translate('Your cache is outdated! You can rebuild it <a href="%s">here</a>.'),
                            self::$baseUrl.'&tab=cache'
                        ); ?></p>
                    </div>
                    <?php
                endif;
            ?>



        </div>

        <?php
    }

    public static function registerTab($name, $slug){
        self::$tabs[$slug] = [
            'name' => $name
        ];
    }

    public static function pageHelper(){
        return adminPageHelper::getInstance();
    }
}