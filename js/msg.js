function msgAction($content) {
	$("#msg").stop(false,true);
	$("#msg").html($content);
	$("#msg").show();
	$("#msg").delay(1500);
	$("#msg").fadeOut(500);
}
function msgShow(div) {
	$("#"+div).stop(false,true);
	$("#"+div).show();
	$("#"+div).delay(3000);
	$("#"+div).fadeOut(1000);
}
function msgHide(div) {
	$("#"+div).hide();
}