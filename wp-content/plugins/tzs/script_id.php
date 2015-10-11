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
	
	
	
	$tables_names = array();
  	$sql_tables = mysql_query("SHOW TABLES");
	while($tables = mysql_fetch_array($sql_tables,MYSQL_NUM)){
		$tables_names[] = $tables[0];
	}
	$sql = "SELECT * FROM ".TZS_COUNTRIES_TABLE;
	$result = mysql_query($sql,$conn);

	while($row = mysql_fetch_array($result)) {
		print_r($row); print('<br>');
	}
	
	
	
	/*$table="calls";
	for($i = 0; $i<10;$i++){
		$column_types = array();
		$column_names = array();
		$sql = "SELECT * FROM $table";
		$result = mysql_query($sql,$conn);
		while($field = mysql_fetch_field($result)) {
			$column_types[] = $field->type;
			$column_names[] = $field->name;
		}

		$field_values = array();
		foreach($column_types as $type){
			switch($type){
				case "int": $res = rand(1,99999);break;
				case "string": $res = "'".generateRandomString()."'";break;
				case "date": $res = "'".randomDate()."'";break;
				default: $res = 0; break;
			}
			$field_values[] = $res;
		}
		$into = "";
		for($j=0;$j<count($column_names);$j++)
			$into.=$column_names[$j].",";
		$into = substr($into,0,-1);

		
		for($k=0;$k<count($field_values);$k++){
			$field_values[1] = rand(1,10);
			$field_values[6] = rand(1,10);
		}
		
		$values = "";
		for($k=1;$k<count($field_values);$k++)
			$values.=$field_values[$k].",";
		$values = substr($values,0,-1);

		//Корректировка внешнего ключа

		
		$request=<<<"HERE"
		INSERT INTO  $table($into)
		VALUES (NULL,$values);	
HERE;

		$sql = $request;
		//$result = mysql_query($sql,$conn);
		print $sql."<br>";

	}*/
}

normalize_ids();
	
?>