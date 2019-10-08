<?php
	define('XC_SERVER', 'http://130.211.220.171:9090');

	define('MYSQL_SERVER', 'localhost');

	define('MYSQL_USERNAME', 'xcpanel');

	define('MYSQL_PASSWORD', 'pencilstand');

	define('MYSQL_DATABASE', 'xtream_iptvpro');

	define('PHPSESSID_FILE', '/home/xtreamcodes/iptv_xtream_codes/crons/PHPSESSID');

	define('SESSION_FOLDER', '/home/xtreamcodes/iptv_xtream_codes/crons/');

	define('MYSQL_PORT', 3306);

	define('RECORDS_PER_PAGE', 500);
	
	define('USER_AGENT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36');
	
	function encode($password){
		$handle = fopen(ENCRYPT_KEY_FILE, "r");
		$safekey = fread($handle, filesize(ENCRYPT_KEY_FILE));
		fclose($handle);
		return safeEncrypt($password, $safekey);
	}

	function decode($password){
		$handle = fopen(ENCRYPT_KEY_FILE, "r");
		$safekey = fread($handle, filesize(ENCRYPT_KEY_FILE));
		fclose($handle);
		return safeDecrypt($password, $safekey);
	}
	
	error_reporting(E_ERROR | E_PARSE);

?>
