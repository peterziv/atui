<?php
/**
 * ATUI
 * @author Peter (peter.ziv@hotmail.com)
 * @date July 15, 2017
 * @version 1.0.0
 */

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
                $this->log->error('Failed to login in the Bug Management system(ZenTao)');
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
            $bug = $this->findBugByTitle($sid, $data);
            if (!is_null($bug)) {
                $bug['steps'] .= '<p>' . date('Y-m-d H:i:s') . ' Reported the same bug via ATUI</p>';
                $this->editBug($sid, $bug);
            } else {
                $this->createBug($sid, $data);
            }
        }

        private function createBug($sid, $data)
        {
            $this->log->info('Report the bug!');
            $data['steps'] = (array_key_exists('steps', $data)) ? '<p>' . $data['steps'] . '</p>' : '';
            $data['steps'] .= '<p>' . date('Y-m-d H:i:s') . ' Reported the bug via ATUI</p>';
            $this->log->info($data['steps']);
            $this->client->post($this->domain . '/index.php?m=bug&f=create&t=json&productID=' .
                $data['product'] . '&' . $sid, $data);
        }

        private function editBug($sid, $data)
        {
            $this->log->debug($data);
            $this->log->warning('This bug is reported before! #' . $data['id']);
            $v = $this->client->post($this->domain . '/index.php?m=bug&f=edit&t=json&bugID=' .
                $data['id'] . '&' . $sid, $data);
            $this->log->debug($v);
        }

        private function findBugByTitle($sid, $data = array())
        {
            $rs = $this->client->get($this->domain . '/index.php?m=bug&f=browse&t=json&productID=' .
                $data['product'] . '&' . $sid);
            $result = $this->client->parse($rs);
            $sameBug = null;
            foreach ($result['bugs'] as $bug) {
                $this->log->debug($bug['id'] . ' ' . $bug['title']);
                if ($bug['title'] != htmlspecialchars($data['title'])) {
                    continue;
                }

                //TODO there's one bug for lanuage issue.
//                if (strpos($bug['openedBuild'], $data['openedBuild']) === false) {
//                    $data['openedBuild'] .= ',' . $bug['openedBuild'];
//                }
                $data['id'] = $bug['id'];
                $data['steps'] = $bug['steps'];
                $sameBug = $data;
                break;
            }
            return $sameBug;
        }
    }
}