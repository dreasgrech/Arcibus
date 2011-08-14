var logger = function(el) {
	var write = function (text) {
		var current = el.innerHTML;
		el.innerHTML = current + text;
	};

	return {
		write: write,
		writeLine: function(text) {
			return write(text + "<br/>");
		}
	};

};

