<?php
namespace pomelodev\wp\plugins\syntax_highlighter;
if ( !defined( 'ABSPATH' ) || !function_exists( 'add_action' ) ) exit;


$languages = prismManager::getAvailableLanguages();
$activeLanguages = settingsManager::getInstance()->getSetting('languages');

if(
    ! empty( $_POST )
    && adminPageManager::pageHelper()->verifyNonceField('changeLanguages')
){
    
    $to_save = [];


    foreach($languages as $slug => $lang){

        if(
            isset($_POST['lang_'.$slug])
            && $_POST['lang_'.$slug] === 'yes'
        )
            $to_save[] = $slug;

    }


    settingsManager::getInstance()->setSetting('languages', $to_save);
    $activeLanguages = $to_save;
}

?>


<form method="POST" action="<?php echo adminPageManager::pageHelper()->getCurrentTabUrl(); ?>">
    <?php adminPageManager::pageHelper()->echoNonceField('changeLanguages'); ?>

    <table class="form-table">
        <tbody>
        <?php
            $i = 0;
            foreach($languages as $slug => $lang){
                if($i == 0)
                    echo '<tr>';
                else if($i % 4 == 0)
                    echo '</tr><tr>';
                
                $i++;
                ?>
                <td style="padding-bottom: 5px; padding-top: 5px;">
                    
                    <input 
                        name="lang_<?php echo $slug; ?>"
                        id="lang_<?php echo $slug; ?>"
                        type="checkbox"
                        value="yes"
                        <?php if(in_array($slug, $activeLanguages)) echo 'checked="yes"' ?>
                        >
                    
                        <label for="lang_<?php echo $slug; ?>">
                            <b>
                                <?php echo $lang['title']; ?>
                            </b>
                            <?php
                            if(array_key_exists('owner', $lang)){
                                echo '<small> by <a href="'.adminPageManager::pageHelper()->getGithubUrl($lang['owner']).'" target="_blank">'.$lang['owner'].'</a></small>';
                            }
                            ?>
                        </label>

                </td>
               <?php
               
            }

            if(4 - ( $i % 4 ) > 0)
                echo '<td  style="padding-bottom: 5px; padding-top: 5px;" colspan="'.(4 - ( $i % 4 )).'"></td>'; 
        ?>
        </tr>
        </tbody>
    </table>

    <?php submit_button(); ?>
</form>

<script type="text/javascript">
var prismLanguages = <?php echo json_encode($languages); ?>
</script>