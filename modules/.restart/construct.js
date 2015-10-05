$.ajax({
	type: "GET",
	url: data_modules["restart"]["path"] + "res/data.php",
	cache: false,
	error: function() {
		console.log("connection error");
	},
	success: function(data) {
		location.reload();
	}
});