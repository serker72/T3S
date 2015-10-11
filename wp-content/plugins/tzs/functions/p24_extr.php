<?php
	include "privat24api.php";
	$obj = new privat24api(112273, "7aG38HK0tavfqft1i413No1kTRdX9WXh");

	echo '<pre>';
	print("GetAccountExtractPhys: "."<br>");
        $a1 = $obj->GetAccountExtractPhys("01.09.2015", "11.10.2015", "4731185603763589", 1);
	print_r($a1);
	print(" Ошибка: ".$obj->getErrorMessage()."<br>");
        
        //$a3 = preg_grep("/ ([A-Z]{2}).(\d*).(\d*).(\d*)$/", $a1[1][$i]['TERMINAL'], $a2);
        for ($i=0;$i < count($a1[1]);$i++) {
            $a3 = preg_match("/ (\d*)$/", $a1[1][$i]['TERMINAL'], $a2);
            if ($a2[0] <> '') {
                echo 'i='.$i.' a3='.$a3.'<br>';
                print_r($a2);
            }
        }
	echo '</pre>';

?>