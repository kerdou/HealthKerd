"use strict";
window.addEventListener('load', scrollUpAtLoad);
function scrollUpAtLoad() {
    if (document.body.contains(document.getElementById('desktop_sidebar'))) {
        var scrollUpButton = document.getElementById('scrollUpButton'); // trouvable dans pageBottom.html
        scrollUpButton.addEventListener('click', scrollToTop); //
    }
}
// When the user scrolls down 20px from the top of the document, show the button
window.onscroll = function () {
    scrollFunction();
};
/**   */
function scrollFunction() {
    var scrollUpButton = document.getElementById('scrollUpButton');
    if (document.body.scrollTop > 20 ||
        document.documentElement.scrollTop > 20) {
        scrollUpButton.style.visibility = 'visible';
        scrollUpButton.style.opacity = '1';
        scrollUpButton.style.cursor = 'pointer';
    }
    else {
        scrollUpButton.style.opacity = '0';
        setTimeout(function () {
            if (scrollUpButton.style.opacity === '0') { // le if évite d'avoir des changements intempestifs d'état
                scrollUpButton.style.visibility = 'hidden';
                scrollUpButton.style.cursor = 'none';
            }
        }, 300);
    }
}
// remonte l'écran quand la fonction est activée
function scrollToTop() {
    var scrollUpButton = document.getElementById('scrollUpButton');
    if (scrollUpButton.style.opacity === '1') {
        document.body.scrollTop = 0; // For Safari
        document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
    }
}
