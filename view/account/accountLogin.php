<form method="post" action="">
	<label for="email">Email:</label>
	<input type="text" id="email" name="email" value="<?php echo $_POST['email']; ?>" />
	<br />
	<label for="pwd" for="pwd">Password: </label>
	<input type="password" id="pwd" name="pwd" />
	<br />
	<input type="submit" name="submit" value="Log In"></input>
</form>