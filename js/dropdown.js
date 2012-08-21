function change(id){
	ID = $("#" + id);
	display = ID.css("display");

	if(display == "none")
		ID.show();
	else
		ID.hide();
}