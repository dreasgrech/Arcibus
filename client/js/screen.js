var screen = function(game) {
	var obj = {
		game: game,
		update: function(time) {},
		draw: function(time) {}
	};

	return obj;
};

var mainScreen = function(game) {
	var players = [], 
	availablePlayers = document.getElementById('availablePlayers');

	return {
		addPlayer: function(name) {
				   players.push(name);
				   var el = document.createElement('div');
				   el.innerHTML = name;
				   availablePlayers.appendChild(el);
		}
	};
};

