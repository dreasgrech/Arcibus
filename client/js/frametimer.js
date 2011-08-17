var frameTimer = (function() {
	// Adapted from http://codeutopia.net/blog/2009/08/21/using-canvas-to-do-bitmap-sprite-animation-in-javascript/
	var lastTick = (new Date()).getTime(),
	frameSpacing;

	return {
		getSeconds: function() {
			var seconds = frameSpacing / 1000;
			if (isNaN(seconds)) {
				return 0;
			}

			return seconds;
		},
		tick: function() {
			var currentTick = (new Date()).getTime();
			frameSpacing = currentTick - lastTick;
			lastTick = currentTick;
		}
	};
} ());
