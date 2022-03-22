import { Offcanvas } from 'bootstrap';
import _ from 'lodash';
var MobileSidebar = /** @class */ (function () {
    function MobileSidebar() {
        this.windowWidth = window.innerWidth;
        this.mobileSidebarElement = document.getElementById('mobile_sidebar');
        window.addEventListener('resize', _.debounce(this.windowResize.bind(this), 150));
    }
    /** Se déclenche au resize de la page
     * Au delà de 992px de large (format LG sur Bootstrap), l'offcanvas menu se rétracte
    */
    MobileSidebar.prototype.windowResize = function () {
        this.windowWidth = window.innerWidth;
        if (this.windowWidth >= 992 && this.mobileSidebarElement.classList.contains('show')) {
            var boostrapSidebarObj = Offcanvas.getInstance(this.mobileSidebarElement);
            boostrapSidebarObj.hide();
        }
    };
    return MobileSidebar;
}());
export default MobileSidebar;
