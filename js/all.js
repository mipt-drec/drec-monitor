var modules = {},
data_modules = {};
$(document).ready(function(){
	setInterval(function(){connect();}, 30000);
	connect();
});
function connect(){
	$.ajax({
			type: "GET",
			url: "api.php?v="+Math.random(),
			cache: false,
			error: function() {
				console.log("connection error");
			},
			success: function(data) {
				try {
					data = JSON.parse(data);
					for (var i = 0; i < data["number"]; i++){
						if (modules[data[i]["name"]] != data[i]["version"]){
							modules[data[i]["name"]] = data[i]["version"];
							if (!data_modules[data[i]["name"]]){
								data_modules[data[i]["name"]] = {};
							}
							data_modules[data[i]["name"]]["path"] = "modules/"+data[i]["name"]+"/";
							$.ajax({
								type: "GET",
								url: data_modules[data[i]["name"]]["path"] + "construct.js",
								cache: false,
								error: function(ee, e) {
									console.log("connection error");
									console.log(e);
								},
								success: function(data) {}
							});
						}
					}
				}catch(e){
					console.log("json error");
					console.log(e);
				}
			}
		});
}