<?php
function current_page_url() {
	$pageURL = 'http';
	if( isset($_SERVER["HTTPS"]) ) {
		if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	}
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	return $pageURL;
}

function current_page_number() {
	$url = current_page_url();
	
	$page = 1;
	$us = explode('/', $url);
	
	$idx = count($us)-1;
	if (strlen(trim($us[$idx])) == 0 || !is_numeric(trim($us[$idx])))
		$idx--;
	
	if (is_numeric(trim($us[$idx])))
		$page = intval($us[$idx]);
	
	if ($page <= 0)
		$page = 1;
	
	return $page;
}

function build_page_url($page) {
	$us = explode('/', current_page_url());
	
	$idx = count($us)-1;
	if ((strlen(trim($us[$idx])) == 0 || !is_numeric(trim($us[$idx]))) && is_numeric(trim($us[$idx-1])))
		$idx--;
	
	$us[$idx] = $page;
	if (strlen(trim($us[count($us)-1])) > 0 && is_numeric(trim($us[count($us)-1])))
		array_push($us, '');
	return implode('/', $us);
}

function build_pages_footer($page, $pages, $tag="page") {
	if ($pages < 2)
		return;
	?>
	<div id="pages_container">
	<?php
	if ($page > 1) {
		?>
			<a tag="<?php echo $tag;?>" page="<?php echo $page-1;?>" href="<?php echo build_page_url($page-1);?>">« Предыдущая</a>
			&nbsp;&nbsp;&nbsp;
		<?php
	}
	$start = 1;
	$stop = $pages;
	if ($pages > TZS_MAX_PAGES) {
		if ($page > TZS_MAX_PAGES_2) {
			$start = $page - TZS_MAX_PAGES_2;
		}
		if ($pages - $page > TZS_MAX_PAGES_2) {
			$stop = $page + TZS_MAX_PAGES_2;
		}
		
		if ($stop - $start < TZS_MAX_PAGES) {
			if ($pages - $stop < TZS_MAX_PAGES_2) {
				$start -= TZS_MAX_PAGES - ($stop - $start);
			} else if ($start < TZS_MAX_PAGES_2) {
				$stop += TZS_MAX_PAGES - ($stop - $start);
			}
		}
	}
	
	if ($start > 1) {
		?>
			<a tag="<?php echo $tag;?>" page="1" href="<?php echo build_page_url(1);?>">1</a>
			&nbsp;
		<?php
	}
	if ($start > 2) {
		?>
			...
			&nbsp;
		<?php
	}
	
	//echo ">>$start<<>>$stop<<";
	for ($i = $start; $i <= $stop; $i++) {
		if ($i == $page) {
			?>
			<span><?php echo $i;?></span>
			&nbsp;
			<?php
		} else {
			$url = build_page_url($i);
			?>
			<a tag="<?php echo $tag;?>" page="<?php echo $i;?>" href="<?php echo $url;?>"><?php echo $i;?></a>
			&nbsp;
			<?php
		}
	}
	if ($stop < $pages-1) {
		?>
			...
			&nbsp;
		<?php
	}
	if ($stop < $pages) {
		?>
			<a tag="<?php echo $tag;?>" page="<?php echo $pages;?>" href="<?php echo build_page_url($pages);?>"><?php echo $pages;?></a>
		<?php
	}
	if ($page < $pages) {
		?>
			&nbsp;&nbsp;&nbsp;
			<a tag="<?php echo $tag;?>"page="<?php echo $page+1;?>"  href="<?php echo build_page_url($page+1);?>">Следующая »</a>
		<?php
	}
	?>
	</div>
	<?php
}

function get_timezone_offset($remote_tz, $origin_tz = null) {
    if($origin_tz === null) {
        if(!is_string($origin_tz = date_default_timezone_get())) {
            return false; // A UTC timestamp was returned -- bail out!
        }
    }
    $origin_dtz = new DateTimeZone($origin_tz);
    $remote_dtz = new DateTimeZone($remote_tz);
    $origin_dt = new DateTime("now", $origin_dtz);
    $remote_dt = new DateTime("now", $remote_dtz);
    $offset = $origin_dtz->getOffset($origin_dt) - $remote_dtz->getOffset($remote_dt);
    
    return $offset;
}


function convert_time($time, $format = "d.m.Y H:i") {
    if (isset($_SESSION['timezone_offset_enabled']) && isset($_SESSION['timezone_offset']) && $_SESSION['timezone_offset_enabled'] && is_numeric($_SESSION['timezone_offset'])) {
        $timezone_offset = $_SESSION['timezone_offset'];
    } else {
        $timezone_offset = 0;
    }
    
    return date($format, strtotime($time) + $timezone_offset*3600);
}

function convert_time_only($time) {
    if (isset($_SESSION['timezone_offset_enabled']) && $_SESSION['timezone_offset_enabled'] && is_numeric($_SESSION['timezone_offset'])) {
        $timezone_offset = $_SESSION['timezone_offset'];
    } else {
        $timezone_offset = 0;
    }
    
    return date("H:i", strtotime($time) + $timezone_offset*3600);
}

function convert_date($date) {
	return date("d.m.Y", strtotime($date));
}

function convert_date_year2($date) {
    return date("d.m.y", strtotime($date));
}

function convert_date_no_year($date) {
	$cy = date("Y");
	$dy = date("Y", strtotime($date));
	return $cy == $dy ? date("d.m", strtotime($date)) : date("d.m.Y", strtotime($date));
}

function remove_decimal_part($num) {
	return $num == intval($num) ? intval($num) : $num;
}

function print_error($error) {
        echo '<div style="clear: both;"></div>';
	echo '<div class="errors">';
	echo '<div class="error">'.$error.'</div>';
	echo '</div>';
}

function print_errors($errors) {
	if ($errors != null && count($errors) > 0) {
		echo '<div class="errors">';
		foreach ($errors as $error)
			echo '<div class="error">'.$error.'</div>';
		echo '</div>';
	}
}

function get_param($name) {
	if (isset($_POST[$name]))
		return trim($_POST[$name]);
	return '';
}

function get_param_def($name, $def) {
	if (isset($_POST[$name])) {
		$val = trim($_POST[$name]);
		return strlen($val) > 0 ? $val : $def;
	}
	return $def;
}

function is_valid_date($d) {
	$date = date_parse_from_format('d.m.Y', $d);
	if ($date['error_count'] > 0)
		return null;
	return $date;
}

function is_valid_city($city) {
	return strlen($city) > 1;
}

function is_valid_num($num) {
	return is_numeric($num) && floatval($num) > 0;
}

function is_valid_num_zero($num) {
	return is_numeric($num) && floatval($num) >= 0;
}

function echo_val($name) {
	if (isset($_POST[$name])) echo htmlspecialchars($_POST[$name]);
}

function echo_val_def($name, $def) {
	if (isset($_POST[$name])) echo htmlspecialchars($_POST[$name]);
	else echo htmlspecialchars($def);
}

function trans_types_to_str($t, $t2) {
	$type = $t > 0 && isset($GLOBALS['tzs_tr2_types'][$t]) ? $GLOBALS['tzs_tr2_types'][$t][0].'<br><img src="'.$GLOBALS['tzs_tr2_types'][$t][1].'"></img>' : "";
	if (strlen($type) == 0) {
		return "";
	} else {
		return $type;
	}
        
	/*$type = $t > 0 && isset($GLOBALS['tzs_tr_types'][$t]) ? $GLOBALS['tzs_tr_types'][$t] : "";
	$type2 = "";
	if ($t2 > 0 && isset($GLOBALS['tzs_tr2_types'][$t2])) {
		$t = $GLOBALS['tzs_tr2_types'][$t2];
		$type2 = $t[0].'<br><img src="'.$t[1].'"></img>';
	}
	if (strlen($type) == 0 && strlen($type2) == 0) {
		return "";
	} else if (strlen($type) > 0 && strlen($type2) > 0) {
		return $type.", ".$type2;
	} else if (strlen($type) > 0 && strlen($type2) == 0) {
		return $type;
	} else {
		return $type2;
	}*/
}

function tzs_cost_print_option($name, $value) {
	if (isset($_POST[$name]) && $_POST[$name] == $value)
		echo 'checked="checked"';
}

function tzs_cost_print_option_def($name) {
	if (isset($_POST[$name]))
		echo 'checked="checked"';
}

function tzs_convert_distance_to_str($distance, $meters) {
	return $meters ? "~ ".round($distance / 1000)." км" : "~ ".round($distance)." км";
}

function tzs_cities_to_str($city) {
	$counter = 0;
	$res = "";
	foreach ($city as $c) {
		if ($counter > 0)
			$res .= " — ";
		$res .= htmlspecialchars(stripslashes_deep($c));
		$counter++;
	}
	return $res;
}

function tzs_convert_time_to_str($seconds) {
	$h = floor($seconds / 3600);
	$m = ($seconds / 60) % 60;
	$res = '~ ';
	if ($h > 0)
		$res .= $h." ч ";
	$res .= $m." мин";
	return $res;
}

function tzs_calculate_distance($city) {
	$prev = null;
	$time = 0;
	$distance = 0;
	$results = 0;
	$errors = array();
	foreach ($city as $c) {
		if ($prev != null) {
			$from = urlencode($prev);
			$to = urlencode($c);
			$data = file_get_contents("http://maps.googleapis.com/maps/api/distancematrix/json?origins=$from&destinations=$to&language=en-EN&sensor=false");
			$data = json_decode($data);
	
			if ($data->status == 'OK' && $data->rows[0]->elements[0]->status == 'OK') {
				$distance += $data->rows[0]->elements[0]->distance->value;
				$time += $data->rows[0]->elements[0]->duration->value;
				$results++;
			 } else {
				array_push($errors, "Не удалось рассчитать расстояние между ".stripslashes_deep($prev)." и ".stripslashes_deep($c));
			} 
		}
		$prev = $c;
	}
	return array('time' => $time, 'distance' => $distance, 'errors' => $errors, 'results' => $results);
}

function tzs_make_distance_link_old($distance, $meters, $city) {
	$url = "/distance-calculator/?calc=&";
	$counter = 0;
	foreach ($city as $c) {
		if ($counter > 0)
			$url .= "&";
		$url .= "city[]=";
		$url .= urlencode($c);
		$counter++;
	}
	return '<a class="distance_link" href="'.$url.'">'.tzs_convert_distance_to_str($distance, $meters).'</a>';
}

function tzs_encode($str) {
	return str_replace('\'', '\u0027', str_replace('\\\'', stripslashes_deep('\u0027'), json_encode($str)));
}

function str_split_unicode($str, $l = 0) {
    if ($l > 0) {
        $ret = array();
        $len = mb_strlen($str, "UTF-8");
        for ($i = 0; $i < $len; $i += $l) {
            $ret[] = mb_substr($str, $i, $l, "UTF-8");
        }
        return $ret;
    }
    return preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY);
}

// WHAT THE FUCK!?
function unicode_escape_sequences($str) {
	$working = json_encode($str);
	//$working = preg_replace('/\\\u([0-9a-z]{4})/', '\\u$1', $working);
	$working = preg_replace('/\\\u([0-9a-z]{4})/', '\u$1', $working);
	return json_decode($working);
}
function tzs_encode2($str) {
	return '"'.unicode_escape_sequences($str).'"';
}
// ---------------

function tzs_make_distance_link($distance, $meters, $city) {
	$url = "displayDistance([";
	$counter = 0;
	foreach ($city as $c) {
		if ($counter > 0)
			$url .= ',';
		$url .= tzs_encode($c);
		$counter++;
	}
	$url .="], null)";
	return '<a class="distance_link" href=\'javascript:'.$url.';\' title="Расчет расстояния между пунктами">'.tzs_convert_distance_to_str($distance, $meters).'</a>';
}

function tzs_price_query_to_str($row) {
    $str1 = ''; // цена
    $str2 = ''; // стоимость
    $str3 = ''; // форма оплаты
    
    // Если установлен переключатель "Не указывать стоимость (цена договорная)"
    if ($row->price_query || ($row->cost == 0)) {
        $str1 = 'договорная';
        $str2 = '';
        $str3 = 'не указана';
    } else {
        $str1 = number_format($row->cost, 0, '.', ' ').' '.$GLOBALS['tzs_curr'][$row->price_val];
        $str2 = $row->price.' '.$GLOBALS['tzs_curr'][$row->price_val].'/км';
        
        if ($row->cash) {
            if (strlen($str3) > 0) $str3 .= ', ';
            $str3 .= 'наличная';
        }
        
        if ($row->nocash) {
            if (strlen($str3) > 0) $str3 .= ', ';
            $str3 .= 'безналичная';
        }
        
        if ($row->way_ship) {
            if (strlen($str3) > 0) $str3 .= ', ';
            $str3 .= 'при погрузке';
        }
        
        if ($row->way_debark) {
            if (strlen($str3) > 0) $str3 .= ', ';
            $str3 .= 'при выгрузке';
        }
        
        if ($row->soft) {
            if (strlen($str3) > 0) $str3 .= ', ';
            $str3 .= 'софт';
        }
        
        if ($row->way_prepay) {
            if (strlen($str3) > 0) $str3 .= ', ';
            $str3 .= 'предоплата';
            if ($row->prepayment) {
                $str3 .= ': '.$row->prepayment.'%';
            }
        }
    }
    
    return array($str1, $str2, $str3);
}

function tzs_loading_types_to_str($row) {
    $str1 = '';
    
    if ($row->top_loading) {
        if (strlen($str1) > 0) $str1 .= ', ';
        $str1 .= 'верхняя';
    }
    
    if ($row->side_loading) {
        if (strlen($str1) > 0) $str1 .= ', ';
        $str1 .= 'боковая';
    }
    
    if ($row->back_loading) {
        if (strlen($str1) > 0) $str1 .= ', ';
        $str1 .= 'задняя';
    }
    
    if ($row->full_movable) {
        if (strlen($str1) > 0) $str1 .= ', ';
        $str1 .= 'без ворот';
    }
    
    if ($row->remove_cross) {
        if (strlen($str1) > 0) $str1 .= ', ';
        $str1 .= 'с полной растентовкой';
    }
    
    if ($row->remove_racks) {
        if (strlen($str1) > 0) $str1 .= ', ';
        $str1 .= 'со снятием поперечин';
    }
    
    if ($row->without_gate) {
        if (strlen($str1) > 0) $str1 .= ', ';
        $str1 .= 'со снятием стоек';
    }
    
    return $str1;
}

function tzs_cost_to_str($cost_str, $split_flag = false) {
	$cost = json_decode($cost_str, true);
	$str = '';
	$str1 = '';
	if (isset($cost['set_price']) && $cost['set_price'] == 1) {
		if (isset($cost['price'])) {
			$str1 .= $cost['price'];
			$str1 .= ' ';
		}
		if (isset($cost['cost_curr']) && isset($GLOBALS['tzs_curr'][$cost['cost_curr']])) {
			$str1 .= $GLOBALS['tzs_curr'][$cost['cost_curr']];
		}
		if (isset($cost['payment'])) {
			switch ($cost['payment']) {
				case "nocash":
					if (strlen($str) > 0) $str .= ', ';
					$str .= "без нал.";
					break;
				case "cash":
					if (strlen($str) > 0) $str .= ', ';
					$str .= "нал.";
					break;
				case "mix_cash":
					if (strlen($str) > 0) $str .= ', ';
					$str .= "комбинир.";
					break;
				case "soft":
					if (strlen($str) > 0) $str .= ', ';
					$str .= "софт";
					break;
				case "conv":
					if (strlen($str) > 0) $str .= ', ';
					$str .= "удобная";
					break;
				case "on_card":
					if (strlen($str) > 0) $str .= ', ';
					$str .= "на карту";
					break;
			}
		}
		if (isset($cost['payment_way_nds'])) {
			if (strlen($str) > 0) $str .= ', ';
			$str .= 'НДС';
		}
		if (isset($cost['payment_way_ship'])) {
			if (strlen($str) > 0) $str .= ', ';
			$str .= 'при погрузке';
		}
		if (isset($cost['payment_way_debark'])) {
			if (strlen($str) > 0) $str .= ', ';
			$str .= 'при выгрузке';
		}
		if (isset($cost['payment_way_prepay'])) {
			if (strlen($str) > 0) $str .= ', ';
			$str .= 'предоплата';
			if (isset($cost['prepayment']))
				$str .= ': '.$cost['prepayment'].'%';
		}
		if (isset($cost['payment_way_barg'])) {
			if (strlen($str) > 0) $str .= ', ';
			$str .= 'торг';
		}
	} else {
		if (strlen($str1) > 0) $str1 .= ', ';
		$str1 .= "договорная";
		$str = "не указана";
		if (isset($cost['price_query'])) {
			if (strlen($str1) > 0) $str1 .= ', ';
			$str1 .= 'запрос цены';
		}
	}
        
        if ($split_flag) { return array($str1, $str); }
        else {	return $str1.', '.$str; }
}

function tzs_print_array_options($arr, $capt, $name, $capt0=null) {
    $count = count($arr);
    $counter = 0;
    foreach ($arr as $key => $val) {
        echo '<option value="'.$key.'" ';
        if ((isset($_POST[$name]) && $_POST[$name] == $key) || (!isset($_POST[$name]) && $key == 0)) {
            echo 'selected="selected"';
        }
        echo '>';
        if ($key == 0) {
            if ($capt0) {
                echo $capt0;
            } else {
                echo '...';
            }
        } else {
            if ($counter == $count-1) {
                //echo ">";
            }
            echo $val.$capt;
        }
        echo '</option>';
        $counter++;
    }
}

function tzs_copy_get_to_post() {
	foreach ($_GET as $key => $value) {
		$_POST[$key] = $value;
	}
}

function tzs_print_user_table($user_id) {
	$user_info = get_userdata($user_id);
?>
	<table border="0" id="view_ship">
	<tr>
		<td>Название компании</td>
		<td><?php $meta = get_user_meta($user_id, 'company'); echo $meta[0]; //echo htmlspecialchars($meta[0]); ?></td>
	</tr>
	<tr>
		<td>Контактное лицо</td>
		<td><?php $meta = get_user_meta($user_id, 'fio'); echo $meta[0]; //echo htmlspecialchars($user_info->display_name); ?></td>
	</tr>
	<tr>
		<td>E-mail</td>
		<td><?php echo htmlspecialchars($user_info->user_email); ?></td>
	</tr>
	<tr>
		<td>Номера телефонов</td>
		<td><?php $meta = get_user_meta($user_id, 'telephone'); echo htmlspecialchars($meta[0]); ?></td>
	</tr>
	<tr>
		<td>Skype</td>
		<td><?php $meta = get_user_meta($user_id, 'skype'); echo htmlspecialchars($meta[0]); ?></td>
	</tr>
<!--	<tr>
		<td>ICQ</td>
		<td><?php /*$meta = get_user_meta($user_id, 'icq'); echo htmlspecialchars($meta[0]); */?></td>
	</tr>-->
	</table>
<?php
}

function tzs_print_user_table_ed($user_id) {
	$user_info = get_userdata($user_id);
?>
	 <div class="pull-left label-txt">
                        <label><strong>Название компании:</strong></label>
                    </div>
                    <div class="pull-left">
                        <?php $meta = get_user_meta($user_id, 'company'); echo $meta[0]; //echo htmlspecialchars($meta[0]); ?>
                    </div>
                    <div class="clearfix"></div>
                    <div class="pull-left label-txt">
                        <label><strong>Контактное лицо:</strong></label>
                    </div>
                    <div class="pull-left">
                        <?php $meta = get_user_meta($user_id, 'fio'); echo $meta[0]; //echo htmlspecialchars($user_info->display_name); ?>
                    </div>
                    <div class="clearfix"></div>
                    <div class="pull-left label-txt">
                        <label><strong>E-mail:</strong></label>
                    </div>
                    <div class="pull-left">
                        <?php echo htmlspecialchars($user_info->user_email); ?>
                    </div>
                    <div class="clearfix"></div>
                    <div class="pull-left label-txt">
                        <label><strong>Номера телефонов:</strong></label>
                    </div>
                    <div class="pull-left">
                        <?php $meta = get_user_meta($user_id, 'telephone'); echo htmlspecialchars($meta[0]); ?> 
                    </div>
                    <div class="clearfix"></div>
                    <div class="pull-left label-txt">
                        <label><strong>Skype:</strong></label>
                    </div>
                    <div class="pull-left">
                        <?php $meta = get_user_meta($user_id, 'skype'); echo htmlspecialchars($meta[0]); ?>
                    </div>
                    <div class="clearfix"></div>
    
<?php
}

/*
 * Построение выпадающего списка наименований стран
 */
function tzs_build_countries($name) {
	global $wpdb;
	
	$sql = "SELECT * FROM ".TZS_COUNTRIES_TABLE." ORDER BY FIELD(code, 'BY', 'RU', 'UA') DESC, title_ru ASC;";
	$res = $wpdb->get_results($sql);
	if (count($res) == 0 && $wpdb->last_error != null) {
		// do nothink
	} else {
		?>
			<option value="0">все страны</option>
			<option disabled>- - - - - - - -</option>
		<?php
		$counter = 0;
		foreach ( $res as $row ) {
			$country_id = $row->country_id;
			$title = $row->title_ru;
			?>
				<option value="<?php echo $country_id;?>" <?php
					if ((isset($_POST[$name]) && $_POST[$name] == $country_id)) {
						echo 'selected="selected"';
					}
				?>
				><?php echo $title;?></option>
			<?php
			if ($counter == 2) {
				?>
					<option disabled>- - - - - - - -</option>
				<?php
			}
			$counter++;
		}
	}
}

/*
 * Получение списка регионов по выбранной стране
 */
function tzs_get_regions() {
	$id = isset($_POST['id']) && is_numeric($_POST['id']) ? intval( $_POST['id'] ) : 0;
	$rid = isset($_POST['rid']) && is_numeric($_POST['rid']) ? intval( $_POST['rid'] ) : 0;
	if ($id <= 0) {
		?>
			<option value="0">все области</option>
		<?php
	} else {
		global $wpdb;
		
		$sql = "SELECT * FROM ".TZS_REGIONS_TABLE." WHERE country_id=$id ORDER BY title_ru ASC;";
		$res = $wpdb->get_results($sql);
		if (count($res) == 0 && $wpdb->last_error != null) {
			?>
				<option value="0">все области</option>
			<?php
		} else {
			?>
				<option value="0">все области</option>
			<?php
			$found = false;
			foreach ( $res as $row ) {
				if (!$found) {
					$found = true;
					?>
						<option disabled>- - - - - - - -</option>
					<?php
				}
				$region_id = $row->region_id;
				$title = $row->title_ru;
				?>
					<option value="<?php echo $region_id;?>" <?php
						if ($rid == $region_id) {
							echo 'selected="selected"';
						}
					?> ><?php echo $title;?></option>
				<?php
			}
		}
	}
	//wp_die();
}

/*
 * Вывод контактных данных
 */
function tzs_print_user_contacts($row, $form_type, $show_address=0) {
    $user_id = get_current_user_id();
    $user_info = tzs_get_user_meta($row->user_id);

    $output_tbody = '<div class="tbl_products_contact" title="Контактные данные ';
    
    switch ($form_type) {
        case 'products': {
            if ($row->sale_or_purchase == 1) { $output_tbody .= 'продавца'; } 
            else { $output_tbody .= 'покупателя'; }
            break;
        }

        case 'trucks': {
            $output_tbody .= 'перевозчика';
            break;
        }

        case 'shipments': {
            $output_tbody .= 'владельца груза';
            break;
        }        
        
        default: {
        }
    }
    
    
    $output_tbody .= '">';

    if (($user_info['company'] != '') || ($user_info['last_name'] != '') || ($user_info['first_name'] != '')) {
        $output_tbody .= '<a href="/company/?id='.$row->user_id.'&type='.$form_type.'">';
    
        if ($user_info['company'] != '') { $output_tbody .= $user_info['company']; }
        else { $output_tbody .= $user_info['last_name'].' '.$user_info['first_name']; }

        $output_tbody .= '</a>';

        if ($show_address) {
            if ($show_address == 1) {
                //$meta = explode(',', $user_info['adress']); 
                //$output_tbody .= '<span>'.$meta[0].'</span>';
                $output_tbody .= '<span>'.$user_info['city'].' '.$user_info['street'].'</span>';
            } else {
                $output_tbody .= '<span>'.$user_info['city'].' '.$user_info['street'].'</span>';
            }
        } else {
            //$output_tbody .= '<span>&nbsp;</span>';
        }

        if (($user_id == 0) && ($GLOBALS['tzs_au_contact_view_all'] == false)) {
            $output_tbody .= '<div class="tzs_au_contact_view_all" phone-user-not-view="'.$row->user_id.'">Для просмотра контактов необходимо <a href="/account/login/">войти</a> или <a href="/account/registration/">зарегистрироваться</a></div>';
        }

        //if ($user_info['company'] != '') {
        //    $phone_list = explode(';', $user_info['tel_fax']);
        //} else {
            $phone_list = explode(';', $user_info['telephone']);
        //}
        
        if ($show_address) {
            $rcnt = count($phone_list);
        } else {
            $rcnt = (count($phone_list) > 3) ? 3 : count($phone_list);
        }

        for ($i=0;$i < $rcnt;$i++) {
            $output_tbody .= '<div class="tbl_products_contact_phone" phone-user="'.$row->user_id.'">
            <b>'.preg_replace("/^(.\d{2})(\d{3})(\d{3})(\d{2})(\d{1,2})/", '$1 ($2)', $phone_list[$i]).'</b>
            <span>'.preg_replace("/^(.\d{2})(\d{3})(\d{3})(\d{2})(\d{1,2})/", '$1 ($2) $3-$4-$5', $phone_list[$i]).'</span>
            <a onclick="showUserContacts(this, '.$row->user_id.', ';

            if (($user_id == 0) && ($GLOBALS['tzs_au_contact_view_all'] == false)) { $output_tbody .= 'true'; }
            else { $output_tbody .= 'false'; }

            $output_tbody .= ');">Показать</a>
            </div>';
        }

        //if ($show_address && ($user_info['user_email'] != '')) { 
        if ($user_info['user_email'] != '') { 
            $output_tbody .= '<div class="tbl_products_contact_email" phone-user="'.$row->user_id.'">
            <b>'.  substr($user_info['user_email'], 0, 3).'XX@XX</b>
            <span>'.$user_info['user_email'].'</span>
            <a onclick="showUserContacts(this, '.$row->user_id.', ';

            if (($user_id == 0) && ($GLOBALS['tzs_au_contact_view_all'] == false)) { $output_tbody .= 'true'; }
            else { $output_tbody .= 'false'; }

            $output_tbody .= ');">Показать</a>
            </div>';
        }

        if ($show_address && ($user_info['skype'] != '')) { 
            $output_tbody .= '<div class="tbl_products_contact_skype" phone-user="'.$row->user_id.'">
            <b>'.  substr($user_info['skype'], 0, 3).'XXXX</b>
            <span>'.$user_info['skype'].'</span>
            <a onclick="showUserContacts(this, '.$row->user_id.', ';

            if (($user_id == 0) && ($GLOBALS['tzs_au_contact_view_all'] == false)) { $output_tbody .= 'true'; }
            else { $output_tbody .= 'false'; }

            $output_tbody .= ');">Показать</a>
            </div>';
        }
    } else {
        $output_tbody .= 'Контактные данные не указаны';
    }
    
    
    $output_tbody .= '</div>';
    
    return $output_tbody;
}
function tzs_tr_sh_table_record_out_cont($row, $form_type) {
//    $user_info = tzs_get_user_meta($row->user_id);

    if ($form_type === 'shipments') { $prefix = 'sh';}
    else { $prefix = 'tr'; }
    
    $type = trans_types_to_str($row->trans_type, $row->tr_type);
    
    $cost = tzs_cost_to_str($row->cost, true);
    
    $output_tbody = '<tr rid="'.$row->id.'">';

    $output_tbody .= '
            <td>
                <div class="record_number">
                    <span class="middle" title="Номер заявки">
                           № '.$row->id.'
                    </span>
                </div>
                <div>
                    <span class="time_label" title="Дата и время публикации заявки">
                        '.convert_date_year2($row->time).'<br>
                        '.convert_time_only($row->time).'
                    </span>
                </div>
            </td>
            <td>
                <div>'.tzs_city_to_str($row->from_cid, $row->from_rid, $row->from_sid, (($prefix === 'tr') ? $row->tr_city_from : $row->sh_city_from),'Пункт погрузки').'<br/>'.tzs_city_to_str($row->to_cid, $row->to_rid, $row->to_sid, (($prefix === 'tr') ? $row->tr_city_to : $row->sh_city_to), 'Пункт выгрузки');
    
    if (($row->distance > 0) && ($prefix === 'tr')) {
        $output_tbody .= '<br/>'.tzs_make_distance_link($row->distance, false, array($row->tr_city_from, $row->tr_city_to));
    }
    else if (($row->distance > 0) && ($prefix === 'sh')) {
        $output_tbody .= '<br/>'.tzs_make_distance_link($row->distance, false, array($row->sh_city_from, $row->sh_city_to));
    }

    $output_tbody .= '
                </div>
            </td>
            <td>
                <div><strong>
                    <span class="expired_label" title="Дата погрузки">
                    '.convert_date_year2(($prefix === 'tr') ? $row->tr_date_from : $row->sh_date_from).'<br/>
                    </span><br>
                    <span class="expired_label" title="Дата выгрузки">
                        '.convert_date_year2(($prefix === 'tr') ? $row->tr_date_to : $row->sh_date_to).'
                    </span></strong>
                </div>
            </td>';
    
    if ($prefix === 'sh') {
        $output_tbody .= '<td>
                <div title="Тип груза">'.(isset($GLOBALS['tzs_sh_types'][$row->sh_type]) ? $GLOBALS['tzs_sh_types'][$row->sh_type] : '').'</div>
            </td>';
        
        $output_tbody .= '<td><div>';
        if (($row->tr_weight > 0) || ($row->sh_weight > 0)) {
            $output_tbody .= '<span title="Вес груза">'.remove_decimal_part(($prefix === 'tr') ? $row->tr_weight : $row->sh_weight).' т</span><br>';
        }

        if (($row->tr_volume > 0) || ($row->sh_volume > 0)) {
            $output_tbody .= '<span title="Объем груза">'.remove_decimal_part(($prefix === 'tr') ? $row->tr_volume : $row->sh_volume).' м³</span>';
        }
        $output_tbody .= '</div></td>
            <td><div title="Описание груза">'.$row->sh_descr.'</div></td>';
    } else {
        $output_tbody .= '<td>
                <div title="Тип транспортного средства">'.$type.'</div>
            </td>
            <td><div title="Описание транспортного средства">';
        
        $tr_ds1 = '';
        $tr_ds2 = '';
        if ($row->tr_length > 0) {
            $tr_ds1 .= 'Д';
            $tr_ds2 .= intval($row->tr_length);
        }
        
        if ($row->tr_width > 0) {
            if ($tr_ds1 !== '') $tr_ds1 .= 'x';
            if ($tr_ds2 !== '') $tr_ds2 .= 'x';
            $tr_ds1 .= 'Ш';
            $tr_ds2 .= intval($row->tr_width);
        }
        
        if ($row->tr_height > 0) {
            if ($tr_ds1 !== '') $tr_ds1 .= 'x';
            if ($tr_ds2 !== '') $tr_ds2 .= 'x';
            $tr_ds1 .= 'В';
            $tr_ds2 .= intval($row->tr_height);
        }
            
        if (($tr_ds1 !== '') && ($tr_ds2 !== '')) 
            $output_tbody .= $tr_ds1.': '.$tr_ds2.' м<br>';
        
        if ($row->tr_weight > 0)
            $output_tbody .= remove_decimal_part($row->tr_weight).' т<br>';

        if($row->tr_volume > 0)
            $output_tbody .= remove_decimal_part($row->tr_volume).' м³<br>';
                                    
        if ($row->tr_descr && (strlen($row->tr_descr) > 0))
            $output_tbody .= $row->tr_descr.'<br>';
            
        $output_tbody .= '</div></td>
            <td><div title="Желаемый груз">'.$row->sh_descr.'</div></td>';
    }

                
    

    $output_tbody .= '<td><div title="Стоимость перевозки груза">';
    if (($row->price > 0) && ($row->distance > 0)) {
        $output_tbody .= $row->price.' '.$GLOBALS['tzs_curr'][$row->price_val].'<br><br>'.
                round($row->price / $row->distance, 2).' '.$GLOBALS['tzs_curr'][$row->price_val].'/км'; 
    } else {
        $output_tbody .= $cost[0];
    }

    $output_tbody .= '</div>
            </td>
            <td>
                <div title="Форма оплаты услуг по перевозке груза">'.$cost[1].'</div>
            </td>';
    
    if ($prefix === 'tr') {
        //$output_tbody .= '<td><div title="Комментарии">'.$row->comment.'</div></td>';
    }
    
    $output_tbody .= '</tr>';
    
    return $output_tbody;
}

?>