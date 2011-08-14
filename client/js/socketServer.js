var socketServer = function(url) {
	var connection, onMessageCallback, onCloseCallback, connect = function(callback) {
		if (!window["WebSocket"]) { // websockets are not available on the browser
			return false;
		}

		connection = new WebSocket(url);
		connection.onopen = function() {
			callback();
		};

		connection.onmessage = function(data) {
			var msg = data.data;
			if (onMessageCallback) {
				onMessageCallback(msg);
			}
		};

		connection.onclose = function() {
			if (onCloseCallback) {
				onCloseCallback();
			}
		};

		return true;
	};

	return {
		connect: connect,
		send: function(data) {
			try {
				connection.send(data);
			} catch(ex) {
				alert(ex);
			}
		},
		onMessage: function(callback) {
			onMessageCallback = callback;
		},
		onClose: function(callback) {
			onCloseCallback = callback;
		}
	};
};

