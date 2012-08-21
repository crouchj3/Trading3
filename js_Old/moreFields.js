/*
	<div id="readroot" style="display:none">
			<input name="member[]" class="required" size="20" onfocus="actb(this,event,ptsmemberarray);" autocomplete="off" minlength="2" />
			<input type="button" value="X" onclick="this.parentNode.parentNode.removeChild(this.parentNode);" />
	</div>
	
	<div class="aboutdiv">
		<form method="post" action="" name="validateform" id="validateform">
			List Names&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="button" id="moreFields" onClick="init()" value="Add Field" />
			<br />	
				
			<span id="writeroot"></span>
				<input type="submit" name="button" value="Submit" />
		</form>
	</div>
*/


var counter = 0;

function moreFields() {
	counter++;
	var newFields = document.getElementById('readroot').cloneNode(true);
	newFields.id = '';
	newFields.style.display = 'block';
	var newField = newFields.childNodes;
	for (var i=0;i<newField.length;i++) {
		var theName = newField[i].name
		if (theName)
			newField[i].name = theName + counter;
	}
	var insertHere = document.getElementById('writeroot');
	insertHere.parentNode.insertBefore(newFields,insertHere);
}
function init() {
	moreFields();
}
window.onload = init;