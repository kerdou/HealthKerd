var ScrollUpButton = /** @class */ (function () {
    function ScrollUpButton() {
        var _this = this;
        var scrollUpButton = document.getElementById('scrollUpButton');
        scrollUpButton.addEventListener('click', this.scrollToTop);
        window.onscroll = function () {
            _this.scrollUpButtonDisplayBehaviour();
        };
    }
    /** Fait apparaitre ou disparaitre le scrollUp button si l'écran est scroll à plus ou moins de 20px du haut de page
      */
    ScrollUpButton.prototype.scrollUpButtonDisplayBehaviour = function () {
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
    };
    /** Remonte l'écran quand la fonction est activée
     */
    ScrollUpButton.prototype.scrollToTop = function () {
        var scrollUpButton = document.getElementById('scrollUpButton');
        if (scrollUpButton.style.opacity === '1') {
            document.body.scrollTop = 0; // For Safari
            document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
        }
    };
    return ScrollUpButton;
}());
export default ScrollUpButton;
