var game = function(canvasElement, step) {
	if (!canvasElement.getContext) {
		throw "Your browser doesn't support the Canvas, so use a different browser or go away.";
	}

	var context = canvasElement.getContext('2d'),
	stepInterval = 10,
	obj = {
		context: context,
		canvas: canvasElement,
		clearCanvas: function() {
			context.clearRect(0, 0, canvasElement.width, canvasElement.height);
		},
		stepInterval: stepInterval
	};

	setInterval(function() {
		step.call(obj, obj);
	},
	stepInterval);

	return obj;
};
