<!-- $errorMsg -->
<div class="msg" id="msg">
	<?php
	if(isset($msg)) {
		foreach($msg as $value) {
			echo $value."<br />";
		}
	}
	?>
</div>
<div class="msg" id="errorMsg">
	<?php
	if(isset($errorMsg)) {
		foreach($errorMsg as $value) {
			echo $value."<br />";
		}
	}
	?>
</div>
<?php if(isset($msg)) { ?>
	<script>msgShow('msg');</script>
<?php } else { ?>
	<script>msgHide('msg');</script>
<?php } ?>
<?php if(isset($errorMsg)) { ?>
	<script>msgShow('errorMsg');</script>
<?php } else { ?>
	<script>msgHide('errorMsg');</script>
<?php } ?>