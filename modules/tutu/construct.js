{
	var data = data_modules["tutu"];
	data_modules["tutu"]["print"] = function() {
		$.ajax({
			type: "GET",
			url: data_modules["tutu"]["path"] + "res/data.txt",
			cache: false,
			error: function() {
				console.log("connection error");
			},
			success: function(data) {
				try {
					data = JSON.parse(data);
					for (var i=1; i<=4; i++){
						for (var j=1; j<=4; j++){
							$("#tutu .tutu"+i+" .tutur"+j).html(data["data"][i][j]);
						}
						if (!parseInt(data["data"][i][21])){
							$("#tutu .tutu"+i+" .tutur21").hide();
						}else{
							$("#tutu .tutu"+i+" .tutur21").show();
							$("#tutu .tutu"+i+" .tutur21 span:first-child").html(data["data"][i][21]);
						}
					}
				} catch (e) {
					console.log("tutu print error");
					console.log(e);
				}
			}
		});
	};

	$("#tutu_css").remove();
	$("head").append($("<link rel='stylesheet' href='" + data["path"] + "res/style.css?v=1' type='text/css' id='tutu_css' />"));
	$("#tutu").remove();
	$("body").append("<div id='tutu'><img class='loading' src='img/loading.gif'></div>");
	$.ajax({
		type: "GET",
		url: data["path"] + "res/text.php",
		cache: false,
		error: function() {
			console.log("connection error");
		},
		success: function(data) {
			try {
				data = JSON.parse(data);
				$("#tutu").html(data["html"]);
				data_modules["tutu"]["print"]();
				if (data_modules["tutu"]["interval"]){
					clearInterval(data_modules["tutu"]["interval"]);
				}
				data_modules["tutu"]["interval"] =setInterval(function() {
					data_modules["tutu"]["print"]();
				}, 31000);
			} catch (e) {
				console.log("tutu print error");
				console.log(e);
			}
		}
	});
}