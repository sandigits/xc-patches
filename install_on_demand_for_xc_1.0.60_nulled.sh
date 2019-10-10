#!/bin/bash
XC_DIR=/home/xtreamcodes/iptv_xtream_codes/
WWW_DIR=wwwdir/
jeshile='\e[40;38;5;82m' #jeshile
echo -e "${jeshile} ┌─────────────────────────────────────┐ \e[0m"
echo -e "${jeshile} │  On-Demand for Xtream Codes 1.0.60  │ \e[0m"
echo -e "${jeshile} └─────────────────────────────────────┘ \e[0m\n"
if [ -d "$XC_DIR" ]; then
  reshile='\e[1;37m' #jeshile
  read -p "XC Server Port [8080]: " xcport
  xcport=${xcport:-8080}
  read -p "MySQL Host [localhost]: " dbhost
  dbhost=${dbhost:-localhost}
  read -p "MySQL Username [root]: " dbuser
  dbuser=${dbuser:-root}
  read -s -p "MySQL Password: " dbpass
  echo ""
  read -p "MySQL Database [xtream_iptvpro]: " dbname
  dbname=${dbname:-xtream_iptvpro}
  if mysql -h "$dbhost" -u "$dbuser" -p"$dbpass"  -e "use $dbname"; then
    mysql -h "$dbhost" -u "$dbuser" -p"$dbpass"  -e "use $dbname; ALTER TABLE streams ADD COLUMN on_demand TINYINT(1) NULL DEFAULT 0 AFTER added, ADD COLUMN sh_called TINYINT(1) NULL DEFAULT 0 AFTER on_demand"
    wget https://github.com/sandigits/xc-patches/raw/master/files/xc_1.0.60_nulled/updates.zip
    unzip updates.zip -d "$XC_DIR$WWW_DIR"
    rm updates.zip
    touch "$XC_DIR"crons/PHPSESSID
    chown xtreamcodes:xtreamcodes "$XC_DIR"crons/PHPSESSID
    touch "$XC_DIR"crons/ACTKEY
    echo "a5b3f19d6bac5f3def4" > /home/xtreamcodes/iptv_xtream_codes/crons/ACTKEY
    sed -i "s/MYHOST/$dbhost/g" "$XC_DIR$WWW_DIR/updates/config.php"
    sed -i "s/MYUSERNAME/$dbuser/g" "$XC_DIR$WWW_DIR/updates/config.php"
    sed -i "s/MYPASSWORD/$dbpass/g" "$XC_DIR$WWW_DIR/updates/config.php"
    sed -i "s/MYDATABASE/$dbname/g" "$XC_DIR$WWW_DIR/updates/config.php"
    sed -i "s/XCPORT/$xcport/g" "$XC_DIR$WWW_DIR/updates/config.php"
    wget https://github.com/sandigits/xc-patches/raw/master/files/xc_1.0.60_nulled/cron/monitor_session
    wget https://github.com/sandigits/xc-patches/raw/master/files/xc_1.0.60_nulled/cron/monitor_stream
    wget https://github.com/sandigits/xc-patches/raw/master/files/xc_1.0.60_nulled/cron/stream
    mv monitor_session "$XC_DIR"crons/monitor_session
    chmod +x "$XC_DIR"crons/monitor_session
    mv monitor_stream "$XC_DIR"crons/monitor_stream
    chmod +x "$XC_DIR"crons/monitor_stream
    mv stream "$XC_DIR"crons/stream
    chmod +x "$XC_DIR"crons/stream
    crontab -l > mycron
    echo "*/10 * * * * ${XC_DIR}crons/monitor_stream $dbhost $dbuser $dbpass $dbname $xcport" >> mycron
    echo "*/7 * * * * ${XC_DIR}crons/monitor_session" >> mycron
    crontab mycron
    rm mycron
    service cron restart
    wget https://github.com/sandigits/xc-patches/raw/master/files/xc_1.0.60_nulled/streaming/check_ondemand.php
    mv check_ondemand.php "$XC_DIR$WWW_DIR/streaming/check_ondemand.php"
    chmod +x "$XC_DIR$WWW_DIR/streaming/check_ondemand.php"
    chown xtreamcodes:xtreamcodes "$XC_DIR$WWW_DIR/streaming/check_ondemand.php"
    GREEN='\033[0;32m'
    echo -e "${GREEN} On-Demand is installed !!\e[0m"
  fi
else
  RED='\033[0;31m'
  NC='\033[0m' # No Color
  echo -e "${RED}Extream codes directory(/home/xtreamcodes/iptv_xtream_codes/) not found".${NC}
fi
