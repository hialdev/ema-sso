var Dashboard = function () {
	var handleStat = function (){
		Application.post({
			container: '.statistic',
			url: 'dashboard/stat',
			data: {},
			useAlert: false,
			success: function (data) {
				Application.fillDataValue($(".statistic"), data);
			},
			failed: function () {
			}
		});
	}

	var handleEvents = function (){
		handleStat();
	}

	return {
		init: function () {
			handleEvents();
		},
	}
}();