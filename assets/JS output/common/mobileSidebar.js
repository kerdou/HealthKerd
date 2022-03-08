"use strict";
window.addEventListener('resize', windowResize);
var mobileSidebar = document.getElementById('mobile_sidebar'); // trouvable dans mainContainer.html
var boostrapSidebarObj = new bootstrap.Offcanvas(mobileSidebar);
var windowWidth = window.innerWidth;
/** Se déclenche au resize de la page
 * Au delà de 992px de large (format LG sur Bootstrap), l'offcanvas menu se rétracte
*/
function windowResize() {
    windowWidth = window.innerWidth;
    if (windowWidth >= 992 && mobileSidebar.classList.contains('show')) {
        boostrapSidebarObj.hide();
    }
}
