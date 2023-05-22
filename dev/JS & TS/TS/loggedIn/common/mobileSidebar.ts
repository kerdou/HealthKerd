import { Offcanvas } from 'bootstrap';
import _ from 'lodash';

export default class MobileSidebar
{
    private windowWidth: number = window.innerWidth;
    private mobileSidebarElement = document.getElementById('mobile_sidebar') as HTMLElement;

    constructor() {
        window.addEventListener('resize', _.debounce(this.windowResize.bind(this), 150));
    }

    /** Se déclenche au resize de la page
     * Au delà de 992px de large (format LG sur Bootstrap), l'offcanvas menu se rétracte
    */
    private windowResize(): void {
        this.windowWidth = window.innerWidth;

        if (this.windowWidth >= 992 && this.mobileSidebarElement.classList.contains('show')) {
            const boostrapSidebarObj = Offcanvas.getInstance(this.mobileSidebarElement) as Offcanvas;
            boostrapSidebarObj.hide();
        }
    }
}
