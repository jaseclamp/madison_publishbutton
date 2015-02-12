
function getUrlParameter(sParam)
{
    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i++) 
    {
        var sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] == sParam) 
        {
            return sParameterName[1];
        }
    }
}          

if($("#rulefields select.channel").val() > 0)
{
	
	var button = '<span class="button"><a href="/admin.php?/cp/content_publish/entry_form&channel_id=' + 
	$("#rulefields select.channel").val()
	+'&S='+
	getUrlParameter('S')
	+'" class="submit" title="Publish"><i class="icon-plus-sign"></i> Create New Entry</a></span>';
	
	
	$(button).insertAfter( $("div.rightNav span:last") );
}

//this puts a clone button after delete button. 
//delete button is added by JS itself so we have to wait for that to execute before we can add the clone. 
if(/entry_form/.test(document.location.toString())){
	
	if(/clone=y/.test(document.location.toString())){
	
		//if under clone then remove entry_id etc
		$("input[name=\'entry_id\']").val("");
		EE.publish.which="new";
		$(".contents .heading").html("<h2>" + "Duplicated Entry" + "</h2>");		
		if (typeof EE.publish.autosave != "undefined" ) {
			delete EE.publish.autosave;
		};
		$("input[name=\'nsm_better_meta__meta[0][id]\']").val("");	
		
	}else{
	
		var button = '<li class="button">' +
		'<form id="cloner" action="'+document.location.toString()+'&clone=y" method="get">' +
		'<input type="submit" value="Duplicate" class="submit submit_alt">' + 
		'</form>' +
		'</li>';
		
		setTimeout( function(){ 
			//console.info( $("div.heading ul#action_nav li:last").length ); 
			$(button).insertAfter( $("div.heading ul#action_nav li:last") );
			
			$(document).on( 'submit', '#cloner',  function (e) {
				e.preventDefault();
				window.location.href = $('#cloner').attr('action');
			});
			
		}, 3000);
	
	}
	
}
