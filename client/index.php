<html>
<head>
<link rel="stylesheet" type="text/css" href="css/main.css" />
</head>
<body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
<script src="js/libs/jquery.centerscreen.packed.js"></script>
<script src="js/json2.js"></script>
<script src="js/libs/date.format.js"></script>
<script src="js/websocketclient.js"></script>
<script src="js/frametimer.js"></script>
<script src="js/chathandler.js"></script>
<script src="js/logger.js"></script>
<script src="js/messageHandler.js"></script>
<script src="js/game.js"></script>
<script src="js/screen.js"></script>
<script src="js/player.js"></script>
<script src="js/localPlayer.js"></script>
<script src="js/keys.js"></script>
<script src="js/keyHandler.js"></script>
<script src="js/vector2.js"></script>
<script src="js/main.js"></script>

<!--
<input id="chatbox" type="text"/>
<input type="button" id="send" value="Send"/>
<div id="logs">
</div>
-->
<div id="game-screen" class="screen">
	<canvas id="mainCanvas"></canvas>
</div>

<div id="main-screen" class="screen">
	<div id="player-name-choose">
		 Enter your name: <input type="text" id="playerNameInput"/><input type="button" value="Connect" id="connectButton"/>
	</div>
	<div id="current-players">
		<div>
			<b>Available Players</b>
		</div>
		<div id="availablePlayers">
		</div>
	</div>
<div id="main-chat">
<div id="chat-logs">

</div>
<div id="chat-input">
<input id="chat-box" type="text" disabled/>
</div>
</div>
</div>
</body>
</html>
