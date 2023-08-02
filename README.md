A simple service that parse modsecurity logs (modsec_audit.log) and insert few details in a MySQL database

**Requirements:**

- nginx with modsecurity 3.x
  ````
  load_module modules/ngx_http_modsecurity_module.so;
  ````
  log to /var/log/modsec_audit.log

- /usr/share/GeoIP/GeoLite2-Country.mmdb 

- php with php-maxminddb 

Tested on CentOS 7

**Install**

````
mkdir /usr/local/modsecurity-parselog/
cd /usr/local/modsecurity-parselog/
wget https://github.com/wbdv/modsecurity_parselog/releases/download/untagged-1440723f2d2b8d2b838d/modsecurity-parselog.tgz
tar xzf modsecurity-parselog.tgz
cp setup/modsecurity-parselog.service /etc/systemd/system/modsecurity-parselog.service 
systemctl daemon-reload
mysql -e 'CREATE DATABASE modsec';
mysql -e 'GRANT ALL ON modsec.* TO modsec@localhost IDENTIFIED BY ""';
mysql modsec < setup/modsec.sql 
vi defines.php

systemctl enable --now modsecurity-parselog.service
````
