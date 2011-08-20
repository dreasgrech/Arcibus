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
		local_player;

		var listPlaceholder = $('#availablePlayers');
		var addToPlayerList = function(nick) {
			var playerRow = $('<div/>');
			playerRow.html(nick);
			listPlaceholder.append(playerRow);
		};

		/*
	var chatHandler = function (socketClient) {

	};
*/
		var chatInput = $("#chat-box"),
		chatLogs = $("#chat-logs");

		var chats = chatHandler(chatLogs, chatInput, messages, function(keyCode) {
			if (keyCode === 13) { // Enter key
				messages.outgoing.sendChat(local_player.ID, chatInput.val());
				chats.clearChatInput();
			}
		});

		messages.incoming.registerMessageAction("welcome", function(data) {
			local_player = localPlayer(g, messages, data.ID, vector2(300, 10), "img/p1.png");
			g.addPlayer(local_player);
			chatInput.attr("disabled", false); // Enable the box for chat input
		});

		messages.incoming.registerMessageAction("chat", function(data) {
			console.log(data);
			chats.addChatLine(data.nick, data.chatMessage);
		});

		var mainscreen = $('#main-screen');
		mainscreen.centerScreen();

		messages.incoming.registerMessageAction("playerlist", function(data) {
			var i = 0,
			j = data.playerList.length,
			player;
			listPlaceholder.html("");
			for (; i < j; ++i) {
				addToPlayerList(data.playerList[i].nick);
			}

			console.log(data);
		});

		var g = game(mainCanvas[0], function(gameObject, time) {
			gameObject.clearCanvas();
			//move the stuff and update
			gameObject.forEachPlayer(function(player) {
				player.update(time);
				player.draw();
			});
		});

		var connectButton = $('#connectButton');
		connectButton.click(function() { // Connect to the server
			var playerName = $('#playerNameInput').val();
			messages.outgoing.sendIntroduction(playerName);
		});

		var main = mainScreen();

		socketClient.start();
	});
} (jQuery));

