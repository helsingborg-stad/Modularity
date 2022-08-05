const accepted = localStorage.getItem('iframeAccepted');
const iframeContainers = document.querySelectorAll('[iframe-container="mod-iframe"]');
const buttons = document.querySelectorAll('[data-js-toggle-trigger="show-iframe"]');

for (const button of buttons) {
    button.addEventListener('click', showIframe);
}

if (accepted === "accepted"){
    showIframe();
}

function showIframe () {
    if(!accepted) {
        localStorage.setItem('iframeAccepted', "accepted");
    }

    for (let iframeContainer of iframeContainers) {
        let iframeAcceptanceWrapper = iframeContainer.querySelector('[iframe-acceptance-wrapper="mod-iframe"]');
        let iframe = iframeContainer.querySelector('[iframe="mod-iframe"');
        iframeAcceptanceWrapper.style.display = 'none';
        iframe.style.filter = "none";
        console.log(accepted);
   
    }
    
}



