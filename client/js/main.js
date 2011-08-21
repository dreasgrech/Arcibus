(function($) {
	$(function() {
		var socketHooks = {
			'onConnect': function() {
				//logs.writeLine("Connected to the server sucessfully");
			},
			'onDisconnect': function() {
				//logs.writeLine("Disconnected from the server");
			}
		},
		socketClient = webSocketClient('ws://uploadz.myftp.org:8000', socketHooks),
		messages = messageHandler(socketClient),
		mainCanvas = $('#mainCanvas');

		messages.incoming.registerMessageAction("welcome", function(data) {
			main.initiateLocalUser(data.ID, data.nick);
		});

		messages.incoming.registerMessageAction("chat", function(data) {
			main.addChatLine(data.nick, data.chatMessage);
		});

		messages.incoming.registerMessageAction("startgame", function(data) {
			alert("Starting the game");
		});

		messages.incoming.registerMessageAction("userlist", function(data) {
				main.updateUserList(data["users"]);
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

		/*
		var g = game(mainCanvas[0], function(gameObject, time) {
			gameObject.clearCanvas();
			//move the stuff and update
			gameObject.forEachPlayer(function(player) {
				player.update(time);
				player.draw();
			});
		});
		*/

		var main = mainScreen(messages);

		socketClient.start();
	});
} (jQuery));

