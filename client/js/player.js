
var player = function(context, id, nick, initialPosition) {
var lerp = function(x1, x2, w) {
	return x1 + (x2 - x1) * w;
};
	var position = initialPosition,
	image = new Image();
	image.onload = function() {};
	image.src = 'img/game/player.png';

	var oldPos = position, newPos = position, lerpValue = 0;

	return {
		ID: id,
		nick: nick,
		getPosition: function() {
			return position;
		},
		setPosition: function(x, y) {
			position.x = x;
			position.y = y;
		},
		draw: function(time) {
			context.drawImage(image, position.x, position.y);
		},
		update: function(time) {
				if (lerpValue <= 1) {
					//console.log(position.x, newPos.x, lerpValue, lerp(position.x, newPos.x, lerpValue));
					position.x = lerp(+oldPos.x, +newPos.x, lerpValue);
					lerpValue += 0.2;
				}

				if (lerpValue >= 1) {
					lerpValue = 0;
				}
		},
		addPosition: function(newPosition) {
				     if (+newPosition.x === +position.x) {
					     return;
				     }

				     oldPos = position;
				     newPos = newPosition;
				     lerpValue = 0;
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

