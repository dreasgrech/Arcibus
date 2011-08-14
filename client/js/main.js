window.onload = function() {
	var logs = logger(document.getElementById('logs')),
	server = socketServer('ws://uploadz.myftp.org:8000');
	var connectionSuccess = server.connect(function() {
		logs.writeLine('Connection opened');
	});

	if (!connectionSuccess) {
		logs.writeLine("Your browser doesn't support sockets, so use a different browser or go away.");
	}

	server.onMessage(function(data) {
		try {
			var obj = JSON.parse(data);
			serverActions[obj.action](obj);
		} catch(ex) {

		}
	});

	server.onClose(function() {
		logs.writeLine('Connection closed');
	});

	var sendButton = document.getElementById('send');
	sendButton.onclick = function() {
		var chatboxInput = document.getElementById('chatbox'),
		data = chatboxInput.value;
		if (data.indexOf("script") >= 0) {
			alert('no scripts mate');
			return;
		}

		server.send(data);
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

	var serverActions = {
		"welcome": function(obj) {
			logs.writeLine("Welcome to the server");
			local = localPlayer(g,obj.playerID, vector2(60, 10), "img/p1.png");
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

	var player = function(game, id, initialPosition, imagePath) {
		var position = initialPosition,
		angle = 0,
		speed = 2,
		image = new Image();
		image.onload = function() {
			//obj.draw();
		};
		image.src = imagePath;

		var draw = function() {
			/*
			         * http://stackoverflow.com/questions/3793397/html5-canvas-drawimage-with-at-an-angle
			         */
			var x = game.canvas.width / 2;
			var y = game.canvas.height / 2;
			game.context.translate(x, y);
			game.context.rotate(angle);
			game.context.drawImage(image, position.x, position.y);
			game.context.rotate( - angle);
			game.context.translate( - x, - y);
		};

		return {
			draw: draw,
			update: function() {},
			speed:speed,
			angle:angle,
			position: position
		};
	};

	var localPlayer = function(game, id, initialPosition, imagePath) {
		var playerObj = player(game, id, initialPosition, imagePath);
		var getVelocity = function() {
			var scale_x = Math.cos(playerObj.angle),
			scale_y = Math.sin(playerObj.angle);
			return {
				x: playerObj.speed * scale_x,
				y: playerObj.speed * scale_y
			};
		};

		playerObj.moveLeft = function() {
				var velocity = getVelocity();
				playerObj.position.x -= velocity.x;
				//position.y -= velocity_y;
		};

		playerObj.moveRight = function() {
				var velocity = getVelocity();
				playerObj.position.x += velocity.x;
				//position.y += velocity_y;
		}

		playerObj.update = function() {
			if (keyHandler.isKeyPressed(KEYS.left)) {
				playerObj.moveLeft();
			}

			if (keyHandler.isKeyPressed(KEYS.right)) {
				playerObj.moveRight();
			}
		};

		return playerObj;
	};


	/*var step = function() {
		clearCanvas(context);
	};*/

};

