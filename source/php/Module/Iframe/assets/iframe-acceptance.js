const accepted = localStorage.getItem('iframeAccepted');
const iframe = document.querySelector('iframe');
const iframeAcceptanceWrapper = document.querySelector('[iframe-acceptance-wrapper]');
const button = iframeAcceptanceWrapper.querySelector('[data-js-toggle-trigger]');
button.addEventListener('click', showIframe);

let url = iframe.getAttribute('url');
iframe.setAttribute("src", url);

localStorage.clear();
if (accepted === "accepted"){
    showIframe();
}

function showIframe () {
    if(!accepted) {
        localStorage.setItem('iframeAccepted', "accepted");
    }
    iframeAcceptanceWrapper.style.display = 'none';
    iframe.style.filter="none";
    iframe.style.pointerEvents = "auto";

}



