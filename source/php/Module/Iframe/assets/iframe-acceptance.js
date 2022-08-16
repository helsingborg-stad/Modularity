const accepted = localStorage.getItem('iframeAccepted') === 'accepted';
const iframeContainers = document.querySelectorAll('[iframe-container]');
const buttons = document.querySelectorAll('[data-js-toggle-trigger="show-iframe"]');

if(!accepted){
    for (const button of buttons) {
        button.addEventListener('click', showIframe);
    }
}

if (accepted){
    showIframe();
}

function showIframe () {

    localStorage.setItem('iframeAccepted', "accepted");
    
    for (let iframeContainer of iframeContainers) {
        let iframeAcceptanceWrapper = iframeContainer.querySelector('[iframe-acceptance-wrapper]');
        let iframe = iframeContainer.querySelector('iframe');
        iframeAcceptanceWrapper.style.display = 'none';
        iframe.style.filter = "none";
        iframe.style.pointerEvents = "auto";
    } 
}



