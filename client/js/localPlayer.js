var lerp = function(x1, x2, w) {
	return x1 + (x2 - x1) * w;
};

var localPlayer = function(playerObj, playerNumber, localPlayerTurningPoint, messages) {
	var modifiedPlayer = playerObj,
	parentUpdate = playerObj.update,
	parentHandleServerState = playerObj.handleServerState,
	angle = 0,
	isMoving = false,
	speed = 200,
	userKeys = {
		left: 1,
		right: 2
	},
	pressedKeys = 0,
	getPressedKeys = function() {
		var keys = 0;
		if (keyHandler.isKeyPressed(KEYS.left)) {
			keys |= userKeys.left;
		}

		if (keyHandler.isKeyPressed(KEYS.right)) {
			keys |= userKeys.right;
		}
		return keys;
	},
	getVelocity = function() {
		angle = Math.round(angle);
		return {
			y: Math.cos(angle) * speed,
			x: Math.sin(angle) * speed
		};
	},
	userCMDs = [],
	generateUserCMD = function(keysPressed) {
		return {
			keys: keysPressed
		};
	},
	userCMDHandler = (function() {
		// TODO: shift sent messages out of the buffer
		var next = 0,
		buffer = [];

		return {
			addUserCMD: function(userCMD) {
				buffer.push(userCMD);
			},
			sendUserCMD: function() {
				if (buffer[next]) {
					messages.outgoing.sendUserCMD(buffer[next++].keys);
				}
			}
		};
	} ());

	modifiedPlayer.handleServerState = function(serverPlayer) {
		//console.log('Recieved my own state');
	};

	setInterval(userCMDHandler.sendUserCMD, 10);

	var orientation = 'horizontal';

	var orientHorizontal = function () {
		orientation = 'horizontal';
		modifiedPlayer.setAngle(0);
	}, orientVertical = function () {
		orientation = 'vertical';
		modifiedPlayer.setAngle(Math.PI / 2);
	}

	modifiedPlayer.update = function(time) {
		pressedKeys = getPressedKeys();
		var isLeftPressed = (pressedKeys & userKeys.left),
		isRightPressed = (pressedKeys & userKeys.right),
		curr = modifiedPlayer.getPosition(),
		positionBeforeUpdate = {x: curr.x, y: curr.y};

		// If the user is holding both left and right, then ignore the input completely.
		if (pressedKeys === (userKeys.left | userKeys.right)) {
			return;
		}

		if (isLeftPressed || isRightPressed) {

			if (isLeftPressed) {
				if (orientation === 'horizontal') {
					curr.x -= speed * time;
				} else { // orientation is vertical
					if (playerNumber === 1 || playerNumber === 4) { // top left, bottom right
						curr.y += speed * time;
					} else {
						curr.y -= speed * time;
					}
				}

			} else { // RIGHT is pressed
				if (orientation === 'horizontal') {
					curr.x += speed * time;
				} else { // orientation is vertical
					if (playerNumber === 1 || playerNumber === 4) { // top left, bottom right
						curr.y -= speed * time;
					} else {
						curr.y += speed * time;
					}
				}
			}

			if (playerNumber % 2) { // 1 and 3
				if (curr.x >= localPlayerTurningPoint.x && orientation == 'horizontal') {
					curr.x = localPlayerTurningPoint.x - 1;
					orientVertical();
				}

				if (orientation === 'vertical') {
					if (playerNumber === 1 && curr.y >= localPlayerTurningPoint.y) { // player 1
						curr.y = localPlayerTurningPoint.y - 1;
						orientHorizontal();
					} 

					if (playerNumber === 3 && curr.y <= localPlayerTurningPoint.y) { // player 3
						curr.y = localPlayerTurningPoint.y + 1;
						orientHorizontal();
					}
				}
			} else { // 2 and 4
				if (curr.x <= localPlayerTurningPoint.x && orientation == 'horizontal') {
					curr.x = localPlayerTurningPoint.x + 1;
					orientVertical();
				}

				if (orientation === 'vertical') {
					if (playerNumber === 2 && curr.y >= localPlayerTurningPoint.y) { // player 2
						curr.y = localPlayerTurningPoint.y - 1;
						orientHorizontal();
					} 

					if (playerNumber === 4 && curr.y <= localPlayerTurningPoint.y) { // player 4
						curr.y = localPlayerTurningPoint.y + 1;
						orientHorizontal();
					}
				}
			}

			console.log(curr.y);
			if ((curr.x - (playerObj.getWidth() / 2)) <= 0) { // LEFT edge
				curr.x = (playerObj.getWidth() / 2) + 1;
			} else if (curr.x + (playerObj.getWidth() / 2) >= playerObj.context.canvas.width) { // RIGHT edge
				curr.x = (playerObj.context.canvas.width - 1) - (playerObj.getWidth()/2);
			} else if ((curr.y - (playerObj.getWidth() / 2)) <= 0) { // TOP edge
				curr.y = (playerObj.getWidth() / 2) + 1;
			} else if (curr.y + (playerObj.getWidth() / 2) >= playerObj.context.canvas.height) { // BOTTOM edge
				curr.y = (playerObj.context.canvas.height - 1) - (playerObj.getWidth()/2);
			}

			//curr.x += getVelocity().x * time;
			
			if (!(positionBeforeUpdate.x === curr.x && positionBeforeUpdate.y === curr.y)) { // Only update the position if there was a change from the previous postion.
				playerObj.setPosition(curr.x, curr.y);
				userCMDHandler.addUserCMD(generateUserCMD(pressedKeys));
			}

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
			messageHandler.outgoing.sendMovedMessage(playerObj.position);
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

