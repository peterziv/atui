<?php

namespace ZKit\Console {

    class HttpClient
    {
        public function get($url)
        {
            //init
            $ch = curl_init();
            //set parameters.
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            //execute it for response.
            $output = curl_exec($ch);
            //close curl���
            curl_close($ch);
            return $output;
        }

        public function post($url, $data = array())
        {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            // post
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $output = curl_exec($ch);
            curl_close($ch);
            return $output;
        }

        public function parse($output, $key = 'data')
        {
            $dataInit = json_decode($output, true);
            if (array_key_exists('status', $dataInit) && 'success' == $dataInit['status']) {
                return json_decode($dataInit[$key], true);
            }
            return null;
        }

        public function isOK($output)
        {
            $dataInit = json_decode($output, true);
            if (array_key_exists('status', $dataInit) && 'success' == $dataInit['status']) {
                return true;
            }
            return false;
        }
    }

}
