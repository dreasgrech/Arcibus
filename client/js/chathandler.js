var chatHandler = function(logsContainer, chatInput, messageHandler, chatInputKeypress) {
	var createChatLine = function(nick, message) {
		var now = new Date(),
		datePart = $("<span/>").html(now.format("UTC:h:MM:ss")).css("color", "red").css("margin-right", 10),
		nickPart = $("<span/>").html(nick).css("color", "green").css("margin-right", 10),
		messagePart = $("<span/>").html(message).css("color", "white"),
		line = $("<div/>");

		line.append(datePart);
		line.append(nickPart);
		line.append(messagePart);

		return line;
	};

	chatInput.keypress(function(ev) {
		chatInputKeypress && chatInputKeypress(ev.which);
	});

	return {
		addChatLine: function(nick, message) {
			logsContainer.append(createChatLine(nick, message));
			logsContainer[0].scrollTop = logsContainer[0].scrollHeight; // Scroll the container to the bottom
		},
		clearChatInput: function() {
			chatInput.val('');
		}
	};
};

