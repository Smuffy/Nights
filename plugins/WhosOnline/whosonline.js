$(document).ready(function() {
	var Url;
	function GetOnline() {
		var webRoot = definition('WebRoot', '');
		var url =  combinePaths(webRoot, '/garden/plugin/imonline');
		$.ajax({
			url: url,
			global: false,
			type: "GET",
			data: null,
			dataType: "html",
			success: function(Data){
				$("#WhosOnline").replaceWith(Data);
				setTimeout(GetOnline, definition('WhosOnlineFrequency') * 1000);
			}
		});
	}

	GetOnline();
});


