var Setting = function () {
    var socket, containerQR, containerWA, containerLog, imgQR;
	var handleEvents = function (){
        containerQR = $(".wa-qrcode");
        containerWA = $(".wa-info");
        containerLog = $(".wa-logs");

        imgQR = containerQR.find('img');

        socket = io.connect(HOST);
        socket.on('message', function(msg) {
            containerLog.text(msg);
        });

        socket.on('qr', function(src) {
            imgQR.attr('src', src);
            containerQR.show();
            containerLog.hide();
            containerWA.hide();
        });

        socket.on('ready', function(data) {
            containerQR.hide();
            containerWA.show();
            containerLog.hide();
        });

        socket.on('authenticated', function(data) {
            containerQR.hide();
            containerWA.show();
            containerLog.hide();
        });

        socket.on('disconnected', function(data) {
            containerQR.hide();
            containerWA.hide();
            containerLog.show();
        });
	}

	return {
		init: function () {
			handleEvents();
		},
	}
}();