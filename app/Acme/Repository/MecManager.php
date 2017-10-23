<?php
namespace Acme\Repository;

//消息推送
use Acme\Repository\TcpConnection;

class MecManager
{

    private $sender;
    private $accepter = array();
    private $TcpPool = array();
    public $msgObject;

    public function __construct($sender, $msgObject, $accepters)
    {
        $this->sender = $sender;

        $msgObject['uuid'] = $sender . time() . rand(10000, 99999);//uniqid(rand(10000,99999));
        $this->msgObject = $msgObject;
        $this->initAccepter($accepters);
    }

    private function initAccepter($accepters)
    {

        foreach ($accepters as $accepter) {
            if (!isset($this->TcpPool[$accepter["mec_ip"] . $accepter["mec_port"]])) {
                $socket = new TcpConnection($accepter["mec_ip"], $accepter["mec_port"]);
                if (!$socket->isConnected()) {
                    \Log::error(sprintf("Mec server %s connect fail. port is %s", $accepter["mec_ip"], $accepter["mec_port"]));
                    continue;
                }
                $socket->setReadTimeout(5, 0);
                $this->TcpPool[$accepter["mec_ip"] . $accepter["mec_port"]] = $socket;
            }
            if (!isset($this->accepter[$accepter["mec_ip"] . $accepter["mec_port"]])) {
                $this->accepter[$accepter["mec_ip"] . $accepter["mec_port"]] = array();
            }

            if (strcmp($accepter["mid"], "0000") === 0) {
                \Log::debug(sprintf("mid is 0000 skip send. accepter info: %s", json_encode($accepter)));
                continue;
            }
            $this->accepter[$accepter["mec_ip"] . $accepter["mec_port"]][] = array(
                "dstmid" => $accepter["mid"],
                "push" => $accepter["push_service_type"],
                "lpsserver" => $accepter["lps_ip"],
                "lpsport" => $accepter["lps_port"]
            );
        }
    }

    public function sendMessage()
    {
        $sendFlag = false;

        $ttl = 1296000;
        foreach ($this->TcpPool as $key => $tcpPool) {

            $message = "SNE " . $this->sender . " " . json_encode($this->accepter[$key]) . " $ttl " . $this->msgObject["type"] . " " . json_encode($this->msgObject) . " \n";

            \Log::debug("$key send message:" . $message);
            $result = $tcpPool->tcpSend($message);
            \Log::debug("$key send result:" . $result);

            if (!$result) {
                \Log::error(sprintf("Mec server %s send fail[1]. Tcp send error.", $key));
            } else {
                $resArr = explode("\n", $result);
                $resCode = explode(" ", $resArr[0]);
                if ($resCode[0] != 0) {
                    \Log::error(sprintf("Mec server %s send fail[2]. MEC error. result=%s", $key, $result));
                } else {
                    $sendFlag = true;
                }
            }

            $tcpPool->close();

        }

        return $sendFlag;
        
    }

}
