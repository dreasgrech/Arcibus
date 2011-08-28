var player = function(context, id, nick, initialPosition) {
	var lerp = function(x1, x2, w) {
		return x1 + (x2 - x1) * w;
	};
	var position = initialPosition,
	image = new Image(),
	angle = 0,
	stateBuffer = (function() {
		var buffer = [],
		index = 0;

		return {
			add: function(state) {
				var position = state.position;
				if (buffer.length > 2) {
					if ( + position.x !== buffer[buffer.length - 1].position.x) {
						buffer.push(state);
					}
					return;
				}
				buffer.push(state);

				if (buffer.length === 2) {
					index++;
				}
			},
			previous: function() {
				return buffer[index - 1];
			},
			current: function() {
				return buffer[index];
			},
			advance: function() {
				if (index !== buffer.length - 1) {
					index++;
				}
			}
		};
	} ());

	position.x = + position.x;
	position.y = + position.y;

	image.onload = function() {};
	image.src = 'img/game/player.png';

	var lerpValue = 0;

	return {
		ID: id,
		nick: nick,
		getPosition: function() {
			return {
				x: +position.x,
				y: +position.y
			};
		},
		getAngle: function() {
			return angle;
		},
		setAngle: function(newAngle) {
			angle = newAngle;
		},
		setPosition: function(x, y) {
			position.x = + x;
			position.y = + y;
		},
		draw: function(time) {
			context.save();
			context.translate(position.x, position.y);
			context.rotate(angle);
			context.translate( - image.width / 2, - image.height / 2);
			context.drawImage(image, 0, 0);
			context.restore();
		},
		update: function(time) {
			var prevState = stateBuffer.previous(),
			currentState = stateBuffer.current(),
			prevPosition,
			currentPosition;

			if (! (prevState && currentState)) {
				return;
			}

			prevPosition = prevState.position,
			currentPosition = currentState.position;

			if (position.x !== currentPosition.x) {
				if (Math.abs(position.x - currentPosition.x) < 10) { // snap to position
					console.log("Snapping", position.x, currentPosition.x);
					position.x = currentPosition.x;
					stateBuffer.advance();
					lerpValue = 0;
					return;
				}

				console.log("Lerping", + prevPosition.x, + currentPosition.x, lerpValue);
				position.x = lerp( + prevPosition.x, + currentPosition.x, lerpValue);
				//console.log(position.x, +currentPosition.x, +currentState.velocity.x);
				//position.x += +currentState.velocity.x * time;
				console.log(position.x, currentPosition.x);
				lerpValue += 0.1;
				if (lerpValue > 1) {
					lerpValue = 0;
				}
			}
			else {
				stateBuffer.advance();
				lerpValue = 0;
			}
		},
		handleServerState: function(serverPlayer) {
			serverPlayer.position.x = Math.round( + serverPlayer.position.x);
			serverPlayer.position.y = Math.round( + serverPlayer.position.y);
			stateBuffer.add(serverPlayer);
		}
	};
};

/*
var player = function(game, id, initialPosition, imagePath) {
	var position = initialPosition,
	angle = 0,
	speed = 350,
	image = new Image();
	image.onload = function() {
		//obj.draw();
	};
	image.src = imagePath;

	var draw = function() {
		// http://stackoverflow.com/questions/3793397/html5-canvas-drawimage-with-at-an-angle
		//var x = game.canvas.width / 2;
		//var y = game.canvas.height / 2;
		//game.context.translate(x, y);
		//game.context.rotate(angle);
		//game.context.drawImage(image, position.x, position.y);
		//game.context.rotate( - angle);
		//game.context.translate( - x, - y);

		game.context.drawImage(image, position.x, position.y);
	};

	return {
		ID: id,
		draw: draw,
		update: function() {},
		speed: speed,
		angle: angle,
		position: position
	};
};
*/

