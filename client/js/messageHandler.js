var messageHandler = function(client) {
	var registeredCallbacks = {},
	messageActionNames = {
		welcome: "welcome",
		log: "log",
		playerMove: "playerMove",
		playerlist: "playerlist"
	},
	sendMessage = function(id, action, data) {
		data = data || {};
		if (id) {
			data.ID = id;
		}
		data.action = action;
		client.send(JSON.stringify(data));
	};

	client.setHook(client.availableHooks.onMessage, function(data) {
		var message, action;
		try {
			message = JSON.parse(data);
		} catch(ex) {
			console.log("Error parsing message to JSON => " + data);
			return;
		}

		action = registeredCallbacks[message.action];
		if (action) {
			action(message);
		}
	});

	return {
		incoming: {
			registerMessageAction: function(actionName, callback) {
				registeredCallbacks[actionName] = callback;
			}
			/*,
			invokeCallback: function(actionName, messageObject) {
				return registeredCallbacks[actionName](messageObject);
			}*/
		},
		outgoing: {
			sendMovedMessage: function(id, position) {
				sendMessage(id, "moved", {
					position: position
				});
			},
			sendReadySignal: function(id) {
				sendMessage(id, "ready");
			},
			sendIntroduction: function(nick) {
				sendMessage(0, "introduction", {
					nick: nick
				});
			},
			sendChat: function(id, message) {
				sendMessage(id, "chat", {
					chat: message
				});

			}
		}
	};

};

