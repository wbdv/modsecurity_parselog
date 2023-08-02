#!/bin/bash
/usr/bin/tail -f /var/log/modsec_audit.log | php -f /usr/local/modsecurity-parselog/modsec-parser.php
