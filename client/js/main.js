(function($) {
	$(function() {
		var logs = logger($('#logs')),
		socketHooks = {
			'onConnect': function() {
				logs.writeLine("Connected to the server sucessfully");
			},
			'onDisconnect': function() {
				logs.writeLine("Disconnected from the server");
			}
		},
		socketClient = webSocketClient('ws://uploadz.myftp.org:8000', socketHooks),
		messages = messageHandler(socketClient),
		mainCanvas = $('#mainCanvas'),
		local_player,
		readyButton = $("#readyButton"),
		chatInput = $("#chat-box"),
		chatLogs = $("#chat-logs"),
		listPlaceholder = $('#availablePlayers'),
		playerNameInput = $('#playerNameInput');

		var addToPlayerList = function(player) {
			var playerRow = $('<div/>');
			console.log(player.ready);
			if (player.ready) {
				playerRow.css({
					"font-weight": "bold",
					"color": "red"
				});
			}

			playerRow.html(player.nick);
			listPlaceholder.append(playerRow);
		};

		var chats = chatHandler(chatLogs, chatInput, messages, function(keyCode) {
			if (keyCode === 13) { // Enter key
				messages.outgoing.sendChat(local_player.ID, chatInput.val());
				chats.clearChatInput();
			}
		});

		readyButton.click(function() {
			messages.outgoing.sendReadySignal(local_player.ID);
			readyButton.attr("disabled", true); // Disable the ready button
		});

		messages.incoming.registerMessageAction("welcome", function(data) {
			local_player = localPlayer(g, messages, data.ID, vector2(300, 10), "img/p1.png");
			g.addPlayer(local_player);
			chatInput.attr("disabled", false); // Enable the box for chat input
			readyButton.attr("disabled", false); // Enable the ready button
			chats.addChatLine("ADMIN", "New player: " + local_player.ID);
		});

		messages.incoming.registerMessageAction("chat", function(data) {
			console.log(data);
			chats.addChatLine(data.nick, data.chatMessage);
		});

		messages.incoming.registerMessageAction("startgame", function(data) {
			alert("Starting the game");
		});

		messages.incoming.registerMessageAction("playerlist", function(data) {
			var i = 0,
			j = data.playerList.length,
			player;
			listPlaceholder.html("");
			for (; i < j; ++i) {
				addToPlayerList(data.playerList[i]);
			}

			console.log(data);
		});

		document.addEventListener('touchmove', function(event) {
			//event.preventDefault();
			var touch = event.touches[0];
			if (local_player) {
				messages.outgoing.sendChat(local_player.ID, "Touch x:" + touch.pageX + ", y:" + touch.pageY);
			}
		},
		false);

		$('#viewport').centerScreen();

		var g = game(mainCanvas[0], function(gameObject, time) {
			gameObject.clearCanvas();
			//move the stuff and update
			gameObject.forEachPlayer(function(player) {
				player.update(time);
				player.draw();
			});
		});

		var validatePlayerName = function (name) {
			return name.length > 0 && name.length <= 10;
		};

		var connectButton = $('#connectButton');
		connectButton.click(function() { // Connect to the server
			var playerName = playerNameInput.val();
			if (!validatePlayerName(playerName)) {
				alert("That name is not available");
				return;
			}

			messages.outgoing.sendIntroduction(playerName);
			connectButton.attr("disabled", true); // Disable the Connect button
			playerNameInput.attr("disabled", true); // Disable the name input box
		});

		var main = mainScreen();

		socketClient.start();
	});
} (jQuery));

