## Installing ON-DEMAND for Xtream Codes 1.0.60 Nulled

#### Run the script

sudo wget https://raw.githubusercontent.com/sandigits/xc-patches/master/install_on_demand_for_xc_1.0.60_nulled.sh

sudo chmod +x install_on_demand_for_xc_1.0.60_nulled.sh

sudo ./install_on_demand_for_xc_1.0.60_nulled.sh


#### and change the rewrire rules as follows in nginx.conf (/home/xtreamcodes/iptv_xtream_codes/nginx/conf)

~~rewrite ^/live/(.*)/(.*)/(.*)\.(.*)$ /streaming/clients_live.php?username=$1&password=$2&stream=$3&extension=$4 break;~~
rewrite ^/live/(.*)/(.*)/(.*)\.(.*)$ /streaming/check_ondemand.php?username=$1&password=$2&stream=$3&extension=$4 break;

~~rewrite ^/(.*)/(.*)/(.*).ch$ /streaming/clients_live.php?username=$1&password=$2&stream=$3&extension=ts break;~~
rewrite ^/(.*)/(.*)/(.*).ch$ /streaming/check_ondemand.php?username=$1&password=$2&stream=$3&extension=ts break;


#### Contact https://web.telegram.org/@sivainf for ACTKEY
