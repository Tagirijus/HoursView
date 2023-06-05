document.addEventListener('DOMContentLoaded', () => {

	// Since I want to have my HoursView plugin use and show the
	// actual remaining as soon as a subtask might be checked
	// as done and the SubtaskStatusController only will reload
	// the checkbox, I simply did the monkey patch to always
	// reload the whole site on a click of the subtask status
	// checkbox. It's not that great, but it is something ...
	var jsSubtaskToggleStatus = document.querySelectorAll('.js-subtask-toggle-status');
	Array.prototype.forEach.call(jsSubtaskToggleStatus, (el, i) => {
		el.addEventListener('click', (event) => {
			window.location.reload();
		});
	});

});
