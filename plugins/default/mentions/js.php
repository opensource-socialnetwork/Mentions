//<script>
$(document).ready(function(){
	if(typeof $.fn.atwho !== 'undefined'){					   
		$('.comment-box').atwho({
 	   		at: "@",
			limit:100,
		    searchKey: "name",
 	  		insertTpl: '<span class="mentions">@${username}</span>',
 	   		displayTpl: "<li><img src='${imageurl}' height='20' width='20'/> ${name}</li>",	
 	   		data: Ossn.site_url+"mentions_picker",
		});
	}
});