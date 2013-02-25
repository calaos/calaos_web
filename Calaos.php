<?php
        require_once "Socket.php";
        require_once "Utils.php";

        class Calaos
        {
                var $socket;
                var $user;
                var $pass;

                //ctor
                function Calaos()
                {
                        $this->socket = new Net_Socket();
                        $this->socket->setBlocking(true);

                        if (getConfigOption("cn_user") != "" &&
                            getConfigOption("cn_pass") != "")
                        {
                                $this->user = getConfigOption("cn_user");
                                $this->pass = getConfigOption("cn_pass");
                        }
                        else
                        {
                                $this->user = getConfigOption("calaos_user");
                                $this->pass = getConfigOption("calaos_password");
                        }
                }

                //Singleton
                function Instance()
                {
                        static $instance;

                        if (!isset($instance))
                                $instance = new Calaos();

                        return $instance;
                }

                function Clean()
                {
                        if (!PEAR::isError($this->socket->getStatus()))
                                $this->socket->writeLine("exit");
                        $this->socket->disconnect();
                }

                //return the response string
                function SendRequest($cmd)
                {
                        global $config;

                        if (PEAR::isError($this->socket->getStatus()))
                        {
                                //on essaye de se connecter
                                $ret = $this->socket->connect("localhost", "4456", false, 300);

                                if (PEAR::isError($ret)) //fail!
                                {
                                        //echo $ret->getMessage();
                                        return "";
                                }

                                //login
                                $this->socket->writeLine("login ".rawurlencode($this->user)." ".rawurlencode($this->pass));
                                $this->socket->readLine();
                        }

                        $this->socket->writeLine($cmd);
                        return $this->socket->readLine();
                }

        }

?>