$(document).ready(function () {

	var editNews = (function () {

		var $divPhoto = $("#edit-news"),
				$form = $("#form-news");

		var _init = function () {
			_eventListener();
		};

		var _eventListener = function () {
			$("#edit-news .edit-news").on("click", function () {
				_changeState(this);
			});

			$form.on("reset", function() {
				_resetForm();
			});
		};

		var _changeState = function (element) {
			var id = $(element).attr("id");
			$(element).addClass("delete-this");
			$form.append("<input class='curr-news-id' type='hidden' name='id' value='" + id + "' id='n-" + id + "'>");
			title = $(element).find('.curr-title').html();
			text = $(element).find('.curr-text').html();
			date = $(element).find('.curr-date').text();
			end = $(element).find('.curr-end').text();
			active = $(element).find('.curr-active').text();
			active = (active == "Да") ? true : false;
			$form.find("#title").val(title);
			$form.find("#text").val(text);
			$form.find("#date").val(date);
			$form.find("#end").val(end);
			$form.find("#active").attr("checked", active);
			$form.removeClass('hidden');
			$divPhoto.addClass('hidden');
		};

		var _resetForm = function () {
			$divPhoto.find(".delete-this").removeClass("delete-this");
			$divPhoto.removeClass('hidden');
			$form.addClass('hidden');
			$form.find("#title").val("")
				.attr("disabled", false);
			$form.find("#text").val("")
				.attr("disabled", false);
			$form.find("#date").val("")
				.attr("disabled", false);
			$form.find("#end").val("")
				.attr("disabled", false);
			$form.find("#active").attr("checked", false)
				.attr("disabled", false);
			$form.find("#delete").attr("checked", false);
			$form.find(".curr-news-id").remove();
		};

		var _deleteForm = function (element) {
			if ($(element).prop("checked")) {
				$form.find("#title").attr("disabled", true);
				$form.find("#text").attr("disabled", true);
				$form.find("#date").attr("disabled", true);
				$form.find("#end").attr("disabled", true);
				$form.find("#active").attr("disabled", true);
			} else {
				$form.find("#title").attr("disabled", false);
				$form.find("#text").attr("disabled", false);
				$form.find("#date").attr("disabled", false);
				$form.find("#end").attr("disabled", false);
				$form.find("#active").attr("disabled", false);
			}
		};

		return {
			init: _init,
		}
	}());

	editNews.init();
});