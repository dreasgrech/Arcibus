/*var screen = function(game) {
	var obj = {
		game: game,
		update: function(time) {},
		draw: function(time) {}
	};

	return obj;
};
*/

var mainScreen = function(messages) {
	var readyButton = $("#readyButton"),
	chatInput = $("#chat-box"),
	chatLogs = $("#chat-logs"),
	listPlaceholder = $('#availableUsers'),
	userNameInput = $('#userNameInput'),
	connectButton = $('#connectButton'),
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
		if (keyCode === 13) { // Enter key
			messages.outgoing.sendChat(local.ID, chatInput.val());
			chats.clearChatInput();
		}
	}),
	local;

	readyButton.click(function() {
		messages.outgoing.sendReadySignal(local.ID);
		readyButton.attr("disabled", true); // Disable the ready button
	});

	connectButton.click(function() {
		var userName = userNameInput.val();
		if (!validateUserNick(userName)) {
			alert("That name is not available");
			return;
		}

		connectButton.attr("disabled", true); // Disable the Connect button
		userNameInput.attr("disabled", true); // Disable the name input box
		messages.outgoing.sendIntroduction(userName);
	});


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
		}
	};
};

