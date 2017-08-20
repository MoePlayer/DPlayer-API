<?php
	class SystemModel extends FLModel {
		public function fetch ($url, $postdata = NULL, $cookie = NULL, $header = array (), $convert = false)
		{
	        // 访问
			$ch = curl_init ();
			curl_setopt ($ch, CURLOPT_URL, $url);
			if (!is_null ($postdata)) {
				curl_setopt ($ch, CURLOPT_POSTFIELDS, $postdata);
			}
			if (!is_null ($cookie)) {
				curl_setopt ($ch, CURLOPT_COOKIE, $cookie);
			}
			if (!empty ($header)) {
				curl_setopt ($ch, CURLOPT_HTTPHEADER, $header);
			}
			curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt ($ch, CURLOPT_HEADER, false);
			curl_setopt ($ch, CURLOPT_TIMEOUT, 20);
			$re = curl_exec ($ch);
			curl_close ($ch);
			if ($convert == true) {
				$re = mb_convert_encoding ($re, 'UTF-8', 'GBK');
			}
			
			return $re;
		}
		
		public function GetVideoURL ($cid) {
            $header = [
                'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.75 Safari/537.36'    
            ];
            $para = 'cid='. $cid .'&from=miniplay&player=1&quality=2&type=mp4';
            $sign = md5 ($para . BILIBILI_SECRET);
            $api = 'http://interface.bilibili.com/playurl?'. $para . '&sign=' . $sign;
            $data = $this->xmlToArray($this->fetch($api, NULL, NULL, $header));
    
            return str_replace ('http', 'https', $data['durl']['url']);
        }
		
		public function GetIP () {
		    return ($_SERVER["HTTP_VIA"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : $_SERVER["REMOTE_ADDR"];
		}
		
		public function xmlToArray($xml){ 
            libxml_disable_entity_loader(true); 
            $xmlstring = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA); 
            $val = json_decode(json_encode($xmlstring),true); 
            return $val; 
        } 
	}
