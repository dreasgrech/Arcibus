window.onload = function() {
	var logs = logger(document.getElementById('logs')),
	socketServ = socketServer('ws://uploadz.myftp.org:8000');

	/*
	server.onMessage(function(data) {
		try {
			var obj = JSON.parse(data);
			serverActions[obj.action](obj);
		} catch(ex) {

		}
	});
	*/

	var server = function(socketServerObj, onOpen, onClose) { //TODO: use the same hooks approach
		var onMessageListeners = [];
		informListeners = function(message) {
			var i = 0,
			j = onMessageListeners.length,
			listener;
			for (; i < j; ++i) {
				listener = onMessageListeners[i];
				listener[obj.action] && listener[obj.action](obj);
			}

		};

		// Connect to the server
		var connectionSuccess = socketServerObj.connect(function() {
			onOpen();
			//logs.writeLine('Connection opened');
		});

		if (!connectionSuccess) {
			throw "Your browser doesn't support sockets, so use a different browser or go away."
		}

		socketServerObj.onClose(onClose);
		socketServerObj.onMessage(function(data) {
			try {
				var obj = JSON.parse(data);
				informListeners(obj);
			} catch(ex) {

			}
		});

		return {
			listen: function(callbacks) {
				// callbacks: {<actionName>: <callback>, <actionName>: <callback>}
				onMessageListeners.push(callbacks);
			}
		};
	};

	var mainServer;
	//try {
		mainServer = server(socketServ, function() {
			logs.writeLine('Connection opened');
		},
		function() {
			logs.writeLine('Connection closed');
		});
	//} catch(ex) { // couldn't connect to the server
	//	logs.writeLine(ex);
	//}

	mainServer.listen({
		"": function() {}
	});

	var sendButton = document.getElementById('send');
	sendButton.onclick = function() {
		var chatboxInput = document.getElementById('chatbox'),
		data = chatboxInput.value;
		if (data.indexOf("script") >= 0) {
			alert('no scripts mate');
			return;
		}

		socketServ.send(data);
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

