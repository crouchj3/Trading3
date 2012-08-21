<h2 class="title">
<?php
	echo $gameName;
?>
</h2>

<form method="post" action="">
	<input type="submit" name="submit" value="<?php echo ($isPlayerInGame) ? 'Leave' : 'Join'; ?>" />
	<input type="submit" name="submit" value="Delete" />
</form>

<table>
<?php
foreach($gameInfo as $key => $info) {
	echo $key." - ".$info."<br />";
}
?>
</table>