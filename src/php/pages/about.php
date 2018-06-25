<?php
namespace pomelodev\wp\plugins\syntax_highlighter;
if ( !defined( 'ABSPATH' ) || !function_exists( 'add_action' ) ) exit;
?>

<h3>
    <?php _e( 'Syntax highlighter', 'pomelodev_syntax_highlighter' ); ?>
    <small>
        by <a href="https://www.pomelodev.pl" target="_blank">Pomelodev</a>
    </small>
</h3>
<p>
    <?php
        echo _e(
            'Syntax highlighter is free, easy to use, Wordpress plugin extending default Gutenberg code block by adding code highlighting for all languages supported by Prism.js.',
            'pomelodev_syntax_highlighter'
        );
    ?>
</p>

<h3>Prism</h3>
<p>
    <?php
        echo sprintf(
            __(
                'Prism is a lightweight, robust, elegant syntax highlighting library. It\'s a spin-off project from <a href="%s" target="_blank">Dabblet</a>.',
                'pomelodev_syntax_highlighter'
            ),
            'http://dabblet.com/'
        );
    ?>
</p>
<p>
    <?php
        echo sprintf(
            __( 'You can learn more on %s.', 'pomelodev_syntax_highlighter' ),
            '<a href="http://prismjs.com/">http://prismjs.com/</a>'
        );
    ?>
</p>