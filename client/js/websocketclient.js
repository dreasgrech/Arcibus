var webSocketClient = function(url, hooks) {
	var connection, hooks = hooks || [],
	availableHooks = {
		onConnect: 'onConnect',
		onDisconnect: 'onDisconnect',
		onMessage: 'onMessage'
	};

	return {
availableHooks:availableHooks,
		start: function() {
			if(window["WebSocket"]) {
				connection = new WebSocket(url);
			} else if (window["MozWebSocket"]) {
				connection = new MozWebSocket(url);
			} else {
				throw "Your browser does not support sockets";
			}

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
		},
		setHook: function(name, callback) {
			hooks[name] = callback;
		}
	};
};

