$(document).ready(function () {

	var delPhoto = (function () {

		var $divPhoto = $("#edit-photo"),
				$form = $("#form-photo");

		var _init = function () {
			_eventListener();
		};

		var _eventListener = function () {
			$("#edit-photo img").on("click", function () {
				_changeState(this);
			});

			$form.on("reset", function() {
				_resetForm();
			})
		};

		var _changeState = function (element) {
			var id = $(element).attr("id");
			var $curr = $form.find("#p-" + $(element).attr("id"));
			if ($curr.length == 0) {
				$(element).parent().addClass("delete-this");
				$form.append("<input class='delimg' type='hidden' name='" + id + "' value='del' id='p-" + id + "'>");
			} else {
				$(element).parent().removeClass("delete-this");
				$curr.remove();
			}
		};

		var _resetForm = function () {
			console.log("reset");
			$form.find(".delimg").remove();
			$divPhoto.find(".delete-this").removeClass("delete-this");
		};

		return {
			init: _init,
		}
	}());

	delPhoto.init();
});