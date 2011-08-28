var mainScreen = function(messages) {
	var screen = $("#main-screen"),
	readyButton = $("#readyButton"),
	chatInput = $("#chat-box"),
	chatLogs = $("#chat-logs"),
	listPlaceholder = $('#availableUsers'),
	userNameInput = $('#userNameInput'),
	connectButton = $('#connectButton'),
	local,
	addToUserList = function(user) {
		var userRow = $('<div/>'),
		color = "white";
		if (user.inGame) {
			color = "red";
		} else if (user.ready) {
			color = "green";
		}

		userRow.css({
			"font-weight": "bold",
			"color": color
		});

		userRow.html(user.nick);
		listPlaceholder.append(userRow);
	},
	validateUserNick = function(name) {
		return name.length && name.length <= 15;
	},
	chats = chatHandler(chatLogs, chatInput, function(keyCode) {
		var chatText;
		if (keyCode === 13) { // Enter key
			chatText = chatInput.val();
			if (!chatText.length) { // chat box is empty
				return;
			}

			messages.outgoing.sendChat(chatText);
			chats.clearChatInput();
		}
	}),
	connect = function() {
		var userName = userNameInput.val();
		if (!validateUserNick(userName)) {
			alert("That name is not available");
			return;
		}

		connectButton.attr("disabled", true); // Disable the Connect button
		userNameInput.attr("disabled", true); // Disable the name input box
		messages.outgoing.sendIntroduction(userName);

	},
	ready = function() {
		messages.outgoing.sendReadySignal();
		readyButton.attr("disabled", true); // Disable the ready button
	};

	readyButton.click(ready);
	connectButton.click(connect);

	userNameInput.keypress(function(ev) { //Once the client presses ENTER in the name-input box, connect
		if (ev.which === 13) {
			connect();
		}
	});

	userNameInput.focus();

	return {
		initiateLocalUser: function(id, nick) {
			local = localUser(id, nick);
			chatInput.attr("disabled", false); // Enable the box for chat input
			chatInput.focus();
			readyButton.attr("disabled", false); // Enable the ready button
		},
		addChatLine: function(nick, message) {
			chats.addChatLine(nick, message);
		},
		updateUserList: function(users) {
			var i = 0,
			j = users.length;

			listPlaceholder.html("");
			for (; i < j; ++i) {
				addToUserList(users[i]);
			}
		},
		hide: function() {
			screen.hide();
		}
	};
};

