#!/bin/bash
# $1 => [mysql host]
# $2 => [mysql username]
# $3 => [mysql password]
# $4 => [database name]
# $5 => [xtream codes port]
IDLE_LIMIT=1
DELAY=10
PHPSESSID=$(</home/xtreamcodes/iptv_xtream_codes/crons/PHPSESSID)
results=($(mysql -h ${1} --user ${2} -p${3} ${4} -Bse "SELECT D.stream_id, D.server_id FROM streams_sys as D, streams as E where pid <> 0 AND D.stream_id = E.id AND E.on_demand = 1 AND stream_id NOT IN(SELECT DISTINCT C.stream_id FROM (SELECT A.stream_id, A.server_id FROM user_activity_now as A UNION SELECT B.stream_id, B.server_id FROM user_activity as B WHERE B.date_end>=(UNIX_TIMESTAMP()-(${IDLE_LIMIT}*60))) as C);"))
cnt=${#results[@]}
for (( i=0 ; i<${cnt} ; i+=2 ))
  do
    echo "stream_id=${results[$i]}; server_id=${results[$i+1]}"
    mysql -h ${1} --user ${2} -p${3} ${4} -e "UPDATE streams SET sh_called = 0 WHERE id = ${results[$i]}"
    /bin/bash /home/xtreamcodes/iptv_xtream_codes/crons/stream.sh stop ${results[$i]} ${results[$i+1]} $PHPSESSID $5
    sleep $DELAY
  done

