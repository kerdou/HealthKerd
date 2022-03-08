window.addEventListener('load', scrollUpAtLoad);

function scrollUpAtLoad(): void {
    if (document.body.contains(document.getElementById('desktop_sidebar'))) {
        const scrollUpButton = document.getElementById('scrollUpButton') as HTMLButtonElement; // trouvable dans pageBottom.html
        scrollUpButton.addEventListener('click', scrollToTop); //
    }
}

// When the user scrolls down 20px from the top of the document, show the button
window.onscroll = function () {
    scrollFunction();
};

/**   */
function scrollFunction(): void {
    let scrollUpButton = document.getElementById('scrollUpButton') as HTMLButtonElement

    if (document.body.scrollTop > 20 ||
        document.documentElement.scrollTop > 20
    ) {
        scrollUpButton.style.visibility = 'visible';
        scrollUpButton.style.opacity = '1';
        scrollUpButton.style.cursor = 'pointer';
    } else {
        scrollUpButton.style.opacity = '0';

        setTimeout(function () { // retard de visibility=hidden et pointer=none pour garantir une disparition fluide du scrollUpButton
            if (scrollUpButton.style.opacity === '0') { // le if évite d'avoir des changements intempestifs d'état
                scrollUpButton.style.visibility = 'hidden';
                scrollUpButton.style.cursor = 'none';
            }
        }, 300);
    }
}

// remonte l'écran quand la fonction est activée
function scrollToTop(): void {
    let scrollUpButton = document.getElementById('scrollUpButton') as HTMLButtonElement;

    if (scrollUpButton.style.opacity === '1') {
        document.body.scrollTop = 0; // For Safari
        document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
    }
}
