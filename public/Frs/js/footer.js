$(document).ready(function()
{


	$(document).on("focusin", ".jqui_datepicker", function(){
        $(this).datepicker();
    });

	$(document).on("focusin", ".jqui_timepicker", function(){
        $(this).timepicker();
    });



});

function clearMsg()
{
	$(".msgCtrl").html('');
}


function writeobj(obj)
{
	var description = "";
	for( var i in obj)
	{
		var property = obj[i];
		description += i + " = " + property + "\n";
	}
	alert(description);
}
