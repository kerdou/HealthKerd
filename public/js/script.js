window.addEventListener('load', operationsAtLoad);

/* Copie du contenu du sidebar dans le off canvas sidebar au chargement de la page */
function operationsAtLoad() {
    let mySideBarContent = document.getElementById('my-sidebar').innerHTML;
    let myOffCanvasSidebar = document.getElementById('my-offcanvas-sidebar');
    myOffCanvasSidebar.innerHTML = mySideBarContent;

    textAreaRidonliListenersAddition(); // Pour faire disparaitre "Informations complémentaires" au scroll des textarea

    // obligé de gérer ça dés le chargement de la page pour éviter des erreurs aprés des rechargements avec F5
    //scrollUpButton = document.getElementById("scrollUpButton");
    //scrollUpButton.addEventListener('click', scrollToTop); // When the user clicks on the button, scroll to the top of the document
    document.getElementById("scrollUpButton").addEventListener('click', scrollToTop);
}




/* Retraction du menu off canvas quand le navigateur devient plus large que 992px */
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




// When the user scrolls down 20px from the top of the document, show the button
window.onscroll = function() {scrollFunction();};

/**   */
function scrollFunction() {
  if (document.body.scrollTop > 20 ||
    document.documentElement.scrollTop > 20) {
    document.getElementById("scrollUpButton").style.visibility = 'visible';
    document.getElementById("scrollUpButton").style.opacity = 1;
    document.getElementById("scrollUpButton").style.pointer = 'cursor';
  } else {
    document.getElementById("scrollUpButton").style.opacity = 0;

    // retard de visiblity=hiden et pointer=none pour garantir une disparition fluide du scrollUpButton
    setTimeout(function(){
      // le if évite d'avoir des changements intempestifs d'état
      if (document.getElementById("scrollUpButton").style.opacity == 0) {
        document.getElementById("scrollUpButton").style.visibility = 'hidden';
        document.getElementById("scrollUpButton").style.pointer = 'none';
      }},
      300
    );
  }
}


// remonte l'écran quand la fonction est activée
function scrollToTop() {
    if (scrollUpButton.style.opacity == 1) {
        document.body.scrollTop = 0; // For Safari
        document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
    }
}



// Pour faire disparaitre "Informations complémentaires" au scroll des textarea
function textAreaRidonliListenersAddition() {
  let ridonList = Array.from(document.getElementsByClassName('textarea-ridonli'));

  ridonList.forEach(element => {
    element.addEventListener('scroll', textAreaScrollDown);
  });
}

function textAreaScrollDown() {
  this.nextElementSibling.style.opacity = 0;
}