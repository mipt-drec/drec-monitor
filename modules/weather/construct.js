{
	var data = data_modules["weather"];
	data_modules["weather"]["print"] = function() {
		$.ajax({
			type: "GET",
			url: data_modules["weather"]["path"] + "res/data.txt",
			cache: false,
			error: function() {
				console.log("connection error");
			},
			success: function(data) {
				try {
					data = JSON.parse(data);
					curr_temp = Math.round(parseFloat(data["temp"].replace(",", ".")));
					$("#curr-temp").html(curr_temp);
					$("#curr-weather-icon").remove();
					if (data["ico"].length > 4) {
						$("#weather").append("<img id='curr-weather-icon' src='"+data_modules["weather"]["path"] + "res/weather/" + data["ico"] + "' alt='' >");
					}
					// $(".weather .wind span").html(data["wind"]);
					// $(".weather .water span").html(data["water"]);
				} catch (e) {
					console.log("print error");
					console.log(e);
				}
			}
		});
	};

	$("#weather_css").remove();
	$("head").append($("<link rel='stylesheet' href='" + data["path"] + "res/style.css?v=2' type='text/css' id='weather_css' />"));
	$("#weather").remove();
	// $("body").append("<div class='weather'><img class='loading' src='img/loading.gif'></div>");
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
				$("#footer").append(data["html"]);
				data_modules["weather"]["print"]();
				if (data_modules["weather"]["interval"]){
					clearInterval(data_modules["weather"]["interval"]);
				}
				data_modules["weather"]["interval"] = setInterval(function() {
					data_modules["weather"]["print"]();
				}, 90000);
			} catch (e) {
				console.log("print error");
				console.log(e);
			}
		}
	});
}