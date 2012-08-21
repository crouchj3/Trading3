<form method="POST" action="">
	<input type="number" name="rate" value="<?php echo $rate; ?>" />
	<input type="submit" name="submit" value="Refresh Rate" />
</form>
<?php
echo $updatehistories."<br />";
?>
<script>
	function doLoad() {
		setTimeout("refresh()",<?php echo $rate; ?>);
	}
	function refresh() {
		window.location.reload(true);
	}
	document.write('<b>'+(new Date).toLocaleString() + '</b><br />');
	doLoad();
</script>
