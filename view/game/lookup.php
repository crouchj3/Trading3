<form method="post" action="">
	Ticker: <input type="text" name="ticker" /><br />
	<input type="submit" name="submit" value="Get" />
</form>

<?php if(is_array($stocks)) { ?>
	<form method="post" action="">
	<table style="width:700px;">
		<tr>
			<td>Symbol</td>
			<td>Price</td>
		</tr>
	<?php $i = 0;foreach($stocks as $key => $value) { ?>
		<tr>
			<td><?php echo $key; ?></td>
			<td><?php echo $value; ?></td>
			<td><?php if($loggedIn && !empty($playercomps) && $value != "Invalid Ticker") { ?><a href="javascript:void(0);" onclick='$("#trade<?php echo $i; ?>").show("fast");'>Trade</a><?php } ?></td>
		</tr>
		<tr>
			<td colspan="3">
				<?php if($loggedIn && !empty($playercomps) && $value != "Invalid Ticker") { ?>
				<div style="display:none;" id="trade<?php echo $i; ?>">
					<label for="symbol<?php echo $i; ?>">Symbol:</label><input type="text" id="symbol<?php echo $i; ?>" name="symbol<?php echo $i; ?>" value="<?php echo $key; ?>" />
					<label for="shares<?php echo $i; ?>">Shares:</label><input type="number" id="shares<?php echo $i; ?>" name="shares<?php echo $i; ?>" value="1" />
					<label for="type<?php echo $i; ?>">Type:</label>
						<select name="type<?php echo $i; ?>" id="type<?php echo $i; ?>">
							<option value="buy">Buy</option>
							<option value="sell">Sell</option>
						</select>
						<select name="order<?php echo $i; ?>" id="order<?php echo $i; ?>">
							<option value="market">Market Order</option>
							<option value="limit">Limit</option>
							<option value="stop">Stop</option>
						</select>
					<br />
					<label for="price<?php echo $i; ?>">Price (for limit & stop orders)</label>
						<input type="number" name="price<?php echo $i; ?>" id="price<?php echo $i; ?>" />
					<input type="button" name="trade" id="trade" value="Trade"
						onclick='$.post("",
	                    { 
	                    cmd: "trade", 
	                    symbol:$("input#symbol<?php echo $i; ?>").val(),
	                    shares:$("input#shares<?php echo $i; ?>").val(),
	                    type:$("select#type<?php echo $i; ?>").val(),
	                    order:$("select#order<?php echo $i; ?>").val(),
	                    price:$("input#price<?php echo $i; ?>").val(),
	                    gameid:<?php echo $gameId; ?>
	                    } ,function(data){ msgAction(data); });'
	               />
	               <a href="javascript:void(0);" onclick='$("#trade<?php echo $i; ?>").hide("fast");'>Close</a>
				</div>
				<?php } ?>
			</td>
		</tr>
	<?php $i++;} ?>
	</table>
	</form>
<?php } ?>
