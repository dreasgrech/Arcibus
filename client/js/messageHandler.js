var messageHandler = function(socketServer, playerID) {
	var registeredCallbacks = {},
	messageActionNames= {
		welcome: "welcome",
		log: "log",
		playerMove: "playerMove",
		playerlist: "playerlist"
	}, initializeMessage = function () {
		return {ID: playerID};
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
				var message = initializeMessage();
				message.position = position;
				message.action = messageActionNames.playerMove;
				socketServer.send(JSON.stringify(message));
			}
		}
	};

};
