var messageHandler = function(client) {
	var registeredCallbacks = {},
	messageActionNames = {
		welcome: "welcome",
		log: "log",
		playerMove: "playerMove",
		playerlist: "playerlist"
	},
	sendMessage = function(action, data) {
		data = data || {};
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

		//console.log(message);
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
			sendMovedMessage: function(direction) {
				sendMessage("moved", {
					direction: direction
				});
			},
			sendUserCMD: function(pressedKeys) {
				sendMessage("usercmd", {keys: pressedKeys});
			},
			sendReadySignal: function() {
				sendMessage("ready");
			},
			sendIntroduction: function(nick) {
				sendMessage("introduction", {
					nick: nick
				});
			},
			sendChat: function(message) {
				sendMessage("chat", {
					chat: message
				});

			}
		}
	};

};

