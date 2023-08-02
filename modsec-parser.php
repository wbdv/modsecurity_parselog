<?php
#   @title    nginx and mod_security log parser
#   @author   Daniel Toma <dt@webdev.ro>
#   @date     2021-02-23
#   @updated  2023-08-03

include_once('defines.php');

$exclude_ips = array('217.156.103.68', '86.126.137.70', '81.196.83.32', '138.199.37.99', '86.120.59.94', '86.124.80.209', '86.120.58.190', '82.137.7.101', '212.146.104.14');

#ini_set('display_errors', 1);
#ini_set('display_startup_errors', 1);
#error_reporting(E_ALL);

stream_set_blocking(STDIN, 0);
while(true) {
	$log = stream_get_contents(STDIN);
	if($log!='') {
#        error_log($log, 3, 'logparser.log');

    $p = array();
    $chunk = '';
    $letter = '';

    foreach(explode("\n", $log) as $l) {
        $regex = '/^\-\-\-(\S+)\-\-\-([A-Z])\-\-$/';
        #$regex = '/^(\S+) (\S+) (\S+) \[([^:]+):(\d+:\d+:\d+) ([^\]]+)\] \"(\S+) (.*?) (\S+)\" (\S+) (\S+) "([^"]*)" "([^"]*)"/';
        #$regex = '/^(\S+) (\S+) (\S+) \[([^:]+):(\d+:\d+:\d+) ([^\]]+)\] (\S+) (\S+) (\S+) (\S+) (\S+) (\S+) (.*) \- (\S+) (\S+) (\S+) (\S+) (\S+) (\S+) (\S+) (\S+) (\S+)/';
        #$no_log_url = array('/process-backup.php', '/checkssl.php', '/monitor/index.php');
        if(preg_match($regex ,$l, $matches)) {
            #echo '<pre>';
            #echo $l."\n";
#            var_dump($matches);
            if($matches[1]!=$chunk) {
				if($chunk!='')
					unset($p[$chunk]);
                $chunk=$matches[1];
            }
            $letter = $matches[2];

/*
            $data1 = explode('/', $matches[4]);
            $data2 = $data1[2].'-'.$data1[1].'-'.$data1[0];
            $data2 = date('Y-m-d H:i:s', strtotime($data2.' '.$matches[5]));

            $cc = str_replace('cc=', '', $matches[22]);

            try {
                $sth = $q->prepare("INSERT INTO logs VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $sth->execute([$data2, $matches[1], $cc, $matches[7], $matches[8], $matches[9], $matches[10], $matches[12], $matches[13]]);
            } catch (\PDOException $e) {
                throw new \PDOException($e->getMessage(), (int)$e->getCode());
            }
*/
        } else if($chunk!='' && $letter!='') {
            if($letter == 'Z') {
                $ip='';
                $data2='';
                $method='';
                $url='';
                $response_code='';
                $rule_id=0;
                $message='';
				$score=0;

#                print_r($p[$chunk]);
                if(preg_match('/^\[([^:]+):(\d+:\d+:\d+) ([^\]]+)\] (\S+) (\S+) (\S+) (\S+) (\S+)/', $p[$chunk]["A"], $matches)) {
#                    print_r($matches);
                    $data1 = explode('/', $matches[1]);
                    $data2 = $data1[2].'-'.$data1[1].'-'.$data1[0];
                    $data2 = date('Y-m-d H:i:s', strtotime($data2.' '.$matches[2]));
                    $ip = $matches[5];
                }
                if(preg_match('/^(\S+) (\S+) (\S+).*/', $p[$chunk]["B"], $matches)) {
#                    print_r($matches);
                    $method = $matches[1];
                    $url = substr($matches[2],0,100);
                }
                if(preg_match('/^(\S+) (\S+).*/', $p[$chunk]["F"], $matches)) {
#                    print_r($matches);
                    $response_code = $matches[2];
                }
                if(preg_match('/\[id "(\S+)"\]/', $p[$chunk]["H"], $matches)) {
#                    print_r($matches);
                    $rule_id = $matches[1];
                }
                if(preg_match('/\[msg "([^\]]+)"\]/', $p[$chunk]["H"], $matches)) {
#                    print_r($matches);
                    $message = $matches[1];
                }
                if(preg_match('/Total Score: (\d+)/', $p[$chunk]["H"], $matches)) {
#                    print_r($matches);
                    $score = (int)($matches[1]);
                }
        		
#				error_log($data2.' '.$ip."\n", 3, 'logparser.log');
                if($ip!='' && !in_array($ip, $exclude_ips) && $data2!='' && $url!='' && $rule_id!=0) {


					$ip_info = $reader->get($ip);
					$cc = $ip_info['country']['iso_code'];

                    try {
                        $sth = $q->prepare("INSERT INTO modsec VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                        $sth->execute([$data2, $cc, $ip, $method, $url, $response_code, $rule_id, $score, $message]);

						// also, block the IPs with more than 5 entries in mod_security
//						$st = $q->query("SELECT ip, count(*) AS nr FROM modsec WHERE date>date_sub(NOW(), INTERVAL 60 MINUTE) GROUP BY ip HAVING nr>=5");
//						foreach ($st as $row) {
//    						shell_exec("csf -d ".$row["ip"]." blocked by mod_security ".$row["nr"]." entries >> /var/log/lfd.log 2>&1");
//						}

                    } catch (\PDOException $e) {
                        throw new \PDOException($e->getMessage(), (int)$e->getCode());
                    }
                }
#				print_r($p[$chunk]);
            } else {
                $p[$chunk][$letter].=$l."\n";
            }
        }
    }
#	echo "Done.\n";
    }
}

$reader->close();
?>
