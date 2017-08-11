<?php
header("Content-Type: text/html; charset=UTF-8");
add_action('widgets_init', create_function('', 'return register_widget("kktiptricks");'));
add_shortcode('kktiptricks', 'kktt_bartag_func');
add_action('wp_head', 'animated_bar_kktt');

class kktiptricks extends WP_Widget {

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

	function kktiptricks() {
		// widget actual processes
		parent::WP_Widget(false, $name = 'KK Tip Tricks');
	}

	function form($instance) {
		// outputs the options form on admin

		$title = esc_attr($instance['title']);
		?>
<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?>
<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
	name="<?php echo $this->get_field_name('title'); ?>" type="text"
	value="<?php echo $title; ?>" /></label></p>
		<?php
	}

	function update($new_instance, $old_instance) {
		// processes widget options to be saved
		return $new_instance;
	}

	function widget($args, $instance) {
		// outputs the content of the widget

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
		$liczba = kktiptricks::unirand('1',$ilosc,'1') - 1;

		extract($args);
		$title = apply_filters('widget_title', $instance['title']);
		echo $before_widget;
		echo $before_title;
		echo $title;
		echo $after_title;

		echo $cytaty[$liczba];

		echo $after_widget;
	}

}

function animated_bar_kktt(){

	if(get_option('kktt-animated-bar') == '1'){
		
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
		$liczba = kktiptricks::unirand('1',$ilosc,'1') - 1;

		?>
<script type="text/javascript">
	function zamknij(id) {
	jQuery('#' + id).fadeOut("fast");
}

function zamknijPernamentnie(id) {
	jQuery('#' + id).fadeOut("fast", function() {
		jQuery.cookie(id + '_ban', '1', {
			expires : 1
		});
	});
}

function pokazCiekawostki() {
	jQuery('#ciekawostki').fadeIn("normal");
}
	
	jQuery(document).ready(function() {

		var tresc = "<div id=\"ciekawostki\" style=\"display: none; position: fixed; width: 100%;";
		tresc += " background: <?php echo get_option('kktt-back-color'); ?>; opacity:";
		tresc += " <?php echo get_option('kktt-transp')/100; ?>; filter: alpha(opacity =";
		tresc += " <?php echo get_option('kktt-transp'); ?>); bottom: 0 !important; margin-bottom: 0px; z-index: 99999;";
		tresc += " border-top: 2px #000 solid;\"><div id=\"ciekawostki-content\" style=\"width: 1000px; margin: auto;";
		tresc += " color: <?php echo get_option('kktt-font-color'); ?>\"><div id=\"ciekawostki-top\"";
		tresc += " style=\"margin-top: 10px; font-size: 12px; font-style: italic; font-weight: bold;\">";
		tresc += "<?php echo get_option('kktt-bar-head'); ?>:<span id=\"ciekawostki-zamknij\" style=\"float: right;\">";
		tresc += "<a href=\"javascript:zamknij('ciekawostki');\" style=\"color: <?php echo get_option('kktt-font-color'); ?>;";
		tresc += " font-size: 10px; text-decoration: none;\">[X]</a> <a href=\"javascript:zamknijPernamentnie('ciekawostki');\"";
		tresc += " style=\"color: <?php echo get_option('kktt-font-color'); ?>; font-size: 10px; text-decoration: none;\">[X - Day]</a>";
		tresc += "</span></div><div id=\"ciekawostki-tresc\" style=\"margin: 10px 0px 20px 0px; text-align: justify;\">";
		tresc += "<?php echo trim($cytaty[$liczba],"\n"); ?></div></div></div>";
		
		jQuery('body').append('<div id="kktt-box-anim"></div>');

		
		jQuery('#kktt-box-anim').html(tresc);

		if (jQuery.cookie('ciekawostki_ban') != '1') {
			pokazCiekawostki();
		}

	});
	</script>
		<?php

	}
}
//function kktt_bartag_func($atts) {
//	extract(shortcode_atts(array(
//                'idkktt' => 'noid',
//	), $atts));
//
//	if ($idkktt != '' && $idkktt != 'noid') {
//		global $wpdb;
//		$table_name = $wpdb->prefix . "kktiptricks";
//
//		$sql = "SELECT * FROM $table_name WHERE id = '$idkktt'";
//		$wynik = $wpdb->get_row($sql, ARRAY_A);
//
//		$i = 0;
//		foreach ($wyniki as $wynik) {
//			$cytaty[$i] = $wynik['text'];
//			$i++;
//		}
//
//		$ilosc = count($cytaty);
//		$liczba = kktiptricks::unirand('1',$ilosc,'1') - 1;
//
//		return $cytaty[$liczba];
//
//	}else{
//		/* @ToDo */
//	}
//
//}
?>
