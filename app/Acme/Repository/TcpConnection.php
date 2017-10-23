<?php

namespace Acme\Repository;

class TcpConnection{

    private $socket;
    private $connected;

    public function __construct($serverAddr, $port){
        $this->connected = false;
        $this->socket = @socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if ($this->socket){
            $count = 1;
            while($count <= 3){
                $conn = @socket_connect($this->socket, $serverAddr, $port);
                if ($conn){
                    $this->connected = true;
                    break;
                }
                $count++;
            }
        }
    }

    public function tcpSend($msg){
        /*
         * 这个发送速度快，不需要等待完全相应 适应与ACK和SNE
         */

        $data = null;

        if (@socket_write($this->socket, $msg) === false) return FALSE;

        while($buffer = @socket_read($this->socket, 1024, PHP_BINARY_READ)){
            if ($buffer === false) return FALSE;
            $data .= $buffer;
            if (strlen($buffer) < 1024) break;
        }

        return $data;
    }

    public function tcpSend_ex($msg){
        /*
         * 等待完全相应才返回，适应于FETCH
         */

        $data = null;

        if (@socket_write($this->socket, $msg) === false) return FALSE;

        while($buffer = @socket_read($this->socket, 1024, PHP_BINARY_READ)){

            $data .= $buffer;

        }

        return $data;
    }

    public function isConnected(){
        return $this->connected;
    }

    public function close(){
        socket_close($this->socket);
    }

    public function getSocketErrorString() {
        $err = socket_last_error($this->socket);
        return "(ErrorNo:$err)" . socket_strerror($err);
    }

    public function setReadTimeout($seconds, $useconds){
        $timeout = socket_get_option($this->socket, SOL_SOCKET, SO_RCVTIMEO);
        $timeout["sec"] = $seconds;
        $timeout["usec"] = $useconds;
        socket_set_option($this->socket, SOL_SOCKET, SO_RCVTIMEO, $timeout);
    }
}

