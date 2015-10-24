<?php
define( 'TZS_TABLE_PREFIX', "wp_tzs_" );
global $wpdb;
define( 'TZS_SHIPMENT_TABLE', $wpdb->prefix . TZS_TABLE_PREFIX . "shipments" );
define( 'TZS_TRUCK_TABLE', $wpdb->prefix . TZS_TABLE_PREFIX . "trucks" );
define( 'TZS_COUNTRIES_TABLE', $wpdb->prefix . TZS_TABLE_PREFIX . "countries" );
define( 'TZS_REGIONS_TABLE', $wpdb->prefix . TZS_TABLE_PREFIX . "regions" );
define( 'TZS_CITIES_TABLE', $wpdb->prefix . TZS_TABLE_PREFIX . "cities" );
define( 'TZS_CITY_IDS_TABLE', $wpdb->prefix . TZS_TABLE_PREFIX . "city_ids" );
define( 'TZS_PRODUCTS_TABLE', $wpdb->prefix . TZS_TABLE_PREFIX . "products" );



function normalize_ids($url="localhost",$login="root",$password=""){
	$conn = mysql_connect($url,$login,$password);
	if (!$conn)
		die('Could not connect: ' . mysql_error());
	mysql_select_db("t3s",$conn);

	$sql = "SELECT * FROM ".TZS_COUNTRIES_TABLE;
	$result = mysql_query($sql,$conn);
	
	/* Замена id-шек стран
	 */
        echo '<p>Замена id-шек стран - '.date('d.m.Y H:i:s').'<br>';
		$country_id_new = array();
		$country_id_old = array();
	while($row_country = mysql_fetch_array($result)) {
		$country_id_new[] += substr(preg_replace('~\D+~','',sha1(md5($row_country['title_ru']))),0,8);
		$country_id_old[] += $row_country['country_id'];
        }
        
        for($i=0;$i < count($country_id_new);$i++) {
		if($country_id_new[$i] != $country_id_old[$i]){
			//echo 'false '; echo $country_id_new; echo ' ';echo $country_id_old; echo '<br>';
			
 			$sql = "UPDATE `".TZS_COUNTRIES_TABLE."` SET country_id=".$country_id_new[$i]." WHERE country_id=".$country_id_old[$i];
 			echo $sql.'<br>';
 			mysql_query($sql,$conn);
			
			$sql = "UPDATE ".TZS_REGIONS_TABLE." SET country_id=".$country_id_new[$i]." WHERE country_id=".$country_id_old[$i];
 			echo $sql.'<br>';
			mysql_query($sql,$conn);
			
			$sql = "UPDATE ".TZS_CITIES_TABLE." SET country_id=".$country_id_new[$i]." WHERE country_id=".$country_id_old[$i];
 			echo $sql.'<br>';
			mysql_query($sql,$conn);
			
			$sql = "UPDATE ".TZS_TRUCK_TABLE." SET from_cid=".$country_id_new[$i]." WHERE from_cid=".$country_id_old[$i];
 			echo $sql.'<br>';
			mysql_query($sql,$conn);
			
			$sql = "UPDATE ".TZS_SHIPMENT_TABLE." SET from_cid=".$country_id_new[$i]." WHERE from_cid=".$country_id_old[$i];
 			echo $sql.'<br>';
			mysql_query($sql,$conn);
			
			$sql = "UPDATE ".TZS_PRODUCTS_TABLE." SET from_cid=".$country_id_new[$i]." WHERE from_cid=".$country_id_old[$i];
 			echo $sql.'<br>';
			mysql_query($sql,$conn);
			
			$sql = "UPDATE ".TZS_TRUCK_TABLE." SET to_cid=".$country_id_new[$i]." WHERE to_cid=".$country_id_old[$i];
 			echo $sql.'<br>';
			mysql_query($sql,$conn);
				
			$sql = "UPDATE ".TZS_SHIPMENT_TABLE." SET to_cid=".$country_id_new[$i]." WHERE to_cid=".$country_id_old[$i];
 			echo $sql.'<br>';
			mysql_query($sql,$conn);
				
			$sql = "UPDATE ".TZS_PRODUCTS_TABLE." SET to_cid=".$country_id_new[$i]." WHERE to_cid=".$country_id_old[$i];
 			echo $sql.'<br>';
			mysql_query($sql,$conn);
			
		}
	}
		echo '</p>';
                

		/* Замена id-шек регионов
		 */
        echo '<p>Замена id-шек регионов - '.date('d.m.Y H:i:s').'<br>';
		$sql = "SELECT * FROM ".TZS_REGIONS_TABLE;
		$result = mysql_query($sql,$conn);
		
		while($row_region = mysql_fetch_array($result)) {
			$region_id_new = (int)substr(preg_replace('~\D+~','',sha1(md5($row_region['title_ru']))),0,8);
			$region_id_old = $row_region['region_id'];
		
			if($region_id_new != $region_id_old){
				//echo 'false '; echo $country_id_new; echo ' ';echo $country_id_old; echo '<br>';
					
				$sql = "UPDATE ".TZS_REGIONS_TABLE." SET region_id=".$region_id_new." WHERE region_id=".$region_id_old;
 			echo $sql.'<br>';
				mysql_query($sql,$conn);
					
				$sql = "UPDATE ".TZS_CITIES_TABLE." SET region_id=".$region_id_new." WHERE region_id=".$region_id_old;
 			echo $sql.'<br>';
				mysql_query($sql,$conn);
					
				$sql = "UPDATE ".TZS_TRUCK_TABLE." SET from_rid=".$region_id_new." WHERE from_rid=".$region_id_old;
 			echo $sql.'<br>';
				mysql_query($sql,$conn);
					
				$sql = "UPDATE ".TZS_SHIPMENT_TABLE." SET from_rid=".$region_id_new." WHERE from_rid=".$region_id_old;
 			echo $sql.'<br>';
				mysql_query($sql,$conn);
				
				$sql = "UPDATE ".TZS_PRODUCTS_TABLE." SET from_rid=".$region_id_new." WHERE from_rid=".$region_id_old;
 			echo $sql.'<br>';
				mysql_query($sql,$conn);
				
				$sql = "UPDATE ".TZS_TRUCK_TABLE." SET to_rid=".$region_id_new." WHERE to_rid=".$region_id_old;
 			echo $sql.'<br>';
				mysql_query($sql,$conn);
					
				$sql = "UPDATE ".TZS_SHIPMENT_TABLE." SET to_rid=".$region_id_new." WHERE to_rid=".$region_id_old;
 			echo $sql.'<br>';
				mysql_query($sql,$conn);
				
				$sql = "UPDATE ".TZS_PRODUCTS_TABLE." SET to_rid=".$region_id_new." WHERE to_rid=".$region_id_old;
 			echo $sql.'<br>';
				mysql_query($sql,$conn);
					
			}
		}
		echo '</p>';
                
		
		
		normalize_coordinates($conn);
		
		
		/* Замена id-шек городов
		 */

		
        echo '<p>Замена id-шек городов - '.date('d.m.Y H:i:s').'<br>';
		$sql = "SELECT * FROM ".TZS_CITIES_TABLE;
		$result = mysql_query($sql,$conn);

		while($row_city = mysql_fetch_array($result)) {
				
				$city_id_new = substr(preg_replace('~\D+~','',sha1(md5($row_city['title_ru'].number_format($row_city['lat'],3).number_format($row_city['lng'],3)))),0,8);
				$city_id_old = $row_city['city_id'];

				
			if($city_id_new != $city_id_old){
				
				$sql = "UPDATE ".TZS_CITIES_TABLE." SET city_id=".$city_id_new." WHERE city_id=".$city_id_old;
 			echo $sql.'<br>';
				mysql_query($sql,$conn);
					
				$sql = "UPDATE ".TZS_TRUCK_TABLE." SET from_sid=".$city_id_new." WHERE from_sid=".$city_id_old;
 			echo $sql.'<br>';
				mysql_query($sql,$conn);
					
				$sql = "UPDATE ".TZS_SHIPMENT_TABLE." SET from_sid=".$city_id_new." WHERE from_sid=".$city_id_old;
 			echo $sql.'<br>';
				mysql_query($sql,$conn);
				
				$sql = "UPDATE ".TZS_PRODUCTS_TABLE." SET from_sid=".$city_id_new." WHERE from_sid=".$city_id_old;
 			echo $sql.'<br>';
				mysql_query($sql,$conn);
				
				$sql = "UPDATE ".TZS_TRUCK_TABLE." SET to_sid=".$city_id_new." WHERE to_sid=".$city_id_old;
 			echo $sql.'<br>';
				mysql_query($sql,$conn);
					
				$sql = "UPDATE ".TZS_SHIPMENT_TABLE." SET to_sid=".$city_id_new." WHERE to_sid=".$city_id_old;
 			echo $sql.'<br>';
				mysql_query($sql,$conn);
				
				$sql = "UPDATE ".TZS_PRODUCTS_TABLE." SET to_sid=".$city_id_new." WHERE to_sid=".$city_id_old;
 			echo $sql.'<br>';
				mysql_query($sql,$conn);
					
			}
		
		}
		echo '</p>';
                
		/* Замена в таблице ids
		 */
        echo '<p>Замена в таблице ids - '.date('d.m.Y H:i:s').'<br>';
			$sql = "SELECT * FROM ".TZS_CITY_IDS_TABLE;
			$result = mysql_query($sql,$conn);
	
			while($row_title = mysql_fetch_array($result)) {
				$ids_old = $row_title['ids'];
				$city_str = $row_title['title'];
				
				$url = "https://geocode-maps.yandex.ru/1.x/?format=json&results=1000&geocode=$city_str";
		
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_URL, $url);
				$result_=curl_exec($ch);
				curl_close($ch);
		
				$res = json_decode($result_, true);
				
				$cities = find_all_1($res,'name');	
				$kinds = find_all_1($res,'kind');
		
				$latitude_longitude = find_all_1($res,'pos');
				$ids = array();
				for($i = 0; $i < count($cities); $i++){
					$pieces = explode(' ',$latitude_longitude[$i]);
					$lat = substr($pieces[0],0,6);
					$lng = substr($pieces[1],0,6);
					$ids[] = (int)substr(preg_replace('~\D+~','',sha1(md5($cities[$i].$lat.$lng))),0,8);
				}
				
				$ids_new = implode(' ',$ids);
				//echo $ids; echo '<br>';
				//echo $ids_old; echo '-'; echo $ids_new; echo '<br>';
				
				if($ids_new != $ids_old){
					$sql = "UPDATE ".TZS_CITY_IDS_TABLE." SET ids='".$ids_new."' WHERE title='".$city_str."'";
				//	echo $city_str; echo ' '; echo $sql; echo '<br>';
 			echo $sql.'<br>';
 					mysql_query($sql,$conn);
 				}
		
			}
		echo '</p>';
                
				
			
}

function normalize_coordinates($conn){
	
	$sql = "SELECT * FROM ".TZS_CITIES_TABLE;
	$result = mysql_query($sql,$conn);
	
	while($row_city = mysql_fetch_array($result)) {
		//echo $row_city['lat'].' '.$row_city['lng'].'<br>';
		
		$sql = "SELECT title_ru FROM ".TZS_COUNTRIES_TABLE.' WHERE country_id='.$row_city['country_id'];
		$cresult = mysql_query($sql,$conn);
		$row = mysql_fetch_assoc($cresult);
		$city_str = $row['title_ru'];
		
		$sql = "SELECT title_ru FROM ".TZS_REGIONS_TABLE.' WHERE country_id='.$row_city['country_id'].' AND region_id='.$row_city['region_id'];
	//	echo $sql.'<br>';
		$cresult = mysql_query($sql,$conn);
		if (!$cresult)
			die('Ошибка: ' . mysql_error());
		$row = mysql_fetch_assoc($cresult);
		$city_str = $city_str.' '.$row['title_ru'];
		
		$city_str = $city_str." ".$row_city['title_ru'];
		
		
		echo $city_str.'<br>';
		
		$url = "https://geocode-maps.yandex.ru/1.x/?format=json&results=1000&geocode=$city_str";
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, $url);
		$result_=curl_exec($ch);
		curl_close($ch);

		$res = json_decode($result_, true);
		
		$coords = $pieces = explode(" ", find($res,'pos'));
		$lat = substr($coords[1],0,6);
		$lng = substr($coords[0],0,6);
		
		echo $row_city['lat'].' '.$row_city['lng'].' -> '.$lat.' '.$lng.'<br>';
			
		$sql = "UPDATE ".TZS_CITIES_TABLE." SET lat=".$lat." WHERE lat=".$row_city['lat'];
 			echo $sql.'<br>';
		mysql_query($sql,$conn);
	
		$sql = "UPDATE ".TZS_CITIES_TABLE." SET lng=".$lng." WHERE lng=".$row_city['lng'];
 			echo $sql.'<br>';
		mysql_query($sql,$conn);
	}
	
}

function find($array,$sNeededKey){
	preg_match('/s\:[\d]+\:\"'.preg_quote($sNeededKey).'\";s\:[\d]+\:\"(.*?)\"/', serialize($array), $rgMatches);
	$sResult    = $rgMatches[1];
	return $sResult;
}

function find_all_1($array,$sNeededKey){
	$places = find_all($array,$sNeededKey);
	//	print_r($places); echo '<br>';
	$kinds = find_all($array,'kind');
	//	print_r($kinds); echo '<br>';
	$result = array();

	for($i = 0; $i < count($kinds); $i++)
		if($kinds[$i] == 'locality')
			$result[] = $places[$i];
		return $result;
}

function find_all($array,$sNeededKey){
	$all_values = array();
	array_walk_recursive($array, function($sValue, $sKey) use ($sNeededKey,&$all_values)
	{
		if($sKey == $sNeededKey)
		{
			$all_values[] = $sValue;
		}
	});
	return $all_values;
}



normalize_ids();
	
?>