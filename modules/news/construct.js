{
	var data = data_modules["news"];
	var addFlag = true;
	data_modules["news"]["print"] = function() {
		clearInterval(data_modules["news"]["interval1"]);
		var month = {
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
		};
		$.ajax({
			type: "GET",
			url: data_modules["news"]["path"] + "res/data.php",
			cache: false,
			error: function() {
				console.log("connection error");
			},
			success: function(data) {
				try {
					data = JSON.parse(data);
					var foradd = "";
					for (var i = 0; i < data["number"]; i++) {
						foradd += "<tr><th><span class='news-date'>" + data[i]['day'] + "</span>" + 
							"<span class='news-month'>" + month[parseInt(data[i]['month']) - 1] + "</span></th>" + 
							"<td><p class='news-head'>" + data[i]['title'] + "</p>" + 
							"<p class='news-text'>"+data[i]['text']+"</p></td></tr>";
					}
					$("#news tbody").html(foradd);
					addFlag = true;
					data_modules["news"]["interval1"] = setInterval(function () {
						data_modules["news"]["change"]();
					}, 80);
				} catch (e) {
					console.log("news print error");
					console.log(e);
				}
			}
		});
	};
	data_modules["news"]["change"] = function() {
		/*if ($(".news > div").length > 1){
			$(".news > div:first-child").addClass("hiding");
			setTimeout(function (){
				var tmp = $(".news > div:first-child").html();
				$(".news > div:first-child").remove();
				$(".news").append("<div>"+tmp+"</div>");
			}, 1000);
		}*/
		try {
			$('#news').scrollTop($('#news').scrollTop() + 1);
			var $curr = $('#news').find('tr:first-child');
			if ($curr) {
				var $newtr = $curr.clone();
				var offset = $curr.offset().top;
				var height = $curr.outerHeight();
				if (offset < 0 && addFlag) {
					$('#news').find('tbody').append($newtr);
					addFlag = false;
				}
				if ((offset + height) < 0) {
					$curr.remove();
					$('#news').scrollTop(0);
					addFlag = true;
				}
			}
		} catch (e) {
			console.log("news print error");
			console.log(e);
		}
	};

	$("#news_css").remove();
	$("head").append($("<link rel='stylesheet' href='" + data["path"] + "res/style.css?v=3' type='text/css' id='news_css' />"));
	$("#news").remove();
	$("body").append("<div id='news'></div>");
	$("#news").html("<table id='news-table'><tbody></tbody></table>");
	$("#news-table").prepend("<colgroup><col width='20%'></colgroup>");
	// $("body").append("<div class='news'><img class='loading' src='img/loading.gif'></div>");
	data_modules["news"]["print"]();
	
	if (data_modules["news"]["interval"]){
		clearInterval(data_modules["news"]["interval"]);
	}
	data_modules["news"]["interval"] = setInterval(function(){data_modules["news"]["print"]()}, 600000);
	
	/*if (data_modules["news"]["interval1"]){
		clearInterval(data_modules["news"]["interval1"]);
	}
	setTimeout( function () {
		data_modules["news"]["interval1"] = setInterval(function () {
			data_modules["news"]["change"]();
		}, 50); //10000);
	}, 1000);*/
}