<?php namespace Wlkj\WeixinApi;

class WeixinApi
{
	public $appid	  = 'wxbcfaa471bb9a3957';
	public $appsr	  = 'c2b10dab4ef8782ab2d82a1bcb44419d';
	public $timeout   = 5;
	public $interval  = 5400;	// 更新间隔时间
	//public $interval  = 1;	// 更新间隔时间
					
	// ==================================================
	// 调用CURL发起GET请求	
	// 参数：$url  - 请求链接URL
	public function curl_get($url)
	{		
		$ch = curl_init();		
		curl_setopt_array($ch,[
					CURLOPT_URL 			=> $url,					
					CURLOPT_TIMEOUT 		=> $this->timeout,
					CURLOPT_RETURNTRANSFER	=> true,
					CURLOPT_SSL_VERIFYPEER  => false						
		]);	
		if( !$result = curl_exec($ch)){
			trigger_error(curl_error($ch));
		}
						
		curl_close($ch);
		return $result;		
	}  	
	
	// ===================================================
	// 获取微信公众号 access_token		
	public function getAccess()
	{		
		$file 	= "weixin-access_token.txt";
		$fp 	= fopen($file,"a+");
		$result = null;
		if(flock($fp,LOCK_EX)){														
			do{
				// 1.判断是否要更新access_token
				rewind($fp);
				$data = explode(':',fread($fp, 1000));
				if(count($data) == 2){
					$interval = time() - intval($data[0]);
					if($interval < $this->interval){
						$result = $data[1];
						break;
					}
				}				
				
				// 2.重新获取access_token
				$url  = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->appid.'&secret='.$this->appsr;						
				$data = json_decode($this->curl_get($url));
				if(empty($data)) 				break;
				else if(!empty($data->errcode)) break;
				$result = $data->access_token;
				
				// 3.保存access_token到文件
				ftruncate($fp,0);
				rewind($fp); 		// 倒回文件指针的位置
				fwrite($fp ,time().":".$result);							
																	
			}while(false);								
			flock($fp , LOCK_UN); 
		}
		fclose($fp);	
		return $result;
	}
	
	// **************************************************************************************************
    // 获取微信 API ticket
    public  function getTicket($access_token='')
    {
        // 1.获取$access_token;
        if(empty($access_token)) $access_token	= $this->getAccess();

        // 2.获取ticket        
        $file 	= "weixin-ticket.txt";
		$fp 	= fopen($file,"a+");
		$result = null;
		if(flock($fp,LOCK_EX)){														
			do{
				// 1.判断是否要更新access_token
				rewind($fp);
				$data = explode(':',fread($fp, 1000));
				if(count($data) == 2){
					$interval = time() - intval($data[0]);
					if($interval < $this->interval){
						$result = $data[1];
						break;
					}
				}				
			                        
				// 2.重新获取ticket
				$url     = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?'. http_build_query(['access_token'=>$access_token , 'type' => 'jsapi' ]);						
				$data    = json_decode($this->curl_get($url));
				if(empty($data)) 					break;
				else if($data->errcode == '40001'){
					$access_token   = $this->getAccess();
					$url     		= 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?'. http_build_query(['access_token'=>$access_token , 'type' => 'jsapi' ]);											
					$data    		= json_decode($this->curl_get($url));	
				}
				$result = $data->ticket;
				
				// 3.保存access_token到文件
				ftruncate($fp,0);
				rewind($fp); 		// 倒回文件指针的位置
				fwrite($fp ,time().":".$result);								
			}while(false);								
			flock($fp , LOCK_UN); 
		}
		fclose($fp);	
		return $result;		
    }
			
}
	
$weixin = new WeixinApi();
echo $weixin->getAccess();


	
	
?>