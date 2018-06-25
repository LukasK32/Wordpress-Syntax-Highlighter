<?php
namespace pomelodev\wp\plugins\syntax_highlighter;
if ( !defined( 'ABSPATH' ) || !function_exists( 'add_action' ) ) exit;


$availableThemes = prismManager::getAvailableThemes();
$currentTheme = settingsManager::getInstance()->getSetting('theme');


if(
    ! empty( $_POST )
    && adminPageManager::pageHelper()->verifyNonceField('changeTheme')
    && isset($_POST['prism-theme'])
){
    foreach($availableThemes as $slug => $theme){

        if($slug === $_POST['prism-theme'])
        {
            settingsManager::getInstance()->setSetting('theme', $slug, false);
            $currentTheme = $slug;
            ?>
            <div class="notice notice-success is-dismissible">
                <p><?php _e( 'Theme saved!', 'pomelodev_syntax_highlighter' ); ?></p>
            </div>
            <?php
            break;
        }

    }
}
?>

<form method="POST" action="<?php echo adminPageManager::pageHelper()->getCurrentTabUrl(); ?>">
    <?php adminPageManager::pageHelper()->echoNonceField('changeTheme'); ?>

<fieldset>
    <legend class="screen-reader-text"><span><?php _e( 'Theme', 'pomelodev_syntax_highlighter' ); ?></span></legend>

    <?php
    
        foreach($availableThemes as $slug => $theme){
            ?>
            <label>
                <input type="radio" name="prism-theme" value="<?php echo $slug ?>" <?php if($slug == $currentTheme) echo 'checked="checked"'; ?>>
                <?php
                    if(is_array($theme)){
                        echo $theme['title'];
                        if(array_key_exists('owner', $theme))
                            echo ' by <a href="'.adminPageManager::pageHelper()->getGithubUrl($theme['owner']).'" target="_blank">'.$theme['owner'].'</a>';
                    }else
                        echo $theme;
                    ?>
            </label>
            <br>
            <?php
        }

    ?>
    
    	
</fieldset>

    <?php submit_button(); ?>
</form>

<h2><?php _e( 'Example', 'pomelodev_syntax_highlighter' ); ?></h2>
<?php include(__DIR__.'/../assets/example.html'); ?>