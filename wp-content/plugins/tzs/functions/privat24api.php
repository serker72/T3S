<?php
/**
* privat24api
* 
* Проведение эквайринга, выписки для физ. и юр. лиц
* 
*/


class privat24api {
	/**
	* ID мерчанта
	* 
	* @var int Целочисленное
	*/
	private $merchant_id;
	/**
	* Пароль мерчанта
	* 
	* @var string Строка
	*/
	private $merchant_password;
	/**
	* Сообщение об ошибке
	* 
	* @var string Строка
	*/
	private $errmess;
	/**
	* Состояние платежа
	* 
	* @var string Строка
	*/
	private $statemess;
	
	/**
	* Конструктор класса privat24api
	*
	* Параметрический конструктор класса privat24api
	*
	* @param string $merchant_id ID мерчанта
	* @param string $merchant_password Пароль мерчанта
	*/
	function __construct($merchant_id, $merchant_password){ // конструктор
		$this->merchant_id = $merchant_id; 
		$this->merchant_password = $merchant_password;
	}
	
	
	/**
	* Проверка платежа на корректность и состояние
	*
	* Проверка платежа на корректность и состояние
	*
	* @param string $order Уникальный код операции в системе мерчанта 
	* @param int $test Признак тестового платежа (0 - платёж будет проведён немедленно, 1 - платёж будет проверен на корректность, но не будет проведён)
	* @return int 
	*/
	function CheckOrPaymentOrder($order, $test){
		$this->errmess = "no error";
		$data = '<oper>cmt</oper><wait>0</wait><test>'.$test.'</test><payment><prop name="order" value="'.$order.'"/></payment>';
		$str = '<?xml version="1.0" encoding="UTF-8"?><request version="1.0"><merchant>';
		$str .= '<id>'.$this->merchant_id.'</id>';
		$str .= '<signature>'.$this->calcSignature($data).'</signature>';
		$str .= '</merchant><data>';
		$str .= $data;
		$str .= '</data></request>';
		$request_result =  $this->msoap($str,"https://api.privatbank.ua/p24api/ishop_pstatus");
		
		/*Разбиваем XML на массив*/
		 $p = xml_parser_create(); 
		 xml_parse_into_struct($p, $request_result, $vals, $index); 
		 xml_parser_free($p); 
		
		/*Ищем по аттрибутам тегов теги MESSAGE - индикатор сообщения об ошибке, и STATE - индикатор корректности платежа*/
		foreach($vals as $el) 
		{ 
		if(isset($el['attributes']) && isset($el['attributes']['MESSAGE'])) {
			$err=$el['attributes']['MESSAGE']; $this->errmess = $err; return -1;}
		if(isset($el['attributes']) && isset($el['attributes']['STATE'])) 
			$state=$el['attributes']['STATE'];
		}
		/* Если какая-либо ошибка - выводим текст ошибки на экран и возвращаем сообщение об ошибке*/
			$this->errmess = "no error";
		
		switch($state){
			case "incomplete": $this->statemess=$state; return 0;  /* Действия, если состояние incomplete */ break;
			case "not found": $this->statemess=$state; return 1; /* Действия, если состояние not found */ break;
			case "ok": $this->statemess=$state; return 2; /* Действия, если состояние ok */ break;
			case "fail": $this->statemess=$state; return 3; /* Действия, если состояние fail */ break;
			case "test": $this->statemess=$state; return 4; /* Действия, если состояние test */ break;
			case "wait": $this->statemess=$state; return 5; /* Действия, если состояние wait */ break;	
		}
	}
	
	/**
	* Возвращает сообщение об ошибке
	*
	* Возвращает сообщение об ошибке
	*
	* @return string 
	*/
	function getErrorMessage(){
		return $this->errmess;
	}
	
	/**
	* Возвращает состояние платежа
	*
	* Возвращает состояние платежа
	*
	* @return string 
	*/
	function getPaymentState(){
		return $this->statemess;
	}
	
	/**
	* Возвращает выписку по физ. мерчанту
	*
	* Возвращает выписку по физ. мерчанту
	*
	* @param string $sd Начало периода. Формат дд.мм.гггг
	* @param string $ed Конец периода. Формат дд.мм.гггг
	* @param string $card Номер карты
	* @param int $test Признак тестового платежа (0 - платёж будет проведён немедленно, 1 - платёж будет проверен на корректность, но не будет проведён)
	* @return array Массив, первый элемент которого - STATEMENTS, второй - массив STATEMENT 
	*/
	function GetAccountExtractPhys($sd,$ed,$card,$test){
		$this->errmess = "no error";
		$data = '<oper>cmt</oper><wait>0</wait><test>'.$test.'</test><payment id=""><prop name="sd" value="'.$sd.'"/><prop name="ed" value="'.$ed.'"/><prop name="card" value="'.$card.'"/></payment>';
		
		$str = '<?xml version="1.0" encoding="UTF-8"?><request version="1.0"><merchant>';
		$str .= '<id>'.$this->merchant_id.'</id>';
		$str .= '<signature>'.$this->calcSignature($data).'</signature>';
		$str .= '</merchant><data>';
		$str .= $data;
		$str .= '</data></request>';
		$request_result =  $this->msoap($str,"https://api.privatbank.ua/p24api/rest_fiz");
			
		/*Разбиваем XML на массив*/
		 $p = xml_parser_create(); 
		 xml_parse_into_struct($p, $request_result, $vals, $index); 
		 xml_parser_free($p); 

		foreach($vals as $el) {
			if($el['tag'] == 'ERROR'){
				$this->errmess = $el['attributes']['MESSAGE'];return -1;}	
			if($el['tag'] == 'STATEMENTS' && $el['type'] == 'open')
				$main_statement = $el['attributes'];
			if($el['tag'] == 'STATEMENTS' && $el['type'] == 'complete')
				$main_statement_complete = $el['attributes'];
		}
			if(empty($main_statement) && empty($main_statement_complete))
				foreach($vals as $el) 
					if($el['tag'] == 'INFO'){
						$this->errmess = $el['value'];return -1;}

		foreach($vals as $el) {
			if($el['tag'] == 'STATEMENT' && $el['type'] == 'complete')
				$statements[] = $el['attributes'];	
		}
		
 		if(empty($main_statement)){
		$mes = "Нет выписок за данный период!";
		$result[] = $main_statement_complete;
		$result[] = $mes;

		return $result;
		}
		else{			
		$result[] = $main_statement;
		$result[] = $statements;

		return $result;		
		}
	}
	
	/**
	* Возвращает выписку по юр. мерчанту
	*
	* Возвращает выписку по юр. мерчанту
	*
	* @param string $year Год
	* @param string $month Месяц в численном формате
	* @param int $test Признак тестового платежа (0 - платёж будет проведён немедленно, 1 - платёж будет проверен на корректность, но не будет проведён)
	* @return array Массив, первый элемент которого - STATEMENTS, второй - массив STATEMENT 
	*/
	function GetAccountExtractJur($year,$month,$test){
		$this->errmess = "no error";
		$data = '<oper>cmt</oper><wait>0</wait><test>'.$test.'</test><payment id=""><prop name="year" value="'.$year.'"/><prop name="month" value="'.$month.'"/></payment>';
		
		$str = '<?xml version="1.0" encoding="UTF-8"?><request version="1.0"><merchant>';
		$str .= '<id>'.$this->merchant_id.'</id>';
		$str .= '<signature>'.$this->calcSignature($data).'</signature>';
		$str .= '</merchant><data>';
		$str .= $data;
		$str .= '</data></request>';
		$request_result =  $this->msoap($str,"https://api.privatbank.ua/p24api/rest_yur");
		
		
		$file = "jur.xml";
		// Пишем содержимое обратно в файл
		file_put_contents($file, $request_result);
		
		$p = xml_parser_create(); 
		xml_parse_into_struct($p, $request_result, $vals, $index); 
		xml_parser_free($p);
		$col = array();
		$result = array();
		foreach($vals as $el) {
			if($el['tag'] == 'ERROR'){
				$this->errmess = $el['attributes']['MESSAGE'];return -1;}
			if($el['tag'] == 'COL')
				$col[] = $el['attributes'];
			if($el['tag'] == 'ROW' && $el['type'] == 'open')
				$row = $el['attributes'];
		}
		
 		if(empty($row)){
			$mes = "Нет выписок за данный месяц / год";
			$result[] = $mes; return $result;} 
		
		$result[] = $row;
		$result[] = $col;
		
		return $result;
			
	}
	
	/**
	* Вычисление сигнатуры
	*
	* Вычисление сигнатуры
	*
	* @param string $data Данные для хэширования
	* @return string 
	*/
	function calcSignature($data) { // расчёт сигнатуры
		return sha1(md5($data.$this->merchant_password));
	}
	
	/**
	* Обработка ответа от сервера в случае успешной оплаты
	*
	* Обрабатывает ответ в виде POST-запроса от сервера (return_url)
	*
	*/
	function GetPaymentResult(){
		$result = array();
		if(empty($_POST))
			$this->err="Пустой ответ";

		if(empty($_POST["payment"]) || empty($_POST["signature"]))
			$this->err="Пустой ответ";

		
		$signature = $this->calcSignature($_POST["payment"]);
		if(strcmp ($signature,$_POST["signature"]) == 0){
		//	print "<h2>Ответ от сервера</h2>";
			$pieces = explode("&", $_POST["payment"]);
			foreach($pieces as $piece){
				$t = explode("=", $piece);
				$result["$t[0]"] = $t[1]; 
			}
/* 			foreach($result as $key=>$value)
				print($key.": ".$value."<br>"); */
		}
		else
			$this->errmess = "Сигнатуры различаются";
		
		return $result;
		
	}
	
	/**
	* Обработка ответа от сервера в случае успешной оплаты
	*
	* Обрабатывает ответ в виде POST-запроса от сервера (server_url)
	*
	* @param string $xml XML-запрос для отправки
	* @param string $url Адрес отсправки
	*/
	function GetPaymentStatus(){
		return $this->GetPaymentResult();
	}
	
	/*Метод передачи XML запроса*/
	function msoap($xml,$url) { 
		$header = array();
		$header[] = "Content-Type: text/xml";
		$header[] = "\r\n"; 
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);		
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xml); 
		$rez = curl_exec($ch); 
		curl_close($ch);
		
		return $rez;
	}	
}
?>