<form method="post" action="">
	<label for="displayname">Display Name: </label>
	<input type="text" id="displayname" name="display_name" value="<?php echo $_POST['display_name']; ?>" />
	<br />
	<label for="email">Email: </label>
	<input type="text" id="email" name="email" value="<?php echo $_POST['email']; ?>" />
	<br />
	<label for="pwd1">Password: </label>
	<input type="password" id="pwd1" name="pwd1" />
	<br />
	<label for="pwd2">Retype Your Password: </label>
	<input type="password" id="pwd2" name="pwd2" />
	<br />
	<input type="submit" name="submit" value="Register" />
</form>