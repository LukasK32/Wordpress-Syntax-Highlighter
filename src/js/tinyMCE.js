(function() {

    tinymce.create('tinymce.plugins.pmd_syntax_highlighter', {
        init(editor, url){
            
            //console.log(editor.settings.pmd_syntax_highlighter_languages);

            editor.addButton('pmd_syntax_highlighter', {
                title : 'Syntax highlighter',
                image : url + '/../icon.png',
                onclick() {
                    
                    editor.windowManager.open({
                        title: 'Syntax highlighter',
                        body: [
                            {
                                name: 'lang',
                                label: 'Language',

                                type: 'listbox',
                                values: editor.settings.pmd_syntax_highlighter_languages,
                            },
                            {
                                name: 'inline',
                                label: 'Inline',

                                type: 'checkbox'
                            },
                            {
                                name: 'code',
                                label: 'Code',

                                type: 'textbox',
                                multiline: true,
                                autofocus: true,
                                minWidth: '350',
                                rows: 7,
                            }
                        ],
                        onsubmit: function(e) {

                            var codeClass = '';

                            if(e.data.lang != '')
                                codeClass = ' class="language-' + e.data.lang + '"';

                            if(e.data.inline){
                                editor.insertContent('&nbsp;<code'+ codeClass +'>' + e.data.code + '</code>&nbsp;');
                            }else
                            {
                                editor.insertContent('&nbsp;<pre class="wp-block-code"><code'+ codeClass +'>' + e.data.code + '</code></pre>&nbsp;');
                            }
                        }
                    });

                }
            });
        },
        createControl(n, cm) {
            return null;
        },
        getInfo() {
            return {
                longname : 'Syntax highlighter',
                author : 'Pomelodev',
                authorurl : 'https://pomelodev.pl',
                infourl : 'https://pomelodev.pl',
                version : "1.0"
            };
        }
    });

    tinymce.PluginManager.add( 'pmd_syntax_highlighter', tinymce.plugins.pmd_syntax_highlighter );

})();