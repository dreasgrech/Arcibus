var messageHandler = function(socketServer) {
	var registeredCallbacks = {},
	messageActionNames: {
		welcome: "welcome",
		log: "log",
		playerMove: "playerMove",
		playerlist: "playerlist"
	};

	return {
		incoming: {
			registerMessageAction: function(actionName, callback) {
				registeredCallbacks[actionName] = callback;
			},
			invokeCallback: function(actionName, messageObject) {
				return registeredCallbacks[actionName](messageObject);
			}
		},
		outgoing: {
			sendMovedMessage: function(position) {
				var message = position;
				message.action = messageActionNames.playerMove;
				socketServer.send(JSON.stringify(message));
			}
		}
	};

};
