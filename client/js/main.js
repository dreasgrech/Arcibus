window.onload = function() {
	var logs = logger(document.getElementById('logs')),
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
	mainCanvas = document.getElementById('mainCanvas'),
	local_player;

	messages.incoming.registerMessageAction("welcome", function(data) {
		local_player = localPlayer(g, messages, data.playerID, vector2(300, 10), "img/p1.png");
		g.addPlayer(local_player);

		console.log(data);
		main.addPlayer("guzi");
	});

	messages.incoming.registerMessageAction("playerlist", function(data) {
			console.log(data);
	});

	frameTimer.tick();

	var g = game(mainCanvas, function(gameObject, time) {
		gameObject.clearCanvas();
		//move the stuff and update
		gameObject.forEachPlayer(function(player) {
			player.update(time);
			player.draw();
		});
	});

	var connectButton = document.getElementById('connectButton');
	connectButton.onclick = function () {
		var playerName = document.getElementById('playerNameInput').value;
		messages.outgoing.sendIntroduction(playerName);
	};

	var main = mainScreen();

	socketClient.start();
};
/*

	var sendButton = document.getElementById('send');
	sendButton.onclick = function() {
		var chatboxInput = document.getElementById('chatbox'),
		data = chatboxInput.value;
		if (data.indexOf("script") >= 0) {
			alert('no scripts mate');
			return;
		}

		socketClient.send(data);
	};


	var local;
	var players = [];
	var messages;

	var serverActions = {
		"welcome": function(obj) {
			logs.writeLine("Welcome to the server");
			messages = messageHandler(server, obj.playerID);
			local = localPlayer(g, messages, obj.playerID, vector2(60, 10), "img/p1.png");
		},
		"log": function(obj) {
			logs.writeLine(obj.message);
		},
		"playermove": function(obj) {

		},
		"playerlist": function(obj) {
			logs.writeLine("Current players online: " + obj.list.length);
			console.log(obj);
		}
	};

};
*/

