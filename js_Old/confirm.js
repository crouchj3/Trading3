//<input type="submit" onclick="return confirmSubmit()" name="button" value="Delete" />
function confirmSubmit()
{
var agree=confirm("Confirm delete?");
if (agree)
return true ;
else
return false ;
}
