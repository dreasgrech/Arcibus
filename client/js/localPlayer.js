var localPlayer = function(game, messageHandler, id, initialPosition, imagePath) {
	var playerObj = player(game, id, initialPosition, imagePath),
	getVelocity = function() {
		var scale_x = Math.cos(playerObj.angle),
		scale_y = Math.sin(playerObj.angle);
		return {
			x: playerObj.speed * scale_x,
			y: playerObj.speed * scale_y
		};
	},
	sendMovedMessage = function() {
		messageHandler.outgoing.sendMovedMessage(playerObj.position);
	};

	playerObj.moveLeft = function() {
		var velocity = getVelocity();
		playerObj.position.x -= velocity.x;
		//position.y -= velocity_y;
		sendMovedMessage();
	};

	playerObj.moveRight = function() {
		var velocity = getVelocity();
		playerObj.position.x += velocity.x;
		//position.y += velocity_y;
		sendMovedMessage();
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

