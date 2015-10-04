<?php
	include "privat24api.php";
	$obj = new privat24api(112273, "7aG38HK0tavfqft1i413No1kTRdX9WXh");

	echo '<pre>';
	print("GetAccountExtractPhys: "."<br>");
	print_r($obj->GetAccountExtractPhys("01.09.2015", "04.10.2015", "4731185603763589", 1));
	print(" Ошибка: ".$obj->getErrorMessage()."<br>");
	echo '</pre>';

?>