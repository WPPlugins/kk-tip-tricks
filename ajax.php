<?php

add_action('wp_ajax_save_kktt', 'saveAjaxKKTT');

function saveAjaxKKTT() {

	global $wpdb;

	$update = $_POST['update'];

	$text = $_POST['text'];
	$id = $_POST['id'];

	$table_name = $wpdb->prefix . "kktiptricks";

	if ($update == 0) {

		$sql = "INSERT INTO  $table_name ( `id` , `text`, `status` ) VALUES (NULL ,  '$text', '1')";

	} else if ($update == 1) {

		$sql = "UPDATE  $table_name SET `text` =  '$text'  WHERE `id` = '$id' LIMIT 1";

	}

	$wynik = $wpdb->query($sql);
	$id_last = mysql_insert_id();

	if($id_last == 0){
		$id_last = $id;
	}

	if ($wynik) {
		echo '1|||<div class="kktt-ok postbox"><div class="kktt-alert-wew">'.__('Changes saved successfully.','lang-kktiptricks').'</div></div>|||'.$id_last.'|||
         <tr class="alternate" id="kktt-row-' . $id_last . '">
                <td style="width: 35px;">' . $id_last . '</td>
                <td>' . $text . ' <input type="text" id="text-' . $id . '" value="' . $text . '" style="display:none;" /></td>
                <td style="width: 60px; text-align: center;"><img src="' . WP_PLUGIN_URL . '/kk-tip-tricks/images/aktywny.png" id="kktt-status-' . $id_last . '" onclick="zmienStatusKKTT(\'' . $id_last . '\'); return false;" alt="Yes" style="display:inline-block; vertical-align:middle; cursor: pointer;" /> <span id="loader-status-' . $id_last . '" style="display:none;"><img src="' . WP_PLUGIN_URL . '/kk-tip-tricks/images/small-loader.gif" alt="..." style="display:inline-block; vertical-align:middle;" /><span></td>
                <td style="width: 75px;"><a href="#" onclick="editKKTT(\'' . $id_last . '\'); return false;"><img src="' . WP_PLUGIN_URL . '/kk-tip-tricks/images/edit.png" alt="+" style="display:inline-block; vertical-align:middle;" /> ' . __('Edit', 'lang-kktiptricks') . '</a></td>
                <td style="width: 75px;"><a href="#" onclick="delKKTT(\'' . $id_last . '\'); return false;"><img src="' . WP_PLUGIN_URL . '/kk-tip-tricks/images/delete.png" alt="+" style="display:inline-block; vertical-align:middle;" /> ' . __('Delete', 'lang-kktiptricks') . '</a></td>
                </tr>|||
        ';
	} else {
		echo '0|||<div class="kktt-error postbox"><div class="kktt-alert-wew">'.__('Changes have not been saved. Please try again.','lang-kktiptricks').'</div></div>|||'.$id_last."|||";
	}
}

add_action('wp_ajax_del_kktt', 'delAjaxKKTT');

function delAjaxKKTT() {

	global $wpdb;

	$id = $_POST['id'];

	$table_name = $wpdb->prefix . "kktiptricks";

	$sql = "DELETE FROM ".$table_name." WHERE `id` = '".$id."' LIMIT 1";

	$wynik = $wpdb->query($sql);

	if ($wynik) {
		echo '1|||<div class="kktt-ok postbox"><div class="kktt-alert-wew">'.__('Text deleted successfully.','lang-kktiptricks').'</div></div>|||';
	} else {
		echo '0|||<div class="kktt-error postbox"><div class="kktt-alert-wew">'.__('Text could not be deleted. Please try again.','lang-kktiptricks').'</div></div>|||';
	}
}

add_action('wp_ajax_zmiana_statusu_kktt', 'zmienStatusAjaxKKTT');

function zmienStatusAjaxKKTT() {

	global $wpdb;
	$id = $_POST['id'];

	$table_name = $wpdb->prefix . "kktiptricks";

	$sql = "SELECT status FROM $table_name WHERE id = '$id' LIMIT 1";
	$dane = $wpdb->get_row($sql, ARRAY_A);

	switch ($dane['status']) {
		case 1:
			$sqla = "UPDATE " . $table_name . " SET status = '0' WHERE id = '$id' LIMIT 1";
			$wynika = $wpdb->query($sqla);
			echo 0;
			break;
		case 0:
			$sqla = "UPDATE " . $table_name . " SET status = '1' WHERE id = '$id' LIMIT 1";
			$wynika = $wpdb->query($sqla);
			echo 1;
			break;
	}
}

add_action('wp_ajax_save_settings_kktt', 'zmienSettingsAjaxKKTT');

function zmienSettingsAjaxKKTT() {

	update_option('kktt-animated-bar', $_POST['anim_bar']);
	update_option('kktt-bar-head', $_POST['head_bar']);
	update_option('kktt-back-color', $_POST['back_color']);
	update_option('kktt-font-color', $_POST['font_color']);
	update_option('kktt-transp', $_POST['transp']);

	echo '1|||<div class="kktt-ok postbox"><div class="kktt-alert-wew">'.__('Settings were saved.','lang-kktiptricks').'</div></div>|||';

}

function unirand($min, $max, $ile = 1)
{
	$liczba = range($min, $max);

	for($i = 0; $i < $ile; $i++)
	{
		$los = array_rand($liczba);
		return $liczba[$los].' ';
		//unset($liczba[$los]);
	}
}

add_action('wp_ajax_losowy_cytat_kktt', 'losowyCytatAjaxKKTT');

function losowyCytatAjaxKKTT() {

	$cytaty = array();

	global $wpdb;
	$table_name = $wpdb->prefix . "kktiptricks";

	$sql = "SELECT * FROM $table_name";
	$wyniki = $wpdb->get_results($sql, ARRAY_A);

	$i = 0;
	foreach ($wyniki as $wynik) {
		$cytaty[$i] = $wynik['text'];
		$i++;
	}

	$ilosc = count($cytaty);
	$liczba = unirand('1',$ilosc,'1') - 1;

	echo $cytaty[$liczba];

}

?>