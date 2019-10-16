## Install ON-DEMAND on Xtream Codes 1.0.60 Nulled

#### [1] Run the script

sudo wget https://raw.githubusercontent.com/sandigits/xc-patches/master/install_on_demand_for_xc_1.0.60_nulled.sh

sudo chmod +x install_on_demand_for_xc_1.0.60_nulled.sh

sudo ./install_on_demand_for_xc_1.0.60_nulled.sh


#### [2] Change the rewrire rules as follows in nginx.conf (/home/xtreamcodes/iptv_xtream_codes/nginx/conf). TAKE BACKUP BEFORE MAKING ANY CHANGES.

~~rewrite ^/live/(.*)/(.*)/(.*)\.(.*)$ /streaming/clients_live.php?username=$1&password=$2&stream=$3&extension=$4 break;~~

rewrite ^/live/(.*)/(.*)/(.*)\.(.*)$ /streaming/check_ondemand.php?username=$1&password=$2&stream=$3&extension=$4 break;

~~rewrite ^/(.*)/(.*)/(.*).ch$ /streaming/clients_live.php?username=$1&password=$2&stream=$3&extension=ts break;~~

rewrite ^/(.*)/(.*)/(.*).ch$ /streaming/check_ondemand.php?username=$1&password=$2&stream=$3&extension=ts break;


#### [3] Reboot XC Server

#### [4] Login into http://your_ip_address:xc_port/updates/ Page

![alt Login Page](https://raw.githubusercontent.com/sandigits/xc-patches/master/files/xc_1.0.60_nulled/screens/updates-login.png)

#### [5] Enable On-Demand for Channels. (Start with few channels first. You can increase the count slowly)

![alt Enable On-Demand for Channels](https://raw.githubusercontent.com/sandigits/xc-patches/master/files/xc_1.0.60_nulled/screens/updates-streams.png)

#### [6] Save your PHPSESSID value. (You should login into Xtream Codes via SSH tunnelling. Also use an account which is created only for on-demand purpose and never use that account for login via ipaddress/domain name)

![alt Save your PHPSESSID value](https://raw.githubusercontent.com/sandigits/xc-patches/master/files/xc_1.0.60_nulled/screens/updates-service.png)

#### Copying PHPSESSID
![alt Copy PHPSESSID value](https://raw.githubusercontent.com/sandigits/xc-patches/master/files/xc_1.0.60_nulled/screens/localhost-php-session.png)
