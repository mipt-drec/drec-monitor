{
	
	clearInterval(data_modules["cron"]["interval"]);
	data_modules["cron"]["interval"] = setInterval(function() {
		$.ajax({
			type: "GET",
			url: "cron.php",
			cache: false,
			error: function() {
				console.log("connection error");
			},
			success: function(data) {
				
			}
		});
	}, 30000);

}