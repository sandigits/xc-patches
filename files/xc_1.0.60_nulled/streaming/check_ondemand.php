<?php
	require_once ('../updates/config.php');
	$streaming_loc = "/home/xtreamcodes/iptv_xtream_codes/streams";
	
	// Check if on-demand is enabled for the streaming.
	$username = '';
	if (isset($_GET['username'])) {
		$username = $_GET['username'];
	}
	$password = '';
	if (isset($_GET['password'])) {
		$password = $_GET['password'];
	}
	$stream = -1;
	if (isset($_GET['stream'])) {
		$stream = $_GET['stream'];
	}
	$extension = '';
	if (isset($_GET['extension'])) {
		$extension = $_GET['extension'];
	}
	$conn = new mysqli(MYSQL_SERVER, MYSQL_USERNAME, MYSQL_PASSWORD, MYSQL_DATABASE, MYSQL_PORT);
	$sql = "SELECT on_demand FROM streams where id = $stream";
	$result = $conn->query($sql);
	$od_enabled = 0;
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()){
			$od_enabled = $row['on_demand'];
		}
	}
	if ($od_enabled==1){
		// On-demand is enabled. Check if streaming is in-progress
		$sql = "SELECT pid FROM streams_sys where stream_id = $stream";
		$result = $conn->query($sql);
		$pid = NULL;
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()){
				$pid = $row['pid'];
			}
		}
		$sql = "SELECT A.sh_called, B.server_id FROM streams as A, streams_sys as B where A.id = $stream and A.id = B.stream_id;";
		$result = $conn->query($sql);
		$shcalled = 0;
		$serverid = 0;
		if ($result->num_rows > 0) {
				while($row = $result->fetch_assoc()){
						$shcalled = $row['sh_called'];
						$serverid = $row['server_id'];
				}
		}
		if (is_null($pid) && $shcalled==0 ){
			// Streaming is not in-progress. Start it!
			error_log("Streaming is not in-progress.");
			$sql = "UPDATE streams SET sh_called = 1 WHERE id = $stream";
			$conn->query($sql);
			$phpsessid = file_get_contents(PHPSESSID_FILE);
			exec("/home/xtreamcodes/iptv_xtream_codes/crons/stream start $stream $serverid ".$phpsessid." ".XC_SERVER_PORT);
		}
		$loc = $streaming_loc.'/'.$stream.'_*.*';
		$files = glob($loc);
		if (count($files)<2){
			sleep(5);
			header("Location:  /streaming/check_ondemand.php?username=$username&password=$password&stream=$stream&extension=ts");
			die();
		}

	}
	header("Location: /streaming/clients_live.php?username=$username&password=$password&stream=$stream&extension=ts");
?>
