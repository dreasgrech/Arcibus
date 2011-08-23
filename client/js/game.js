var game = function(messages, playerList, localUserID) {
	var canvasElement = $('#mainCanvas')[0];

	if (!canvasElement.getContext) {
		throw "Your browser doesn't support the Canvas, so use a different browser or go away.";
	}

	canvasElement.width = 800;
	canvasElement.height = 600;

	var context = canvasElement.getContext('2d'),
	players = (function() {
		var i = 0,
		j = playerList.length,
		p, list = [];

		for (; i < j; ++i) { (function(p) {
				list[p.ID] = player(context, p.ID, p.nick, p.position);
			} (playerList[i]));
		}

		return list;
	} ()),
	gameScreen = $("#game-screen"),
	stepInterval = 10,
	clearCanvas = function() {
		context.clearRect(0, 0, canvasElement.width, canvasElement.height);
	},
	forEachPlayer = function(callback) {
		for (playerID in players) {
			if (!players.hasOwnProperty(playerID)) {
				continue;
			}
			callback(players[playerID]);
		}
	},
	drawImageOnCanvas = function(img, x, y) {
		context.drawImage(img, x, y);
	},
	update = function(time) {
		clearCanvas();
		drawImageOnCanvas(backgroundImage, 0, 0);
		//move the stuff and update
		forEachPlayer(function(player) {
			player.update(time);
			player.draw();
		});

		if (keyHandler.isKeyPressed(KEYS.left)) {
			messages.outgoing.sendMovedMessage(localUserID, "left");
		}

		if (keyHandler.isKeyPressed(KEYS.right)) {
			messages.outgoing.sendMovedMessage(localUserID, "right");
		}
	},
	images = imageManager({
		"back": "img/game/background.png",
		"player": "img/game/player.png"
	}),
	backgroundImage,
	startGameLoop = function() {
		frameTimer.tick();
		setInterval(function() {
			update(frameTimer.getSeconds());
			frameTimer.tick();
		},
		stepInterval);
	};

	images.load(function(list) {
		backgroundImage = list["back"];
		startGameLoop();
	});

	gameScreen.show();

	return {
		applySnapshot: function(snapshot) {
			var updatedPlayers = snapshot.players;
			forEachPlayer(function(player) {
				var updatedPlayer = updatedPlayers[player.ID];
				player.addPosition(updatedPlayer.position);
				//player.setPosition(updatedPlayer.position.x, updatedPlayer.position.y);
			});
		}
	};
};

