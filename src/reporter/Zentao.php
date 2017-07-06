<?php

namespace ZKit\ATUI {

    require_once(__DIR__ . '/BugMS.php');

    class Zentao extends BugMS
    {

        public $user = null;
        public $pwd = null;

        public function report($data = array())
        {
            $sid = $this->getSession();
            if ($this->login($sid)) {
                $this->bug($sid, $data);
            } else {
                echo "[ERORR] Failed to login in the Bug Management system(ZenTao)";
            }
        }

        private function getSession()
        {
            $out = $this->client->get($this->domain . '/index.php?m=api&f=getSessionID&t=json');
            $data = $this->client->parse($out);
            return $data["sessionName"] . '=' . $data['sessionID'];
        }

        private function login($sid)
        {
            if ($this->user == null || $this->pwd == null) {
                return false;
            }
            $out = $this->client->post($this->domain . '/index.php?m=user&f=login&t=json&' . $sid, array(
                'account' => $this->user,
                'password' => $this->pwd));
            return $this->client->isOK($out);
        }

        private function bug($sid, $data)
        {
            $product = $data['product'];
            $this->client->post($this->domain . '/index.php?m=bug&f=create&t=json&productID=' .
                $product . '&' . $sid, $data);
        }

    }

}