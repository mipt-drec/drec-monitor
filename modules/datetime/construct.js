{
	data_modules["datetime"]["print_time"] = function() {
		var date = new Date(),
				tmp,
				month = {
			0: "января",
			1: "февраля",
			2: "марта",
			3: "апреля",
			4: "мая",
			5: "июня",
			6: "июля",
			7: "августа",
			8: "сентября",
			9: "октября",
			10: "ноября",
			11: "декабря"
		},
		week = {
			0: "Вс",
			1: "Пн",
			2: "Вт",
			3: "Ср",
			4: "Чт",
			5: "Пт",
			6: "Сб",
			7: "Вс"
		};
		date = new Date(date.getTime() - data_modules["datetime"]["delta_time"]);
		tmp = /*week[date.getDay()] + ', */'' + date.getDate() + ' ' + month[date.getMonth()];
		$('#curr-date').html(tmp);
		tmp = ('0' + date.getHours()).slice(-2) + ':' + ('0' + date.getMinutes()).slice(-2); // + ':' + ((date.getSeconds() < 10) ? '0' : ') + date.getSeconds();
		$('#curr-time').text(tmp);
	};
	data_modules["datetime"]["delta_time"] = 0;
	var data = data_modules["datetime"];

	$("#datetime_css").remove();
	$("head").append($("<link rel='stylesheet' href='" + data["path"] + "res/style.css?v=1' type='text/css' id='datetime_css' />"));
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
				data_modules["datetime"]["delta_time"] = new Date().getTime() - parseInt(data["unixtime"]) * 1000;
				$("#footer").html(data["text"]);
				data_modules["datetime"]["print_time"]();
				if (data_modules["datetime"]["interval"]){
					clearInterval(data_modules["datetime"]["interval"]);
				}
				data_modules["datetime"]["interval"] = setInterval(function() {
					data_modules["datetime"]["print_time"]();
				}, 1000);
			} catch (e) {
				console.log("datetime print error");
				console.log(e);
			}
		}
	});
}