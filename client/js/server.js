var webSocketClient = function(url, hooks) {
	var connection, availableHooks = {
		onConnect: 'onConnect',
		onDisconnect: 'onDisconnect',
		onMessage: 'onMessage'
	};

	return {
		start: function() {
			if (!window["WebSocket"]) {
				throw "Your browser does not support sockets";
			}

			connection = new WebSocket(url);

			connection.onopen = function() {
				if (hooks[availableHooks.onConnect]) {
					hooks[availableHooks.onConnect].call(this);
				}
			}

			if (hooks[availableHooks.onDisconnect]) {
				connection.onclose = hooks[availableHooks.onDisconnect];
			};

			connection.onmessage = function(data) {
				var message = data.data;
				hooks[availableHooks.onMessage] && hooks[availableHooks.onMessage].call(this, message);
			};
		},
		send: function(message) {
			      connection.send(message);
		}
	};
};

/*
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
*/

