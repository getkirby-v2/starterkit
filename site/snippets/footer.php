<footer role="contentinfo">
	<?= $site->copyright()->kirbytext(); ?>
	<p><a href="#" onclick="showPre(); return false;">show</a>/<a href="#" onclick="hidePre(); return false;">hide</a> dev notes</p>
</footer>

<script type="text/javascript">
	function showPre(){
		var pre = document.getElementsByTagName('pre');
		for (var i = 0; i < pre.length; i++) {
			pre[i].style.display = '';
		}
	}
	function hidePre(){
		var pre = document.getElementsByTagName('pre');
		for (var i = 0; i < pre.length; i++) {
			pre[i].style.display = 'none';
		}
	}
	hidePre();
</script>

</body>
</html>