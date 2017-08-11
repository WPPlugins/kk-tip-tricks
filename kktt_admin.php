<?php
/*
 Plugin Name: KK Tip Tricks
 Plugin URI: http://krzysztof-furtak.pl/2010/09/wordpress-plugin-kk-tip-tricks/
 Description:  Plugin displays randome messages such as quotations, tips &tricks, newsflash in any place on a webpage.
 Version: 1.0.2
 Author: Krzysztof Furtak
 Author URI: http://krzysztof-furtak.pl/
 */

add_action('init', 'kktt_addJQueryPlugin');

	function kktt_addJQueryPlugin() {
		wp_register_script('kktt-js-plugin', WP_PLUGIN_URL . '/kk-tip-tricks/js/cookie.js', array('jquery'), '1.0');
		wp_enqueue_script('kktt-js-plugin');
	}

require_once 'kktt_user.php';

add_action('init', 'kktt_load_translation');

function kktt_load_translation() {
	$lang = get_locale();
	if (!empty($lang)) {
		$moFile = dirname(plugin_basename(__FILE__)) . "/lang";
		$moKat = dirname(plugin_basename(__FILE__));

		load_plugin_textdomain("lang-kktiptricks", false, $moFile);
	}
}

/* instalacja */

function kktt_install() {

	add_option('kktt-animated-bar', '1');
	add_option('kktt-back-color', '#333333');
	add_option('kktt-font-color', '#cccccc');
	add_option('kktt-transp', '80');
	add_option('kktt-bar-head', 'Did you know that');

	global $wpdb;
	$table_name = $wpdb->prefix . "kktiptricks";

	if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
		$sql = "CREATE TABLE ".$table_name." (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`text` TEXT NOT NULL ,
`status` INT( 1 ) NOT NULL DEFAULT  '1'
) ENGINE = INNODB CHARACTER SET utf8 COLLATE utf8_general_ci;";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);

		$text = 'The Only Thing Necessary For Triumph Of Evil Is For Good Men To Do Nothing
<br />Edmund Burke';

		$insert = "INSERT INTO  " . $table_name . " (`id` ,`text`,`status`) VALUES (NULL , '$text',  '1')";

		$results = $wpdb->query($insert);
	}

}

register_activation_hook(__FILE__, 'kktt_install');
/* koniec instalacja */


if (is_admin ()) {
	add_action('admin_menu', 'kktt_menu');
	add_action('admin_print_styles', 'kktt_admin_styles');
	add_action('init', 'kktt_addJavaScript');

	function kktt_addJavaScript() {
		wp_register_script('kktt-js', WP_PLUGIN_URL . '/kk-tip-tricks/js/functions.js', array('jquery'), '1.0');
		wp_enqueue_script('kktt-js');
	}

	function kktt_admin_styles() {
		wp_enqueue_style('kktt_style', WP_PLUGIN_URL . '/kk-tip-tricks/css/admin_css.css');
	}

	function kktt_menu() {
		add_menu_page('KKTipTricks', 'KKTipTricks', 'administrator', 'kktt-menu', 'kktt_content');
		add_submenu_page('kktt-menu', 'KKTipTricks', 'Settings', 'administrator', 'kktt-menu-settings', 'kktt_settings');
	}

	function kktt_content(){
			
		global $wpdb;
		$table_name = $wpdb->prefix . "kktiptricks";
		$rows = $wpdb->get_results("SELECT * FROM $table_name");

		echo '<div class="wrap">';
		echo '<div id="icon-edit-pages" class="icon32"></div><h2>KK Tip Tricks - ' . __("Lists", "lang-kktiptricks") . '</h2>';
			
		echo '<div id="info" style="margin-top:10px;"></div>';

		echo '<div class="kktt-error postbox" id="kktt-error-puste-pola" style="display: none;"><div class="kktt-alert-wew">'.__('All fields are required.','lang-kktiptricks').'</div></div>';

		echo '
		<div style="float: left; margin: 20px 0; width: 74%;">
		
		<div class="postbox" id="kktt-add" style="display: none;">
			<h3 class="hndle kktt-head-h3">
				<span>' . __('Add new text', 'lang-kktiptricks') . ':</span>
				<div style="float:right;"><img src="' . WP_PLUGIN_URL . '/kk-tip-tricks/images/close.png" onclick="kkttCloseDiv(\'kktt-add\');" style="vertical-align: middle; margin: 0 10px; cursor:pointer;" alt="X" /></div>
			</h3>
			<div class="inside">
				<div class="kktt-div-wew">
					<div class="kktt-small-text">' . __('Text', 'lang-kktiptricks') . ':</div>
					<div><textarea style="width: 100%; height: 50px;" id="kktt-text"></textarea></div>
					<div style="margin-top: 10px; text-align: right;">
                       <a href="#" class="btn button-primary" onclick="kkttSave(); return false;" style="padding: 5px 10px;" /><img src="' . WP_PLUGIN_URL . '/kk-tip-tricks/images/save.png" alt="+" style="display:inline-block; vertical-align:middle;" /> ' . __('Save', 'lang-kktiptricks') . '</a><span id="save-loading" style="display: none;"><img src="' . WP_PLUGIN_URL . '/kk-tip-tricks/images/loader.gif" style="vertical-align: middle; margin-left: 10px;" alt="Wait..." /></span>
                    </div>
				</div>
			</div>
		</div>
		
		<div class="postbox" id="kktt-edit" style="display: none;">
			<h3 class="hndle kktt-head-h3">
				<span>' . __('Edit text', 'lang-kktiptricks') . ':</span>
				<div style="float:right;"><img src="' . WP_PLUGIN_URL . '/kk-tip-tricks/images/close.png" onclick="kkttCloseDiv(\'kktt-edit\');" style="vertical-align: middle; margin: 0 10px; cursor:pointer;" alt="X" /></div>
			</h3>
			<div class="inside">
				<div class="kktt-div-wew">
					<div class="kktt-small-text">' . __('Text', 'lang-kktiptricks') . ':</div>
					<div><textarea style="width: 100%; height: 50px;" id="kktt-text-edit"></textarea></div>
					<div style="display: none;"><input type="text" id="kktt-edit-id" /></div>
					<div style="margin-top: 10px; text-align: right;">
                       <a href="#" class="btn button-primary" onclick="kkttSaveEdit(); return false;" style="padding: 5px 10px;" /><img src="' . WP_PLUGIN_URL . '/kk-tip-tricks/images/save.png" alt="+" style="display:inline-block; vertical-align:middle;" /> ' . __('Update', 'lang-kktiptricks') . '</a><span id="save-loading-edit" style="display: none;"><img src="' . WP_PLUGIN_URL . '/kk-tip-tricks/images/loader.gif" style="vertical-align: middle; margin-left: 10px;" alt="Wait..." /></span>
                    </div>
				</div>
			</div>
		</div>
		';

		echo '
		<div style="text-align:right; margin:10px 0px;">
			<a href="#" class="button add-new-h2" onclick="addTextWindow(); return false;" style="padding: 5px 10px;"><img src="' . WP_PLUGIN_URL . '/kk-tip-tricks/images/add.png" alt="+" style="display:inline-block; vertical-align:middle;" /> ' . __('Add new text', 'lang-kktiptricks') . '</a>
		</div>
		';

		echo '<table id="kktt-table" class="widefat fixed" cellspacing="0">';
		echo '<thead><tr class="thead">
            <th style="width: 35px;">ID</th>
            <th>' . __('Text', 'lang-kktiptricks') . '</th>
            <th style="width: 60px; text-align: center;">' . __('Status', 'lang-kktiptricks') . '</th>
            <th style="width: 150px;" colspan="2">' . __('Options', 'lang-kktiptricks') . '</th>
            </tr></thead>';

		foreach ($rows as $row) {

			if ($row->status == 1) {
				$status = '<img src="' . WP_PLUGIN_URL . '/kk-tip-tricks/images/aktywny.png" id="kktt-status-' . $row->id . '" onclick="zmienStatusKKTT(\'' . $row->id . '\'); return false;" alt="Yes" style="display:inline-block; vertical-align:middle; cursor: pointer;" />';
			} else {
				$status = '<img src="' . WP_PLUGIN_URL . '/kk-tip-tricks/images/nieaktywny.png" id="kktt-status-' . $row->id . '" onclick="zmienStatusKKTT(\'' . $row->id . '\'); return false;" alt="No" style="display:inline-block; vertical-align:middle; cursor: pointer;" />';
			}

			echo '<tr class="alternate" id="kktt-row-' . $row->id . '">
                <td style="width: 35px;">' . $row->id . '</td>
                <td>' . $row->text . ' <input type="text" id="text-' . $row->id . '" value="' . $row->text . '" style="display:none;" /></td>
                <td style="width: 60px; text-align: center;">' . $status . ' <span id="loader-status-' . $row->id . '" style="display:none;"><img src="' . WP_PLUGIN_URL . '/kk-tip-tricks/images/small-loader.gif" alt="..." style="display:inline-block; vertical-align:middle;" /><span></td>
                <td style="width: 75px;"><a href="#" onclick="editKKTT(\'' . $row->id . '\'); return false;"><img src="' . WP_PLUGIN_URL . '/kk-tip-tricks/images/edit.png" alt="+" style="display:inline-block; vertical-align:middle;" /> ' . __('Edit', 'lang-kktiptricks') . '</a></td>
                <td style="width: 75px;"><a href="#" onclick="delKKTT(\'' . $row->id . '\'); return false;"><img src="' . WP_PLUGIN_URL . '/kk-tip-tricks/images/delete.png" alt="+" style="display:inline-block; vertical-align:middle;" /> ' . __('Delete', 'lang-kktiptricks') . '</a></td>
                </tr>';
		}

		echo '</table>';

		echo '</div><div style="float: right; width: 25%; margin-top: 20px;">';

		require_once 'plugin_sidebar.php';

		echo '</div></div>';
	}

	function kktt_settings(){
			
		global $wpdb;
		$table_name = $wpdb->prefix . "kktiptricks";
		$rows = $wpdb->get_results("SELECT * FROM $table_name");

		echo '<div class="wrap">';
		echo '<div id="icon-options-general" class="icon32"></div><h2>KK Tip Tricks - ' . __("Settings", "lang-kktiptricks") . '</h2>';
			
		echo '<div id="info" style="margin-top:10px;"></div>';

		if(get_option('kktt-animated-bar') == 0){
			$bar_tak = '';
			$bar_nie = 'selected="selected"';
			$set_disp = 'display: none;';
		}else{
			$bar_nie = '';
			$bar_tak = 'selected="selected"';
			$set_disp = '';
		}

		echo '
		<div style="float: left; margin: 20px 0; width: 74%;">
		
		<div class="postbox kktt-kol">
			<h3 class="hndle kktt-head-h3">
				<span>'. __('Plugin settings','lang-kktiptricks') .':</span>
			</h3>
			<div class="inside">
				<div class="kktt-div-wew-kol">
					<div>'. __('Display animated bar on the main page','lang-kktiptricks') .': <select id="kktt-anim-belka" onchange="animBarSetKKTT(); return false;"><option value="0" '.$bar_nie.'>'. __('No','lang-kktiptricks') .'</option><option value="1" '.$bar_tak.'>'. __('Yes','lang-kktiptricks') .'</option></select></div>
					<div id="kktt-bar-settings" style="'.$set_disp.'">
						<fieldset><legend>'. __('Animated bar settings','lang-kktiptricks') .':</legend>
							<div><span class="kktt-label">'. __('Bar title','lang-kktiptricks') .':</span> <input type="text" value="'.get_option('kktt-bar-head').'" id="kktt-bar-head" /></div>
							<div><span class="kktt-label">'. __('Background color','lang-kktiptricks') .':</span> <input type="text" value="'.get_option('kktt-back-color').'" id="kktt-back-color" /></div>
							<div><span class="kktt-label">'. __('Font color','lang-kktiptricks') .':</span> <input type="text" value="'.get_option('kktt-font-color').'" id="kktt-font-color" /></div>
							<div><span class="kktt-label">'. __('Bar transparency','lang-kktiptricks') .':</span> <input type="text" value="'.get_option('kktt-transp').'" id="kktt-transp" />%</div>
						</fieldset>
					</div>
					<div style="margin-top: 10px; text-align: right;">
                       <a href="#" class="btn button-primary" onclick="kkttSaveSettings(); return false;" style="padding: 5px 10px;" /><img src="' . WP_PLUGIN_URL . '/kk-tip-tricks/images/save.png" alt="+" style="display:inline-block; vertical-align:middle;" /> ' . __('Save', 'lang-kktiptricks') . '</a><span id="save-loading-settings" style="display: none;"><img src="' . WP_PLUGIN_URL . '/kk-tip-tricks/images/loader.gif" style="vertical-align: middle; margin-left: 10px;" alt="Wait..." /></span>
                    </div>
				</div>
			</div>
		</div>
		';

		echo '</div><div style="float: right; width: 25%; margin-top: 20px;">';

		require_once 'plugin_sidebar.php';

		echo '</div></div>';

	}
}

require_once('ajax.php');

?>
