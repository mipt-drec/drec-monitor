{
	var data = data_modules["photo"];
	data_modules["photo"]["show_photo"] =  function() {
		clearInterval(data_modules["photo"]["interval1"]);
		$.ajax({
			type: "GET",
			url: data_modules["photo"]["path"] + "res/data.php",
			cache: false,
			error: function() {
				console.log("connection error");
			},
			success: function(data) {
				try {
					data = JSON.parse(data);
					// console.log(data);
					var foradd ="";
					for (var i = 0; i < data["number"]; i++) {
						foradd += "<div class='item " + ((i == 0) ? "active" : "") + "' data-type='" + data[i]['type'] + "'>";
						if (data[i]['type'] == "photo") {
							foradd += "<img src='" + data_modules["photo"]["path"] + "res/upload/" + 
								data[i]['source'] + "' alt='' class='photo-img'>";
							if (data[i]['title']) {
								foradd += "<div class='carousel-caption'>" + data[i]['title'] + "</div>";
							}
						}
						foradd += "</div>";
						// if (data[i]['type'] == "video") {

						// }
			// 
			//   <div class="video-iframe">
			//     <video preload>
			//       <source src="video/test.webm" type="video/webm;"></source>
			//       <source src="video/test.mov" type="video/mp4"></source>
			//     </video>
			//   </div>
			//   <div class="carousel-caption">
			//     Test self video
			//   </div>
			// </div>
					}
					$("#photo .carousel-inner").html(foradd);
					data_modules["photo"]["interval1"] = setInterval(function () {
						data_modules["photo"]["change"]();
					}, 15000);
				} catch (e) {
					console.log("photo print error");
					console.log(e);
				}
			}
		});
	};

	data_modules["photo"]["change"] = function () {
		$items = $("#photo .item");
		if ($items.length > 1) {
			$curr = $items.filter(".active");
			$curr.next().addClass("active");
			$curr.removeClass("active");
			$("#photo .carousel-inner").append($curr);
			/*$("#photo .carousel-inner").animate({
				top: -$curr.outerHeight(),
			}, 2000, function () {
				$curr.removeClass("active");
				$("#photo .carousel-inner").css("top", 0);
				// $curr.css("opacity", 0);
				$("#photo .carousel-inner").append($curr);
				// $curr.css("top", "auto");
				// $curr.css("opacity", 1);
			});*/
		}
	};

	$("#photo_css").remove();
	$("head").append($("<link rel='stylesheet' href='" + data["path"] + "res/style.css?v=4' type='text/css' id='photo_css' />"));
	$("#photo").remove();
	$("body").append("<div id='photo'><img class='loading' src='img/loading.gif'></div>");
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
				$("#photo").html(data["text"]);
				data_modules["photo"]["show_photo"]();
				if (data_modules["photo"]["interval"]){
					clearInterval(data_modules["photo"]["interval"]);
				}
				data_modules["photo"]["interval"] = setInterval(function() {
					data_modules["photo"]["show_photo"]();
				}, 600000);
			} catch (e) {
				console.log("photo print error");
				console.log(e);
			}
		}
	});
}