<?php
	class System {
		static public function fetch ($url, $postdata = NULL, $cookie = NULL, $header = array (), $convert = false)
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
			curl_setopt($ch, CURLOPT_ENCODING, "");
			$re = curl_exec ($ch);
			curl_close ($ch);
			if ($convert == true) {
				$re = mb_convert_encoding ($re, 'UTF-8', 'GBK');
			}
			
			return $re;
		}
		
		static public function GetVideoURL ($cid) {
            $header = [
                'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.75 Safari/537.36'    
            ];
            $para = 'cid='. $cid .'&from=miniplay&player=1&quality=2&type=mp4';
            $sign = md5 ($para . BILIBILI_SECRET);
            $api = 'https://interface.bilibili.com/playurl?'. $para . '&sign=' . $sign;
            $data = $this->xmlToArray($this->fetch($api, NULL, NULL, $header));
    
            return str_replace ('http', 'https', $data['durl']['url']);
        }
        
        static public function GetVideoDan ($cid) {
            $danmaku = [];
            
            $source = $this->fetch('https://comment.bilibili.com/'. $cid .'.xml');
            $data = simplexml_load_string($source);
            $attributes = $data->d;
            $data = json_decode(json_encode($data), true)['d'];
            for ($i=0;$i<count($attributes);$i++) {
                foreach($attributes[$i]->attributes() as $p) {
                    $danOriginal = explode (',', $p);
                    if ($danOriginal[1] == '4') {
                        $type = 'bottom';
                    } else if ($danOriginal[1] == '5') {
                        $type = 'top';
                    } else {
                        $type = 'right';
                    }
                    $danmaku[$i]['author'] = 'BiliBili' . $danOriginal[6];
                    $danmaku[$i]['time'] = $danOriginal[0];
                    $danmaku[$i]['text'] = $data[$i];
                    $danmaku[$i]['color'] = '#' . str_pad (dechex (floor ($danOriginal[3])), 6, '0', STR_PAD_LEFT);
                    $danmaku[$i]['type'] = $type;
                }   
            }
            return $danmaku;
        }
		
		static public function GetIP () {
		    return ($_SERVER["HTTP_VIA"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : $_SERVER["REMOTE_ADDR"];
		}
		
		static public function xmlToArray($xml){ 
            libxml_disable_entity_loader(true); 
            $xmlstring = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA); 
            $val = json_decode(json_encode($xmlstring),true); 
            
            return $val; 
        } 
	}
