import { Offcanvas } from 'bootstrap';
import _ from 'lodash';
export default function mobileSidebar() {
    var windowWidth = window.innerWidth;
    var mobileSidebarElement = document.getElementById('mobile_sidebar');
    var boostrapSidebarObj = Offcanvas.getInstance(mobileSidebarElement);
    window.addEventListener('resize', _.debounce(windowResize, 150));
    /** Se déclenche au resize de la page
     * Au delà de 992px de large (format LG sur Bootstrap), l'offcanvas menu se rétracte
    */
    function windowResize() {
        windowWidth = window.innerWidth;
        if (windowWidth >= 992 && mobileSidebarElement.classList.contains('show')) {
            boostrapSidebarObj.hide();
        }
    }
}
