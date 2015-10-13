<?
define( 'TZS_TABLE_PREFIX', "wp_tzs_" );
global $wpdb;
define( 'TZS_SHIPMENT_TABLE', $wpdb->prefix . TZS_TABLE_PREFIX . "shipments" );
define( 'TZS_TRUCK_TABLE', $wpdb->prefix . TZS_TABLE_PREFIX . "trucks" );
define( 'TZS_COUNTRIES_TABLE', $wpdb->prefix . TZS_TABLE_PREFIX . "countries" );
define( 'TZS_REGIONS_TABLE', $wpdb->prefix . TZS_TABLE_PREFIX . "regions" );
define( 'TZS_CITIES_TABLE', $wpdb->prefix . TZS_TABLE_PREFIX . "cities" );
define( 'TZS_CITY_IDS_TABLE', $wpdb->prefix . TZS_TABLE_PREFIX . "city_ids" );



function normalize_ids($url="localhost",$login="root",$password=""){
	$conn = mysql_connect($url,$login,$password);
	if (!$conn)
		die('Could not connect: ' . mysql_error());
	mysql_select_db("t3s",$conn);

	$sql = "SELECT * FROM ".TZS_COUNTRIES_TABLE;
	$result = mysql_query($sql,$conn);

	/* Замена id-шек стран
	 */
	while($row_country = mysql_fetch_array($result)) {
		$country_id_new = (int)substr(preg_replace('~\D+~','',sha1(md5($row_country['title_ru']))),0,8);
		$country_id_old = $row_country['country_id'];

		if($country_id_new != $country_id_old){
			//echo 'false '; echo $country_id_new; echo ' ';echo $country_id_old; echo '<br>';
			
 			$sql = "UPDATE ".TZS_COUNTRIES_TABLE." SET country_id=".$country_id_new." WHERE country_id=".$country_id_old;
 			mysql_query($sql,$conn);
			
			$sql = "UPDATE ".TZS_REGIONS_TABLE." SET country_id=".$country_id_new." WHERE country_id=".$country_id_old;
			mysql_query($sql,$conn);
			
			$sql = "UPDATE ".TZS_CITIES_TABLE." SET country_id=".$country_id_new." WHERE country_id=".$country_id_old;
			mysql_query($sql,$conn);
			
			$sql = "UPDATE ".TZS_TRUCK_TABLE." SET from_cid=".$country_id_new." WHERE from_cid=".$country_id_old;
			mysql_query($sql,$conn);
			
			$sql = "UPDATE ".TZS_SHIPMENT_TABLE." SET from_cid=".$country_id_new." WHERE from_cid=".$country_id_old;
			mysql_query($sql,$conn);
			
		}
	}

		/* Замена id-шек регионов
		 */
		$sql = "SELECT * FROM ".TZS_REGIONS_TABLE;
		$result = mysql_query($sql,$conn);
		
		while($row_region = mysql_fetch_array($result)) {
			$region_id_new = (int)substr(preg_replace('~\D+~','',sha1(md5($row_region['title_ru']))),0,8);
			$region_id_old = $row_region['region_id'];
		
			if($region_id_new != $region_id_old){
				//echo 'false '; echo $country_id_new; echo ' ';echo $country_id_old; echo '<br>';
					
				$sql = "UPDATE ".TZS_REGIONS_TABLE." SET region_id=".$region_id_new." WHERE region_id=".$region_id_old;
				mysql_query($sql,$conn);
					
				$sql = "UPDATE ".TZS_CITIES_TABLE." SET region_id=".$region_id_new." WHERE region_id=".$region_id_old;
				mysql_query($sql,$conn);
					
				$sql = "UPDATE ".TZS_TRUCK_TABLE." SET from_rid=".$region_id_new." WHERE from_rid=".$region_id_old;
				mysql_query($sql,$conn);
					
				$sql = "UPDATE ".TZS_SHIPMENT_TABLE." SET from_rid=".$region_id_new." WHERE from_rid=".$region_id_old;
				mysql_query($sql,$conn);
					
			}
		
		}
		
		/* Замена id-шек городов
		 */
		$sql = "SELECT * FROM ".TZS_CITIES_TABLE;
		$result = mysql_query($sql,$conn);
		
		while($row_city = mysql_fetch_array($result)) {
			$city_id_new = (int)substr(preg_replace('~\D+~','',sha1(md5($row_city['title_ru'].$row_city['lat'].$row_city['lng']))),0,8);
			$city_id_old = $row_city['city_id'];
		
			if($city_id_new != $city_id_old){
										
				$sql = "UPDATE ".TZS_CITIES_TABLE." SET city_id=".$city_id_new." WHERE city_id=".$city_id_old;
				mysql_query($sql,$conn);
					
				$sql = "UPDATE ".TZS_TRUCK_TABLE." SET from_cid=".$city_id_new." WHERE from_cid=".$city_id_old;
				mysql_query($sql,$conn);
					
				$sql = "UPDATE ".TZS_SHIPMENT_TABLE." SET from_cid=".$city_id_new." WHERE from_cid=".$city_id_old;
				mysql_query($sql,$conn);
					
			}
		
		}
				
		/* Замена в таблице ids
		 */
		
		//Будет после дальнейшего согласования
		
		// 		$city_str='Теплик Украина';
		// 		$url = "https://geocode-maps.yandex.ru/1.x/?format=json&kind=locality&geocode=$city_str";
		
		// 		$ch = curl_init();
		// 		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		// 		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// 		curl_setopt($ch, CURLOPT_URL, $url);
		// 		$result=curl_exec($ch);
		// 		curl_close($ch);
		
		// 		$res = json_decode($result, true);
		
		// 		print_r($res);
			
}

normalize_ids();
	
?>