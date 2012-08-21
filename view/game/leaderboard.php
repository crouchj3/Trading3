
Leaderboard shall be updated on the hour (when site reaches final stages of production).
<table>
	<thead>
		<tr>
			<td>Rank</td>
			<td>Name</td>
			<td>Portfolio Value</td>
		</tr>
	</thead>
	<tbody id="board">
		<?php
		foreach($rankings as $rank) {
			echo "<tr><td>{$rank[rank]}</td><td>{$rank[name]}</td><td>$".number_format($rank[balance],2,'.',',')."</td></tr>";
		}
		?>
	</tbody>
</table>
	
<br />