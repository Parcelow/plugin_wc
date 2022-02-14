<?php
	
	namespace PA\Parcelow;

	use Psr\Http\Message\ResponseInterface;
	use GuzzleHttp\Exception\RequestException;
	use GuzzleHttp\Psr7;
	use GuzzleHttp\Psr7\Message;
	use PA\Helper\Helper;

	Class Parcelow
	{

		public function showQuetions($order_id, $access_token, $apihost)
		{

			$Helper = new Helper();
			$access_token = sanitize_text_field($Helper->secured_decrypt($access_token));
			$apihost = sanitize_text_field($Helper->secured_decrypt($apihost));

			if($access_token != ''){
				
				try {
					
					$client = new \GuzzleHttp\Client();
					
					$url = $apihost . "/api/order/".$order_id."/questions";
					$res = $client->request('GET', $url, [
						'headers' => [
							'Content-Type' => 'application/json',
							"Authorization" => $access_token,
							"Accept" => 'application/json',
							'http_errors' => true,
							'force_ip_resolve' => 'v4',
							'connect_timeout' => 2,
							'read_timeout' => 2,
							'timeout' => 2,
						]
					]);

					
					$json = $res->getBody();

					$html = '<h4>Confirmação dados pessoais</h4><br>';
					$json = json_decode($json);
					$id = 0;
					$ant = '';
					$quest = 0;
					foreach($json->questions as $r){
				
						if($r->id != $ant){
							$html .= '<h6>' . $r->question . '</h6>';
							$html .= '<ul class="list-group list-group-flush">';
							$quest++;
						}
				
						foreach($r->answers as $a){
							$html .= '<label class="list-group-item">
							<input class="form-check-input me-1 " type="radio" name="quest_'.$quest.'" value="' . $r->id .";". $a->id . '">
							' . $a->answer . '
							</label>';
						}
				
						if($r->id != $ant){
							$html .= '</ul>';
						}
				
						$ant = $r->id;
					}
					$html .= '<br><a class="btn btn-warning" id="btn_response_question">Prosseguir</a>';
					$html .= '<div id="boxRespQuests"></div><br><br>';
					return $html;

					
				} catch (\GuzzleHttp\Exception\RequestException $e) {
					return $e->getMessage();
				}
				
			} else{
				return "";
			}

		}

		public function responseQuetions($order_id, $access_token, $data, $apihost)
		{
			$Helper = new Helper();
			$access_token = sanitize_text_field($Helper->secured_decrypt($access_token));
			$apihost = sanitize_text_field($Helper->secured_decrypt($apihost));

			if($access_token != ''){

				try {
					
					$client = new \GuzzleHttp\Client();
					
					$url = $apihost . "/api/order/".$order_id."/questions/answers";
					$res = $client->request('POST', $url, [
						'headers' => [
							'Content-Type' => 'application/json',
							"Authorization" => $access_token,
							"Accept" => 'application/json',
							'http_errors' => true,
							'force_ip_resolve' => 'v4',
							'connect_timeout' => 2,
							'read_timeout' => 2,
							'timeout' => 2,
						],
						'body' => $data
					]);
					
					$json = $res->getBody();
					
					$json = json_decode($json);
					if($json->success == true){
						return 1;
					} else{
						return 0;
					}

				} catch (\GuzzleHttp\Exception\RequestException $e) {
					return $e->getMessage();
				}
				
			} else{
				return "";
			}
		}

		public function jsonParcelas($order_id, $access_token, $apihost, $total)
		{
			$Helper = new Helper();
			$access_token = sanitize_text_field($Helper->secured_decrypt($access_token));
			$apihost = sanitize_text_field($Helper->secured_decrypt($apihost));

			if($access_token != ''){
				
				try {

					$client = new \GuzzleHttp\Client();
					
					$url = $apihost . "/api/simulate?amount=" . $total;

					$res = $client->request('GET', $url, [
						'headers' => [
							'Content-Type' => 'application/json',
							"Authorization" => $access_token,
							"Accept" => 'application/json',
							'http_errors' => true,
							'force_ip_resolve' => 'v4',
							'connect_timeout' => 2,
							'read_timeout' => 2,
							'timeout' => 2,
						]
					]);

					$json = $res->getBody();
					$json = json_decode($json);
					$json = $json->data->creditcard->installments;
					$json = json_encode($json);
					return $json;


				} catch (\GuzzleHttp\Exception\RequestException $e) {
					return $e->getMessage();
				}
				
			} else{
				return "";
			}
		}

		public function finalizaPagtoCartao($order_id, $access_token, $dados, $apihost)
		{
			$Helper = new Helper();
			$access_token = sanitize_text_field($Helper->secured_decrypt($access_token));
			$apihost = sanitize_text_field($Helper->secured_decrypt($apihost));

			if($access_token != ''){
				
				try {

					$client = new \GuzzleHttp\Client();
					
					
					$url = $apihost . "/api/order/".$order_id."/payment";
					$res = $client->request('POST', $url, [
						'headers' => [
							'Content-Type' => 'application/json',
							"Authorization" => $access_token,
							"Accept" => 'application/json',
							'http_errors' => true,
							'force_ip_resolve' => 'v4',
							'connect_timeout' => 2,
							'read_timeout' => 2,
							'timeout' => 2,
						],
						'body' => json_encode($dados)
					]);


					$status = (int) $res->getStatusCode();
					$json = $res->getBody();
					$json = json_decode($json);

					if($status == 200){
						return $status . ";".$json->message.";1";
					}
					if($status == 400){
						return $status . ";".$json->message.";2";
					}



				} catch (\GuzzleHttp\Exception\RequestException $e) {
					return $e->getMessage();
				}
				
			} else{
				return "";
			}
		}

		public function finalizaPagtoPIX($order_id, $access_token, $dados, $apihost)
		{
			$Helper = new Helper();
			$access_token = sanitize_text_field($Helper->secured_decrypt($access_token));
			$apihost = sanitize_text_field($Helper->secured_decrypt($apihost));
			//echo $access_token . " } ";

			if($access_token != ''){
				
				try {

					$client = new \GuzzleHttp\Client();
					
					$url = $apihost . "/api/order/".$order_id."/payment";
					$res = $client->request('POST', $url, [
						'headers' => [
							'Content-Type' => 'application/json',
							"Authorization" => $access_token,
							"Accept" => 'application/json'
						],
						'body' => json_encode($dados)
					]);


					$status = (int) $res->getStatusCode();
					$json = $res->getBody();
					$json = json_decode($json);
					return $json->qrcode;



				} catch (\GuzzleHttp\Exception\RequestException $e) {
					return "ERRO -> " . $e->getMessage();
				}
				
			} else{
				return "";
			}
		}

		public function getDadosOrder($order_id, $access_token, $dados, $apihost)
		{
			$Helper = new Helper();
			$access_token = sanitize_text_field($Helper->secured_decrypt($access_token));
			$apihost = sanitize_text_field($Helper->secured_decrypt($apihost));

			if($access_token != ''){
				
				try {

					$client = new \GuzzleHttp\Client();
					
					$url = $apihost . "/api/order/" .$order_id;
					$res = $client->request('GET', $url, [
						'headers' => [
							'Content-Type' => 'application/json',
							"Authorization" => $access_token,
							"Accept" => 'application/json',
							'http_errors' => true,
							'force_ip_resolve' => 'v4',
							'connect_timeout' => 2,
							'read_timeout' => 2,
							'timeout' => 2,
						]
					]);

					$status = (int) $res->getStatusCode();
					$json = $res->getBody();
					$json = json_decode($json);
					$order_id_parcelow = $json->data->reference;
					if($order_id_parcelow > 0){
						return $order_id_parcelow;
					} else{
						return 0;
					}

				} catch (\GuzzleHttp\Exception\RequestException $e) {
					return $e->getMessage();
				}
				
			} else{
				return "";
			}
		}




	}
