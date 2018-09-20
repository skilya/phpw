<?php
    class Helper
    {
        const HEADER = array(0 => "Accept: application/json");

        public static function ask($obj_type, $param, $token) {
            $link = $token->_link.$obj_type."/?".$param;

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl, CURLOPT_USERAGENT, "amoCRM-API-client-undefined/2.0");
            curl_setopt($curl, CURLOPT_HTTPHEADER, self::HEADER);
            curl_setopt($curl, CURLOPT_URL, $link);
            curl_setopt($curl, CURLOPT_HEADER,false);
            curl_setopt($curl, CURLOPT_COOKIEFILE,dirname(__FILE__)."/cookie.txt");
            curl_setopt($curl, CURLOPT_COOKIEJAR,dirname(__FILE__)."/cookie.txt");
            $out = curl_exec($curl);
            curl_close($curl);
            $result = json_decode($out,TRUE);
            sleep(1);
            return $result;
        }

        private static function send_auth_data($token) {
            $user=array(
                'USER_LOGIN'=> $token->_login,
                'USER_HASH'=> $token->_hash
            );
            $link='https://'.$token->_user.'.amocrm.ru/private/api/auth.php?type=json';

            $curl=curl_init();
            curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
            curl_setopt($curl,CURLOPT_URL,$link);
            curl_setopt($curl,CURLOPT_CUSTOMREQUEST,'POST');
            curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($user));
            curl_setopt($curl,CURLOPT_HTTPHEADER,array('Content-Type: application/json'));
            curl_setopt($curl,CURLOPT_HEADER,false);
            curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie.txt');
            curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie.txt');
            curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
            curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
            $out = curl_exec($curl);
            curl_close($curl);
            sleep(1);

            $result = json_decode($out,true);
            $result = $result['response'];
            return $result;
        }
        public static function authorization($token) {
            $response = self::send_auth_data($token);
            if(isset($response['auth'])); else {
                return false;
            }
            return true;
        }

        public static function print_p($data) {
            echo "<hr><pre>";
            print_r($data);
            echo "</pre>";
        }
    }
?>
