function launchFullscreen(element) {
    if (element.requestFullscreen) {
        element.requestFullscreen();
    } else if (element.mozRequestFullScreen) { // Firefox
        element.mozRequestFullScreen();
    } else if (element.webkitRequestFullscreen) { // Chrome, Safari and Opera
        element.webkitRequestFullscreen();
    } else if (element.msRequestFullscreen) { // IE/Edge
        element.msRequestFullscreen();
    }
}
document.addEventListener('click', function once() {
    launchFullscreen(document.documentElement);
    //document.removeEventListener('click', once); // Only run once
});

// Hide mouse cursor after 3 seconds of inactivity
let timer;
document.onmousemove = function(){
  document.body.classList.remove('kiosk-mode');
  clearTimeout(timer);
  timer = setTimeout(() => {
    document.body.classList.add('kiosk-mode');
  }, 3000);
};
// Disable right-click
//document.addEventListener('contextmenu', event => event.preventDefault());