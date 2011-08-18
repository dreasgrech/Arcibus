<?php
interface iIncomingMessageHandler {
	public function handle($socket, $message);
}
?>
