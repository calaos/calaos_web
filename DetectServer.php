<?php

/**
 * Copyright Enna Team
 *
 * Squeezecenter detection
 * How to use:
 *
 *    $d = new DetectServer();
 *    $d->discover();
 *    print_r($d->getServerList());
 */

define("SQUEEZECENTER_PORT", 3483);
//define("DISCOVER_PACKET", "d\x00\x01\x11\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00");
//define("DISCOVER_PACKET_SIZE", 18);
define("DISCOVER_PACKET", "eNAME\x00JSON\x00IPAD\x00VERS\x00UUID\x00");
define("DISCOVER_PACKET_SIZE", 26);
define("DISCOVER_TIMEOUT", 5); //5 seconds timeout

class DetectServer
{
        protected $sock = 0;
        protected $servers = array();

        public function __construct()
        {
                if(!($this->sock = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP)))
                {
                        $errorcode = socket_last_error();
                        $errormsg = socket_strerror($errorcode);

                        throw new Exception("Socket creation failed: $errormsg", $errorcode);
                }

                socket_set_option($this->sock, SOL_SOCKET, SO_BROADCAST, 1);
                socket_set_option($this->sock, SOL_SOCKET, SO_REUSEADDR, 1);
                socket_set_option($this->sock, SOL_SOCKET, SO_RCVTIMEO, array("sec"=>DISCOVER_TIMEOUT, "usec"=>0));

                if(!socket_bind($this->sock, "0.0.0.0", SQUEEZECENTER_PORT))
                {
                        $errorcode = socket_last_error();
                        $errormsg = socket_strerror($errorcode);

                        throw new Exception("Socket binding failed: $errormsg", $errorcode);
                }
        }

        public function __destruct()
        {
                if ($this->sock !== 0)
                        @socket_close($this->sock);
        }

        public function discover()
        {
                //Send discover packet
                socket_sendto($this->sock, DISCOVER_PACKET, DISCOVER_PACKET_SIZE, 0, '255.255.255.255', SQUEEZECENTER_PORT);

                $quit = false;
                while(!$quit)
                {
                        if (@socket_recvfrom($this->sock, &$buf, 512, 0, &$remote_ip, &$remote_port))
                        {
                                $sc = $this->parsePacket($buf, $remote_ip);
                                if ($sc !== false)
                                        $this->servers[] = $sc;
                        }
                        else
                                $quit = true;
                }
        }

        public function getServerList()
        {
                return $this->servers;
        }

        protected function parsePacket($buf, $remote_ip)
        {
                //not a Slimproto packet
                if ($buf[0] !== 'E')
                        return false;

                $sc = array();
                $sc['ip'] = $remote_ip;

                $ptr = 1;
                while ($ptr <= strlen($buf) - 5)
                {
                        $t = substr($buf, $ptr, 4);
                        $l = ord(substr($buf, $ptr + 4, 4));
                        $v = substr($buf, $ptr + 5, $l);
                        $ptr = $ptr + 5 + $l;

                        if ($t == "NAME") $sc["name"] = $v;
                        else if ($t == "IPAD") $sc["ip"] = $v;
                        else if ($t == "JSON") $sc["json"] = $v;
                        else if ($t == "VERS") $sc["version"] = $v;
                        else if ($t == "UUID") $sc["uuid"] = $v;
                }

                //try to get server MAC adresse from our arp cache
                exec('arp -a '.escapeshellarg($sc["ip"]), $lines);

                foreach($lines as $line)
                {
                        $cols = explode(' ', trim($line));

                        if (strstr($cols[1], $sc['ip']) !== false)
                        {
                                $sc["mac"] = $cols[3];
                        }
                }

                return $sc;
        }
}

/*
//For testing purpose
$d = new DetectServer();
$d->discover();
print_r($d->getServerList());
*/

?>