## modsecurity_parselog

A simple service that parse modsecurity logs (modsec_audit.log) and insert few details in a MySQL database

**Requirements:**

- nginx with modsecurity 3.x
  ````
  load_module modules/ngx_http_modsecurity_module.so;
  ````
- log to /var/log/modsec_audit.log

- /usr/share/GeoIP/GeoLite2-Country.mmdb (get it from https://dev.maxmind.com/geoip/geolite2-free-geolocation-data )

- php with php-maxminddb (yum install php-maxminddb)

_Tested on CentOS 7_

**Install**

````
mkdir /usr/local/modsecurity-parselog/
cd /usr/local/modsecurity-parselog/
wget -O modsecurity-parselog.tgz https://github.com/wbdv/modsecurity_parselog/archive/refs/tags/v0.1-beta.tar.gz
tar xzf modsecurity-parselog.tgz
chmod +x modsec-parser.sh
cp setup/modsecurity-parselog.service /etc/systemd/system/modsecurity-parselog.service 
systemctl daemon-reload
mysql -e 'CREATE DATABASE modsec';
mysql -e 'GRANT ALL ON modsec.* TO modsec@localhost IDENTIFIED BY "[..]"';
mysql modsec < setup/modsec.sql 
vi defines.php

systemctl enable --now modsecurity-parselog.service
````
