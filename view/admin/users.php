<!--
<script type="text/javascript" src="/js/actb.js"></script>
<script type="text/javascript" src="/js/membersuggestdbusers.php"></script>
-->
<h2 class="title">User Manager</h2>
Total site users: <?php echo $numUserSite;?><br />
Total overall users: <?php echo $numUserOverall;?><br />

<form method="post" action="">
	<label for="search">Search</label>
    <input type="text" name="search" id="search" />
    <br />
    <label for="group">Group</label>
    <input type="text" name="group" id="group" />
    <br />
    <input type="submit" name="submit" value="Search" />
</form>

<?php if($users['count'] > 0) { ?>
	<form method="post" action="">
		<input type="hidden" name="pagesize" value="<?php echo $_POST['pagesize']; ?>" />
		
		<label for="page">Page: </label>
		<select name="page" id="page">
			<?php
				$count = $users['count'] / $_POST['pagesize'] + 1;
				for($i = 1; $i <= $count; $i++) {
					echo "<option value=\"".$i."\" ";
					if($i == $_POST['pagesize']) {
						echo "selected=\"selected\"";
					}
					echo ">".$i."</option>";
				}
			?>
		</select>
		
		<br />
		<label for="pagesize">Results Per Page: </label>
		<select name="pagesize" id="pagesize">
    	<?php
    		$size = array("20","50","100","200");
			foreach($size as $value) {
				echo "<option value=\"".$value."\" ";
				if($value == $_POST['pagesize']) {
					echo "selected=\"selected\"";
				}
				echo ">".$value."</option>";
			}
    	?>
    </select>
    <br />
    <input type="submit" name="submit" value="Search" />
	</form>

	<form method="post" action="">
	    <table class="datatable">
	        <tr> 
				<td>&nbsp;</td>
	            <td>ID</td>
	            <td>Display Name</td>
	            <td>Email</td>
	            <td>Group</td>
	            <td>Date</td>
	            <td>&nbsp;</td>
	        </tr>
	        <?php if($users['count'] > 0) foreach($users['list'] as $rs) {?>
	        <tr> 
	            <td><input name="u[]" id="u[]" type="checkbox" value="<?php echo $rs['id']; ?>" /></td>
	            <td><?php echo $rs['id']; ?></td>
	            <td><?php echo $rs['display_name']; ?></td>
	            <td><?php echo $rs['user_email']; ?></td>
	            <td><?php echo $rs['group']; ?></td>
	            <td><?php echo $rs['date']; ?></td>
	            <td>
	                <a href="javascript:void(0);" onclick='$("#edit<?php echo $rs['id'];?>").show("fast");'>Edit</a> 
	            </td>
	        </tr>
	        <tr> 
	            <td colspan="7">
	                <div style="display:none;" id="edit<?php echo $rs['id']; ?>">
	                    <input type="hidden" name="id<?php echo $rs['id']; ?>" id="id<?php echo $rs['id']; ?>" value="<?php echo $rs['id']; ?>" />
	                    Name: <input type="text" name="full_name<?php echo $rs['id']; ?>" id="display_name<?php echo $rs['id']; ?>" value="<?php echo $rs['display_name']; ?>" />
	                    Email: <input type="text" name="user_email<?php echo $rs['id']; ?>" id="user_email<?php echo $rs['id']; ?>" value="<?php echo $rs['user_email']; ?>" />
	                    Group: <select name="group<?php echo $rs['id']; ?>" id="group<?php echo $rs['id']; ?>">
				                    <?php foreach($groupnames as $group) { ?>
				                    	<option value="<?php echo $group['group']; ?>" <?php echo ($group['group'] == $rs['group']) ? "selected='selected'" : "" ?>><?php echo $group['group']; ?></option>
									<?php } ?>
	                    		</select>
	                    New Password: <input type="text" name="pass<?php echo $rs['id']; ?>" id="pass<?php echo $rs['id']; ?>" value="" > (leave blank)
	                    <br />
	                    <input name="doSave" type="button" id="doSave" value="Save" 
	                    onclick='$.post("",
	                    { 
		                    cmd: "edit", 
		                    pass:$("input#pass<?php echo $rs["id"]; ?>").val(),
		                    group:$("select#group<?php echo $rs["id"]; ?>").val(),
		                    display_name:$("input#display_name<?php echo $rs["id"]; ?>").val(),
		                    user_email:$("input#user_email<?php echo $rs["id"]; ?>").val(),
		                    id: $("input#id<?php echo $rs["id"]; ?>").val() 
	                    } ,function(data){ msgAction(data); });' /> 
	                    <a onclick='$("#edit<?php echo $rs['id'];?>").hide("fast");' href="javascript:void(0);">close</a>
	                </div>
	            </td>
	        </tr>
	        <?php } ?>
	    </table>
	    
	    <input type="submit" name="submit" value="Delete" />
	</form>
<?php } else if($users['count'] === 0){ ?>
	No users found
<?php } ?>