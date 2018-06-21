<?php
namespace pomelodev\wp\plugins\syntax_highlighter;
if ( !defined( 'ABSPATH' ) || !function_exists( 'add_action' ) ) exit;


$modifiedTimestamp = settingsManager::getInstance()->getSetting('settingsSavedTimestamp');
$cacheTimestamp = settingsManager::getInstance()->getSetting('cacheTimestamp');

//settingsManager::getInstance()->setSetting('cacheTimestamp', time());

//Writing test file
if(
    ! empty( $_POST )
    && adminPageManager::pageHelper()->verifyNonceField('updateCache')
){

}
?>

<form method="POST" action="<?php echo adminPageManager::pageHelper()->getCurrentTabUrl(); ?>">
    <?php adminPageManager::pageHelper()->echoNonceField('updateCache'); ?>
    <?php
        submit_button(
            translate('Update cache')
        );
    ?>
</form>