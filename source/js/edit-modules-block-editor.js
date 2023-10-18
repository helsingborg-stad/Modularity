(function(window, wp){
    const editModulesLinkId       = 'editModulesPageLink';
    const {editModulesLinkLabel, editModulesLinkHref} = modularityBlockEditor;

    // prepare our custom link's html.
    const editModulesLinkHTML = `
        <a 
            id="${editModulesLinkId}" 
            class="components-button has-icon"
            aria-label="${editModulesLinkLabel}" 
            href="${editModulesLinkHref}"
        >
            <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" style="margin-right: .3em;" aria-hidden="true" focusable="false">
                <path d="M240-440h360v-80H240v80Zm0-120h360v-80H240v80Zm-80 400q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h640q33 0 56.5 23.5T880-720v480q0 33-23.5 56.5T800-160H160Zm0-80h640v-480H160v480Zm0 0v-480 480Z"/>
            </svg>
            ${editModulesLinkLabel}
        </a>
    `;

    // check if gutenberg's editor root element is present.
    var editorEl = document.getElementById('editor');
    if(!editorEl){
        return;
    }

    //Append button if not existing
    wp.data.subscribe( function () {
        setTimeout( function () {
            if (!document.getElementById( editModulesLinkId )) {
                var toolbalEl = editorEl.querySelector( '.edit-post-header__toolbar' );
                if(toolbalEl instanceof HTMLElement){
                    toolbalEl.insertAdjacentHTML('beforeend', editModulesLinkHTML);
                }
            }
        }, 1)
    } );
})( window, wp )