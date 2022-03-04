let windowWidth: number = window.innerWidth;
window.addEventListener('resize', windowResize);

/** Se déclenche au resize de la page
 * Au delà de 992px de large (format LG sur Bootstrap), l'offcanvas menu se rétracte
 * Bootstrap fait apparaitre un <div class="offcanvas-backdrop fade show" qui grise le reste de l'écran
 * Quand on supprime la classe "show" du sidebar, il faut aussi supprimer cette div supplémentaire
 * pour supprimer ce grisage *
*/
function windowResize(): void {
    let mobileSidebar = document.getElementById('mobile_sidebar') as HTMLDivElement; // trouvable dans mainContainer.html
    windowWidth = window.innerWidth;

    if (windowWidth >= 992 && mobileSidebar.classList.contains('show')) {
        mobileSidebar.classList.remove('show');

        if (document.getElementsByClassName('offcanvas-backdrop').length != 0) {
            document.getElementsByClassName('offcanvas-backdrop')[0].remove();
        }
    }
}
