<h2 class="title">Game List</h2>
<table>
	<tr>
		<td>Name</td>
		<td>Started</td>
		<td>Protected</td>
	</tr>
<?php
foreach($list as $value) {
	$id = $value['id'];
	$name = $value['name'];
	$started = ($value['started'] == 1) ? "Y" : "N";
	$protected = ($value['public_game'] == '1') ? 'N' : 'Y';

	echo "<tr>";
	echo "<td><a href=\"info/$id\">$name</a></td>";
	echo "<td>$started</td>";
	echo "<td>$protected</td>";
	echo "</tr>";
}
?>
</table>