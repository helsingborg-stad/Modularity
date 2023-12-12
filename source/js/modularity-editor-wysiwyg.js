document.addEventListener("DOMContentLoaded", moduleModalWysiwygSelect);
function moduleModalWysiwygSelect() {
    setTimeout(function() {
        const select = document.querySelector('#mceu_0-open');
        select.addEventListener("click", () => {
            setTimeout(function() {
                const parent = select.closest('.mce-toolbar-grp');
                const floatPanel = document.querySelector('.mce-floatpanel');
                if (floatPanel && parent && parent.style.position === 'fixed') {
                    const pageOffset = window.scrollY;
                    const selectRect = select.getBoundingClientRect();
                    floatPanel.style.top = (pageOffset + selectRect.top - 5) + 'px';
                    floatPanel.style.left = '0px';
                }
            }, 100)
        });
    }, 1000); 
}
