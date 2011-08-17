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
	messages = messageHandler(socketClient); 
	messages.incoming.registerMessageAction("welcome", function (data) {
			console.log(data);
			});
	//messagelistener = socketMessageListener(socketClient);

	socketClient.start();

	/*
	messagelistener.listen(function (message) {
			alert(message);
			});
			*/

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

	var mainCanvas = document.getElementById('mainCanvas');
	var g = game(mainCanvas, function(gameObject) {
		gameObject.clearCanvas();
		//move the stuff and update
		if (local) {
			local.update();
			local.draw();
		}
	});

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

