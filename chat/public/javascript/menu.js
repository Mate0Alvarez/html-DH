var menuOpen = false;
var menu = document.getElementById('menu');
var menuToggle = document.getElementById('menu-toggle');
var moveWidth = parseInt(window.getComputedStyle(menu).getPropertyValue('right')) * -1;

menuToggle.addEventListener('click', toggleMenu);

function toggleMenu() {
	if (! menuOpen) {
		$(menu).animate({right: '+='+moveWidth+'px'});
		menuOpen = true;
	} else {
		$(menu).animate({right: '-='+moveWidth+'px'});
		menuOpen = false;
	}
}
