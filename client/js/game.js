var game = function(canvasElement, step) {
	if (!canvasElement.getContext) {
		throw "Your browser doesn't support the Canvas, so use a different browser or go away.";
	}

	canvasElement.width = 800;
	canvasElement.height = 600;

	var context = canvasElement.getContext('2d'),
	stepInterval = 1,
	obj = {
		players: [],
		addPlayer: function(player) {
			obj.players.push(player);
		},
		forEachPlayer: function(callback) {
			var i = 0,
			j = obj.players.length;
			for (; i < j; ++i) {
				callback(obj.players[i]);
			}
		},
		context: context,
		canvas: canvasElement,
		clearCanvas: function() {
			context.clearRect(0, 0, canvasElement.width, canvasElement.height);
		},
		stepInterval: stepInterval
	};

	frameTimer.tick();
	setInterval(function() {
		step.call(obj, obj, frameTimer.getSeconds());
		frameTimer.tick();
	},
	stepInterval);

	return obj;
};

