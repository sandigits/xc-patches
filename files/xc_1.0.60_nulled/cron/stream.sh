#!/bin/bash
# $1 => ['start' | 'stop']
# $2 => [stream id]
# $3 => [server id]
# $4 => [PHPSESSID]
# $5 => [panel port]
if [ $# -eq 5 ]
	then
		response=$(curl "http://localhost:$5/admin/streams.php?action=str_$1&stream_id=$2&server_id=$3" -H 'Connection: keep-alive' -H 'Pragma: no-cache' -H 'Cache-Control: no-cache' -H 'Upgrade-Insecure-Requests: 1' -H 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36' -H 'Sec-Fetch-Mode: navigate' -H 'Sec-Fetch-User: ?1' -H 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3' -H 'Sec-Fetch-Site: same-origin' -H "Referer: http://localhost:$5/admin/streams.php?action=str_$1&stream_id=$2&server_id=$3" -H 'Accept-Encoding: gzip, deflate, br' -H 'Accept-Language: en-GB,en-US;q=0.9,en;q=0.8,ta;q=0.7' -H "Cookie: PHPSESSID=$4" --compressed)
		if [[ $response == *"Logout"* ]]; then
			logger -s "Action=$1, Channel=$2, Server=$3, Status=success"
		else
			logger -s "Action=$1, Channel=$2, Server=$3, Status=failure"
		fi
fi

