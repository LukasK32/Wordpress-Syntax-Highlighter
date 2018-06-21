jQuery(document).ready(function(){
    
    /*
    |--------------------------------------------
    |   Themes tab
    |--------------------------------------------
    */
    if(jQuery('#pmd_syntax_highlighter_tab_theme').length > 0){

        function updateTheme(){
            var theme = jQuery('input[type=radio][name=prism-theme]:checked').val();
            var url = jQuery('#pomelodev_syntax_highlighter_theme-css')[0].href;
            var t = url.substr(0, url.lastIndexOf("/")) + '/' + theme + '.css';
            jQuery('#pomelodev_syntax_highlighter_theme-css')[0].href = t;
        }
    
        jQuery('input[type=radio][name=prism-theme]').change(updateTheme);
        updateTheme();

    }

    /*
    |--------------------------------------------
    |   Languages tab
    |--------------------------------------------
    */
    if(jQuery('#pmd_syntax_highlighter_tab_languages').length > 0){

        function getRequiredLanguages(slug){
            if(
                prismLanguages[slug] !== undefined 
                && prismLanguages[slug]['require'] !== undefined
            ){

                let requires = prismLanguages[slug]['require'];

                if(jQuery.isArray(requires))
                    return requires;
                else
                    return [requires];

            }else
                return [];
        }

        function enabledLanguage(slug){
            let requires = getRequiredLanguages(slug);

            for(var i = 0; i < requires.length; i++){
                jQuery('input[type=checkbox][name="lang_'+requires[i]+'"]').prop( "checked", true );
                enabledLanguage(requires[i]);
            }
        }

        function disabledLanguage(slug){
            //Disable languages that requires this one
            jQuery('input[type=checkbox][name^="lang_"]:checked').each((index, element) => {
                var name = jQuery(element).attr("name");
                name = name.replace('lang_', '');

                var requires = getRequiredLanguages(name);

                if( (requires.indexOf(slug) > -1) ){
                    jQuery(element).prop( "checked", false );
                    disabledLanguage(name);
                }
            });
        }

        jQuery('input[type=checkbox][name^="lang_"]').change(function(){
            var name = jQuery(this).attr("name");
            name = name.replace('lang_', '');

            if(jQuery(this).prop('checked'))
                enabledLanguage(name);
            else
                disabledLanguage(name);
        });
    }
});