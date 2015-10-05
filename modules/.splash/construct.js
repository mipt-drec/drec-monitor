{
	var data = data_modules["splash"];
	data_modules["splash"]["print"] = function() {
		$.ajax({
			type: "GET",
			url: data_modules["splash"]["path"] + "res/data.php",
			cache: false,
			error: function() {
				console.log("connection error");
			},
			success: function(data) {
				try {
					data = JSON.parse(data);
					if (data.enabled){
						$("body > .splash").html("").css("background-image", "url("+data_modules["splash"]["path"]+"res/img.jpg?v="+data.hash+")").show();
					}else{
						$("body > .splash").hide();
					}
				} catch (e) {
					console.log("print error");
					console.log(e);
				}
			}
		});
	};

	$("#splash_css").remove();
	$("head").append($("<link rel='stylesheet' href='" + data["path"] + "res/style.css?v=3' type='text/css' id='splash_css' />"));
	$("body > .splash").remove();
	$("body").append("<div class='splash'><img class='loading' src='img/loading.gif'></div>");
	data_modules["splash"]["print"]();
	
	if (data_modules["splash"]["interval"]){
		clearInterval(data_modules["splash"]["interval"]);
	}
	data_modules["splash"]["interval"] = setInterval(function(){data_modules["splash"]["print"]()}, 60000);
	data_modules["splash"]["print"]();
}