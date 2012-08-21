<h2 class="title">Portfolio</h2>
<table>
	<tr>
		<td>Cash</td>
		<td>$<?php echo number_format($balance,2); ?></td>
	</tr>
	<tr>
		<td>Portfolio Value</td>
		<td>$<?php echo number_format($portfolioValue,2); ?></td>
	</tr>
</table>

<table>
	<tr>
		<td>Symbol</td>
		<td>Shares</td>
		<td>Current Price</td>
		<td>Percent Change</td>
		<td>Amount Change</td>
		<td>Value</td>
	</tr>
<?php
	foreach($portfolio as $symbol => $stock) {
		$value = $stock['quant'] * $prices[$symbol];
		$amtchange = $value - ($stock['quant'] * $stock['avgprice']);
		$pctchange = ($amtchange / ($stock['quant'] * $stock['avgprice'])) * 100;
		$amtchange = number_format($amtchange, 2);
		$amtchange = (strpos($amtchange, '-') === FALSE) ? '$'.$amtchange : str_replace('-', '-$', $amtchange);
		echo "<tr>
				<td>$symbol</td>
				<td>".number_format($stock['quant'])."</td>
				<td>$".number_format($prices[$symbol],2)."</td>
				<td>".number_format($pctchange, 2)."%</td>
				<td>".$amtchange."</td>
				<td>$".number_format($value,2)."</td>
			</tr>";
	}
?>
</table>


<h2 class="title">Trade History</h2>
<table>
	<tr>
		<td>Timestamp</td>
		<td>Symbol</td>
		<td>Shares</td>
		<td>Price</td>
		<td>Type</td>
		<td>Status</td>
	</tr>
<?php
	foreach($pending as $ar) {
		$time = date("m/d/Y g:i:s a T", strtotime($ar[timestamp]));
		$price = ($ar[price] == 0) ? "N/A" : $ar[price];
		$status = ucfirst($ar[status]);
		echo "<tr>
				<td>$time</td>
				<td>$ar[ticker]</td>
				<td>$ar[quant]</td>
				<td>$".number_format($price,2)."</td>
				<td>$ar[type]</td>
				<td id=\"row_{$ar[id]}\">$status
			";
?>
					&nbsp;|&nbsp; 
					<a href="javascript:void(0);" onclick='$.post("",
						{
							cmd: "cancel",
							gameid:<?php echo $gameId; ?>,
							playerid: <?php echo $userId; ?>,
							tradeid: <?php echo $ar[id]; ?>
						} ,function(data) {
							$("#row_<?php echo $ar[id]; ?>").text(data);
						});'
					/>
						Cancel
					</a>
<?php
	echo "		</td>
			</tr>";
	}
	foreach($executed as $ar) {	
		$time = date("m/d/Y g:i:s a T", strtotime($ar[timestamp]));
		$price = ($ar[price] == 0) ? "N/A" : $ar[price];
		echo "<tr>
				<td>$time</td>
				<td>$ar[ticker]</td>
				<td>$ar[quant]</td>
				<td>$".number_format($price,2)."</td>
				<td>$ar[type]</td>
				<td>$ar[status]</td>
			</tr>";
	}
?>
</table>

<h2 class="title">Portfolio History</h2>
<table>
	<tr>
		<td>Timestamp</td>
		<td>Value</td>
	</tr>
	<?php
	foreach($history as $row) {
		$time = date("m/d/Y g:i:s a T", strtotime($row[timestamp]));
		echo "<tr>
				<td>$time</td>
				<td>$".number_format($row[value],2)."</td>
			
			</tr>";	
	}
	?>
</table>
