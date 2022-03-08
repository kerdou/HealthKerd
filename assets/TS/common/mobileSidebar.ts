window.addEventListener('resize', windowResize);

let mobileSidebar = document.getElementById('mobile_sidebar') as HTMLElement; // trouvable dans mainContainer.html
let boostrapSidebarObj = new bootstrap.Offcanvas(mobileSidebar);
let windowWidth: number = window.innerWidth;

/** Se déclenche au resize de la page
 * Au delà de 992px de large (format LG sur Bootstrap), l'offcanvas menu se rétracte
*/
function windowResize(): void {
    windowWidth = window.innerWidth;

    if (windowWidth >= 992 && mobileSidebar.classList.contains('show')) {
        boostrapSidebarObj.hide();
    }
}
