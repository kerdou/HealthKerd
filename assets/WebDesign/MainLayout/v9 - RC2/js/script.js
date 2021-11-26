window.addEventListener('load', navContentCopy);


let windowWidth = window.innerWidth;
//console.log(windowWidth);

window.addEventListener("resize", windowResize);
let offCanvasSidebar = document.getElementById('offcanvas-nav');

/** Se déclenche au resize de la page */
function windowResize() {
    windowWidth = window.innerWidth;

    /** Au delà de 992px de large (format LG sur Bootstrap), l'offcanvas menu se rétracte
     * Bootstrap fait apparaitre un <div class="offcanvas-backdrop fade show" qui grise le reste de l'écran
     * Quand on supprime la classe "show" du sidebar, il faut aussi supprimer cette div supplémentaire
     * pour supprimer ce grisage
     */
    if (windowWidth >= 992 && offCanvasSidebar.classList.contains('show')) {
        offCanvasSidebar.classList.remove('show');

        if (document.getElementsByClassName('offcanvas-backdrop').length != 0) {
            document.getElementsByClassName('offcanvas-backdrop')[0].remove();
        }

    }
}

// copie du contenu du sidebar dans le off canvas sidebar au chargement de la page
function navContentCopy() {
    let mySideBarContent = document.getElementById('my-sidebar').innerHTML;
    let myOffCanvasSidebar = document.getElementById('my-offcanvas-sidebar');
    myOffCanvasSidebar.innerHTML = mySideBarContent;
}
