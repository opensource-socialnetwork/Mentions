//<script>
$(document).ready(function(){
	if(typeof $.fn.atwho !== 'undefined'){					   
		$('.comment-box').atwho({
 	   		at: "@",
 	  		insertTpl: '<span class="mentions">@${username}</span>',
 	   		displayTpl: "<li><img src='${imageurl}' height='20' width='20'/> ${first_name} ${last_name}</li>",	
 	   		data: Ossn.site_url+"mentions_picker",
		});
	}
});