<footer role="contentinfo">
	<p>
		<a target="_blank" href="http://www.todaysart.org"><svg class="inline-logo logo-todaysart" viewBox="0 0 149.2 31.4"><text>TodaysArt</text><use xlink:href="/assets/svg/symbols.svg#logo-todaysart"></use></svg></a>
		&amp;
		<a target="_blank" href="http://www.tudelft.nl"><svg class="inline-logo logo-tudelft" viewBox="0 0 255.00931 100.021485"><text>Delft University of Technology</text><use xlink:href="/assets/svg/symbols.svg#logo-tudelft"></use></svg></a>
		<?= $site->copyright()->kirbytextRaw(); ?>
	</p>
</footer>

<script type="text/javascript">
	// toggle menu
	var menu = document.getElementById('menu');
	var hamburger = menu.getElementsByClassName('hamburger')[0];
	var cross = menu.getElementsByClassName('cross')[0];
	var list = menu.getElementsByTagName('ul')[0];

	var main = document.getElementsByTagName('main')[0];

	hamburger.addEventListener("click", menu__show, false);
	cross.addEventListener("click", menu__hide, false);

	function menu__show() {
		hamburger.className = 'hamburger hidden';
		cross.className = 'cross';
		list.className = '';
		main.className = 'main hidden';
	}

	function menu__hide() {
		// back to initial
		hamburger.className = 'hamburger';
		cross.className = 'cross hidden';
		list.className = 'hidden';
		main.className = 'main';
	}



</script>




<script type="text/javascript">
	var toggle = true;


	function toggleDevnotes(){
		var pre = document.getElementsByTagName('pre');

		if (toggle === true) {
			for (var i = 0; i < pre.length; i++) {
				pre[i].style.display = 'none';
			}
			toggle = false;
		} else {
			for (var i = 0; i < pre.length; i++) {
				pre[i].style.display = '';
			}
			toggle = true;
		}
	}
	function toggleBackground(){
		var element = document.getElementsByTagName('body')[0];
		if (toggle === true) {
			element.className = '';
			toggle = false;
		} else {
			element.className = 'dev-bg';
			toggle = true;
		}
	}
	function toggleJS(){
		var element = document.getElementById('mobius');
		if (toggle === true) {
			element.style.display = 'none';
			document.getElementsByTagName('body')[0].style.background = '#00f';
			toggle = false;
		} else {
			element.style.display = '';
			document.getElementsByTagName('body')[0].style.background = '';
			toggle = true;
		}
	}

	window.addEventListener("keydown", function(e) {
		if ( e.code == 'KeyD' ) {
			toggleDevnotes();
		} if ( e.code == 'KeyF' ) {
			// toggleBackground();
			toggleJS();
		}
	}, true);

	toggleDevnotes();


</script>

<div id="mobius"></div>

<script src="/assets/js/bundle.min.js"></script>

<svg xmlns="http://www.w3.org/2000/svg" class="asset" height="0">
	<filter id="alpha" x="0%" y="0%" width="100%" height="100%">
		<feColorMatrix type="matrix" values=".7 0 0 0 0,
			.7 0 0 0 0,
			0 0 0 1 0,
			1 1 1 0 0" />
	</filter>
	<filter id="alpha2" x="0%" y="0%" width="100%" height="100%">
		<feColorMatrix type="matrix" values="1.0 0 0 0 -.3,
			1.0 0 0 0 -.3,
			0 0 0 1 0,
			1 1 1 0 0" />
	</filter>
	<filter id="opaque" x="0%" y="0%" width="100%" height="100%">
		<feColorMatrix type="matrix" values="1.0 0 0 0 -.3,
			1.0 0 0 0 -.3,
			0 0 0 1 0,
			0 0 0 1 0" />
	</filter>
</svg>

<style media="screen">
	.cover--image, .card--image {
		-webkit-filter: url(#alpha2);
		filter: url(#alpha2);
	}
</style>
</body>
</html>