#!/bin/bash
PHPSESSID=$(</home/xtreamcodes/iptv_xtream_codes/crons/PHPSESSID)
SESSION_PATH="/home/xtreamcodes/iptv_xtream_codes/tmp/sess_$PHPSESSID"
SIZE_LIMIT="100"
if [ ! -f "$SESSION_PATH" ]
  then
  sudo touch "/home/xtreamcodes/iptv_xtream_codes/tmp/sess_$PHPSESSID"
  sudo chown  root:root "/home/xtreamcodes/iptv_xtream_codes/tmp/sess_$PHPSESSID"
  sudo chmod 757 "/home/xtreamcodes/iptv_xtream_codes/tmp/sess_$PHPSESSID"
  :> "/home/xtreamcodes/iptv_xtream_codes/tmp/sess_$PHPSESSID"
  SESSID=$(</home/xtreamcodes/iptv_xtream_codes/crons/sess_$PHPSESSID)
  sudo echo "$SESSID" > "/home/xtreamcodes/iptv_xtream_codes/tmp/sess_$PHPSESSID"
  sudo chmod 600 "/home/xtreamcodes/iptv_xtream_codes/tmp/sess_$PHPSESSID"
  sudo chown  xtreamcodes:xtreamcodes "/home/xtreamcodes/iptv_xtream_codes/tmp/sess_$PHPSESSID"      
else
  fsize=$(stat --printf="%s" $SESSION_PATH)
  if [ $SIZE_LIMIT -gt $fsize ]; then
    sudo rm "/home/xtreamcodes/iptv_xtream_codes/tmp/sess_$PHPSESSID"
    sudo touch "/home/xtreamcodes/iptv_xtream_codes/tmp/sess_$PHPSESSID"
    sudo chown  root:root "/home/xtreamcodes/iptv_xtream_codes/tmp/sess_$PHPSESSID"
    sudo chmod 757 "/home/xtreamcodes/iptv_xtream_codes/tmp/sess_$PHPSESSID"
    :> "/home/xtreamcodes/iptv_xtream_codes/tmp/sess_$PHPSESSID"
    SESSID=$(</home/xtreamcodes/iptv_xtream_codes/crons/sess_$PHPSESSID)
    sudo echo "$SESSID" > "/home/xtreamcodes/iptv_xtream_codes/tmp/sess_$PHPSESSID"
    sudo chmod 600 "/home/xtreamcodes/iptv_xtream_codes/tmp/sess_$PHPSESSID"
    sudo chown  xtreamcodes:xtreamcodes "/home/xtreamcodes/iptv_xtream_codes/tmp/sess_$PHPSESSID"
  fi
fi
