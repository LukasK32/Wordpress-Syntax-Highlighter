const { __ } = wp.i18n;
const { addFilter } = wp.hooks;
const { InspectorControls } = wp.editor;
const { SelectControl } = wp.components;

function addLanguageAttributeToCodeBlock(blockSettings){
    if( blockSettings.name !== 'core/code' )
        return blockSettings;

    //Add current block to depreacated
    if( blockSettings.deprecated == null )
    blockSettings.deprecated = new Array();

    blockSettings.deprecated.push(
        {
            attributes: Object.assign({}, blockSettings.attributes),
            save({ attributes }){
                return <pre><code>{ attributes.content }</code></pre>;
            }
        }
    );
    
    //Add attribute
    blockSettings.attributes.codeLanguage = {
        type: 'string',
        selector: 'code',
        source: 'attribute',
        attribute: 'codelanguage'
    }

    
    //Block saving
    blockSettings.save = function( { attributes } ){
        if(attributes.codeLanguage !== '' && attributes.codeLanguage !== null && attributes.codeLanguage !== undefined ){

            return  <pre>
                        <code codelanguage={ attributes.codeLanguage } className={ "language-"+attributes.codeLanguage }>
                            { attributes.content }
                        </code>
                    </pre>;

        }else
            return <pre><code>{ attributes.content }</code></pre>;
    };

    return blockSettings;
}

function addLanguageSwitcherToCodeBlock( BlockEdit ){
    const WrappedBlockEdit = ( props ) => {

        if(props.name !== 'core/code')
            return <BlockEdit { ...props } />
        
        const { attributes, setAttributes, isSelected } = props

        return [
            <BlockEdit { ...props } />,
            isSelected && (
                <InspectorControls>
                    <SelectControl
                        label="Language"
                        value={ attributes.codeLanguage }
                        options={ window.pmd_syntax_highlighter_languages }
                        onChange = { ( value ) => {
                            props.setAttributes({codeLanguage: value});
                        }}
                        />
                </InspectorControls>
            )
        ]
    };

    return WrappedBlockEdit;
}


addFilter(
    'blocks.registerBlockType',
    'pomelodev-syntax-highlighter',
    addLanguageAttributeToCodeBlock
);

addFilter(
    'editor.BlockEdit',
    'pomelodev-syntax-superTemporary',
    addLanguageSwitcherToCodeBlock
);