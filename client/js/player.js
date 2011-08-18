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
		/*
		var x = game.canvas.width / 2;
		var y = game.canvas.height / 2;
		game.context.translate(x, y);
		game.context.rotate(angle);
		game.context.drawImage(image, position.x, position.y);
		game.context.rotate( - angle);
		game.context.translate( - x, - y);
		*/
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
