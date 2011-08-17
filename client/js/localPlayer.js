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
		messageHandler.outgoing.sendMovedMessage(id, playerObj.position);
	};

	playerObj.moveLeft = function(time) {
		var velocity = getVelocity();
		playerObj.position.x -= velocity.x * time;
		//position.y -= velocity_y;
		//sendMovedMessage();
	};

	playerObj.moveRight = function(time) {
		var velocity = getVelocity();
		playerObj.position.x += velocity.x * time;
		//position.y += velocity_y;
		//sendMovedMessage();
	}

	playerObj.update = function(time) {
		if (keyHandler.isKeyPressed(KEYS.left)) {
			playerObj.moveLeft(time);
		}

		if (keyHandler.isKeyPressed(KEYS.right)) {
			playerObj.moveRight(time);
		}
	};

	return playerObj;
};

