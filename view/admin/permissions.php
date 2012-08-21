<form method="post" action="">
	<h2 class="title">Groups</h2>
	<label for="group">Group Name: </label>
    <input type="text" name="group" id="group" />
    <br />
    <input type="submit" name="submit" value="Create" />
</form>

<form method="post" action="">
	<h2 class="title">Permissions</h2>
	<label for="fullname">Long Name: </label>
	<input type="text" name="fullname" id="fullname" />
	<br />
	<label for="name">Name: </label>
	<input type="text" name="name" id="name" />
	<br />
	<input type="submit" name="submit" value="Add" />
</form>

<table>
	<tr>
    	<td>Permission:</td>
    	<?php foreach($groupname as $key => $value) { ?>
        	<td>
        		<?php echo $value; ?>
        		<a href="/s/admin/perm/cmd/groupDelete/<?php echo $key; ?>">[X]</a>
        		<!-- Group Delete -->
        	</td>
        <?php } ?>
    </tr>
    
	<?php
        foreach($permname as $pkey => $pvalue) {
    ?>
            <tr>
            	<td>
            		<?php echo $pvalue; ?>
            		<a href="/s/admin/perm/cmd/permDelete/<?php echo $pkey; ?>">[X]</a>
            		<!-- Permission Delete -->
            	</td>
            	
				<?php
					foreach($groupname as $gkey => $gvalue) {
				?>
                        <td>
                        	<?php if($pvalue[0] != "_") { ?>
	                            <input type="checkbox" 
	                                <?php if($groupperm[$gkey][$pkey]) echo 'checked="checked"'; ?> 
	                                onchange="$.post('',
	                                {
	                                cmd: 'change',
	                                id: '<?php echo $gkey.'_'.$pkey; ?>'
	                                } ,
	                                function(data){ 
	                                	msgAction(data);
	                                });"
	                            />
                            <?php
								} else {
									echo "&nbsp;";
								}
							?>
                        </td>
                <?php
					}
				?>
            </tr>	
    <?php	
        }				
    ?>
</table>