<h2 class="title">Site Settings</h2>

<form action="" method="post">
	<?php	
		foreach($settings as $value) {
			echo $value."<br />";
		}
	?>
	<input type="submit" name="submit" value="Update" />
</form>