var lerp = function(x1, x2, w) {
	return x1 + (x2 - x1) * w;
};

var localPlayer = function(playerObj, messages) {
	var modifiedPlayer = playerObj, parentUpdate = playerObj.update, parentHandleServerState = playerObj.handleServerState;

	modifiedPlayer.handleServerState = function(serverPlayer) {
		//console.log('Recieved my own state');
	};

	modifiedPlayer.update = function(time) {

		var speed = 250;
		if (keyHandler.isKeyPressed(KEYS.left)) {
			var currPos = modifiedPlayer.getPosition();
			currPos.x -= speed * time;
			//parentHandleServerState({position: currPos});
			playerObj.setPosition(currPos.x, currPos.y);
		}

		if (keyHandler.isKeyPressed(KEYS.right)) {
			var currPos = modifiedPlayer.getPosition();
			currPos.x += speed * time;
			//parentHandleServerState({position: currPos});
			playerObj.setPosition(currPos.x, currPos.y);
		}

		if (keyHandler.isKeyPressed(KEYS.left) || keyHandler.isKeyPressed(KEYS.right)) {
			messages.outgoing.sendStateMessage(modifiedPlayer.ID, modifiedPlayer.getPosition());
		}

		//parentUpdate(time);

	};

	return modifiedPlayer;
};
/*
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

			//playerObj.position.x = lerp(playerObj.position.x, movingTo, 0.1);
		};

		return playerObj;
	};
*/

