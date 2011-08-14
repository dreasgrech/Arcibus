var keyHandler = (function() {
	var pressedKeys = [];
	window.addEventListener('keydown', function(evt) {
		pressedKeys[evt.keyCode] = true;
	},
	true);
	window.addEventListener('keyup', function(evt) {
		pressedKeys[evt.keyCode] = false;
	},
	true);

	document.onkeydown = function(ev) {
		return ev.keyCode != 38 && ev.keyCode != 40; // disable vertical scrolling from arrows
	};

	return {
		isKeyPressed: function(keyCode) {
			return pressedKeys[keyCode];
		}
	};
} ());
