<?php
namespace pomelodev\wp\plugins\syntax_highlighter;
if ( !defined( 'ABSPATH' ) || !function_exists( 'add_action' ) ) exit;
?>

<h3><?php echo translate('Syntax highlighter'); ?> <small>by <a href="https://www.pomelodev.pl" target="_blank">Pomelodev</a></small></h3>
<p>
    <?php
        echo translate('Syntax highlighter is free, easy to use, Wordpress plugin extending default Gutenberg code block by adding code highlighting for all languages supported by Prism.js.');
    ?>
</p>

<h3>Prism</h3>
<p>
    <?php
        echo sprintf(
            translate('Prism is a lightweight, robust, elegant syntax highlighting library. It\'s a spin-off project from <a href="%s" target="_blank">Dabblet</a>.'),
            'http://dabblet.com/'
        );
    ?>
</p>
<p>
    <?php
        echo sprintf(
            translate('You can learn more on %s.'),
            '<a href="http://prismjs.com/">http://prismjs.com/</a>'
        );
    ?>
</p>

<p><?php echo translate(''); ?></p>