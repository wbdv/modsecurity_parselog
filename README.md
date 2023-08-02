**Requrements:**

- nginx with modsecurity 3.x
  load_module modules/ngx_http_modsecurity_module.so;
  log to /var/log/modsec_audit.log

- /usr/share/GeoIP/GeoLite2-Country.mmdb 

- php with php-maxminddb 

**Install**

mkdir /usr/local/modsecurity-parselog/
cd /usr/local/modsecurity-parselog/
wget 
tar xzf
cp setup/modsecurity-parselog.service /etc/systemd/system/modsecurity-parselog.service 
systemctl daemon-reload
mysql -e 'CREATE DATABASE modsec';
mysql -e 'GRANT ALL ON modsec.* TO modsec@localhost IDENTIFIED BY ""';
mysql modsec < setup/modsec.sql 
vi defines.php

systemctl enable --now modsecurity-parselog.service
