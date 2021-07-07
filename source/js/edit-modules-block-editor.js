/* const { link } = require("fs");
 */
( function( window, wp ){
    
    // check if gutenberg's editor root element is present.
    const editorEl = document.getElementById( 'editor' );
    if( !editorEl ){
        return; // do nothing if there's no gutenberg root element on page.
    }

    //Set default Values
    const buttonID = 'mod-edit-modules';

    //create button
    let button = document.createElement('a');
    button.className += ' components-button c-button c-button__outlined c-button__outlined--default c-button--sm';
    button.setAttribute('id', buttonID);
    button.setAttribute('href', blockeditior.hrefeditmodules);

    //create label
    let label = document.createElement('span');
    label.classList.add = 'c-button__label';

    let labelText = document.createElement('span');
    labelText.classList.add = 'c-button__label-text';

    //create text
    const labelTextString = document.createTextNode(blockeditior.langeditmodules);
    labelText.appendChild(labelTextString);

    label.appendChild(labelText);
    button.appendChild(label);

    const unsubscribe = wp.data.subscribe( function () {
        setTimeout( function () {
            if ( !document.getElementById( buttonID ) ) {
                const toolbalEl = editorEl.querySelector( '.edit-post-header__toolbar' );
                if( toolbalEl instanceof HTMLElement ){
                    toolbalEl.appendChild( button );
                }
            }
        }, 1 )
    } );
    // unsubscribe is a function - it's not used right now 
    // but in case you'll need to stop this link from being reappeared at any point you can just call unsubscribe();

    const callback = function(mutationsList, observer) {
        for(const mutation of mutationsList) {
            if (mutation.type === 'childList') {
                //Items has changed, check if it was a block
                const typewriter = editorEl.querySelector('.edit-post-visual-editor');
                const links = typewriter.querySelectorAll('[href]');

                //remove href from new items
                links.forEach(link => {
                    link.removeAttribute('href');
                })
            }
        }
    };

    //Add observer for editor
    const observer = new MutationObserver(callback);
    observer.observe(editorEl, { childList: true, subtree: true });

} )( window, wp )

