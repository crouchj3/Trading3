<h2 class="title">Create Game</h2>
<form method="post" action="">
	<table class="center">
		<tr>
			<td style="width:300px;">Name:</td>
			<td>
			<input type="text" name="name" value="<?php echo $_POST['name'];?>" />
			</td>
		</tr>
		<tr>
			<td>Description:</td>
			<td>
			<input type="text" name="descript" value="<?php echo $_POST['description'];?>" />
			</td>
		</tr>
		<tr>
			<td>Start Date:</td>
			<td>
			<input type="date" name="start_date" value="<?php echo ($_POST['start_date']) ? $_POST['start_date'] : date("Y-m-d", strtotime("+1 day"));?>" />
			</td>
		</tr>
		<tr>
			<td>End Date:</td>
			<td>
			<input type="date" name="end_date" value="<?php echo ($_POST['end_date']) ? $_POST['end_date'] : date("Y-m-d", strtotime("+1 year"));?>" />
			</td>
		</tr>
		<tr>
			<td>Joinable after start of game?</td>
			<td>
			<input type="radio" name="joinable_post_start" value="0" <?php echo (!isset($_POST['joinable_post_start']) || $_POST['joinable_post_start'] != 1) ? 'checked="checked"' : ''; ?> />
			No
			<input type="radio" name="joinable_post_start" value="1" <?php echo (isset($_POST['joinable_post_start']) && $_POST['joinable_post_start'] == 1) ? 'checked="checked"' : ''; ?> />
			Yes </td>
		</tr>
		<tr>
			<td>Entry Fee ($):</td>
			<td>
				<input type="text" name="entry_fee" value="<?php echo ($_POST['entry_fee']) ? $_POST['entry_fee'] : 0;?>" />
			</td>
		</tr>
		<tr>
			<td>Game Visibility:</td>
			<td>
			<input type="radio" name="public_game" value="1" <?php echo (!isset($_POST['public_game']) || $_POST['public_game'] != 0) ? 'checked="checked"' : ''; ?> />
			Public 
			<input type="radio" name="public_game" value="0" <?php echo (isset($_POST['public_game']) && $_POST['public_game'] == 0) ? 'checked="checked"' : ''; ?> />
			Private
			</td>
		</tr>
		<tr>
			<td>Password:</td>
			<td>
			<input type="text" name="password" />
			</td>
		</tr>
		<tr>
			<td>Start Balance ($):</td>
			<td>
			<input type="text" name="start_balance" value="<?php echo (isset($_POST['start_balance'])) ? $_POST['start_balance'] : 100000; ?>" />
			</td>
		</tr>
		<tr>
			<td>Commission ($):</td>
			<td>
			<input type="text" name="commission" value="<?php echo (isset($_POST['commission'])) ? $_POST['commission'] : '0'; ?>" />
			</td>
		</tr>
		<tr>
			<td>Max Percent of Stock Value As Portfolio Value (%):</td>
			<td>
			<input type="text" name="max_portfolio_percent" value="<?php echo (isset($_POST['max_portfolio_percent'])) ? $_POST['max_portfolio_percent'] : '100'; ?>" />
			</td>
		</tr>
		<tr>
			<td>Short Selling?</td>
			<td>
			<input type="radio" name="short_sell" value="0" <?php echo (isset($_POST['short_sell']) && $_POST['short_sell'] == 0) ? 'checked="checked"' : ''; ?> />
			No
			<input type="radio" name="short_sell" value="1" <?php echo (!isset($_POST['short_sell']) || $_POST['short_sell'] != 0) ? 'checked="checked"' : ''; ?> />
			Yes</td>
		</tr>
		<tr>
			<td>Limit Orders?</td>
			<td>
			<input type="radio" name="limit_order" value="0" <?php echo (!isset($_POST['limit_order']) || $_POST['limit_order'] != 1) ? 'checked="checked"' : ''; ?> />
			No
			<input type="radio" name="limit_order" value="1" <?php echo (isset($_POST['limit_order']) && $_POST['limit_order'] == 1) ? 'checked="checked"' : ''; ?> />
			Yes </td>
		</tr>
		<tr>
			<td>Stop Loss?</td>
			<td>
			<input type="radio" name="stop_loss" value="0" <?php echo (!isset($_POST['stop_loss']) || $_POST['stop_loss'] != 1) ? 'checked="checked"' : ''; ?> />
			No
			<input type="radio" name="stop_loss" value="1" <?php echo (isset($_POST['stop_loss']) && $_POST['stop_loss'] == 1) ? 'checked="checked"' : ''; ?> />
			Yes </td>
		</tr>
		<tr>
			<td colspan="2" style="text-align:center;">
			<input type="submit" name="submit" value="Create" />
			<button onclick="history.go(-1);">
				Cancel
			</button></td>
		</tr>
	</table>
</form>