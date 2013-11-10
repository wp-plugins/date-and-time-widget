<?php
/**
 * Plugin Name: Date and Time Widget
 * Description: Widget that displays the local date and/or time.
 * Version: 1.0.0
 * Author: Donna Peplinskie
 * Author URI: http://bookwookie.ca
 * License: GPL v3
 * 
 * Date and Time Widget
 * Copyright (C) 2013, Donna Peplinskie - donnapep@gmail.com
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

function register() {
    register_widget( 'DateTimeWidget' );
}
add_action( 'widgets_init', 'register' );

function load_color_picker_style() {
    wp_enqueue_style( 'wp-color-picker' );
}
function load_color_picker_script() {
    wp_enqueue_script( 'wp-color-picker' );
}
add_action( 'admin_print_scripts-widgets.php', 'load_color_picker_script' );
add_action( 'admin_print_styles-widgets.php', 'load_color_picker_style' );

class DateTimeWidget extends WP_Widget {
    function __construct() {
	parent::__construct(
	    'date_time', // Base ID
	    __( 'Date and Time', 'date-time' ), // Name
	    array( 'description' => __( 'Show the local date and time.', 'date-time' ), )
	); 
	
	wp_register_style( 'date-time-style', plugins_url( 'css/style.css', __FILE__ ) );
	wp_enqueue_style( 'date-time-style' );
	wp_enqueue_script( 'date-time', plugins_url( '/js/date-time.js', __FILE__ ) );
    } 
    
    /* Show the widget. */
    function widget( $args, $instance ) {
	extract( $args );
	
	$widget_id = $args['widget_id'];

	//Widget settings
	$time_format = $instance[ 'time_format' ];
	$date_format = $instance[ 'date_format' ];
	$font_family = $instance[ 'font_family' ];
	$font_size = $instance[ 'font_size' ];
	$text_color = $instance [ 'text_color' ];
	$background_color = $instance [ 'background_color' ];

	echo $before_widget;
	?>
	
	<div class="date-time" style="color: <?php echo $text_color ?>; background-color: <?php echo $background_color ?>; font-family: <?php echo $font_family ?>; font-size: <?php echo $font_size ?>;">
	    <div class="date"></div>
	    <div class="time"></div>
	</div>
    	<script type="text/javascript">
	    update('<?php echo $widget_id; ?>', '<?php echo $time_format; ?>', '<?php echo $date_format; ?>');
    	</script>

	<?php
	echo $after_widget;
    }
    
    /* Show the widget's settings. */
    function form( $instance ) { ?>
	<?php //Set up some default widget settings.
	$defaults = array(
	'time_format' => '12-hour-seconds',
	'date_format' => 'long',
	'font_family' => 'Arial, Arial, Helvetica, sans-serif',
	'font_size' => '20px',
	'text_color' => '#000',
	'background_color' => 'transparent'
	);
	$time_formats = array(
	"none" => "None",
	"12-hour" => date("g:i A", current_time( 'timestamp', 0 ) ),
	"12-hour-seconds" => date("g:i:s A", current_time( 'timestamp', 0 ) ),
	"24-hour" => date("G:i", current_time( 'timestamp', 0 ) ),
	"24-hour-seconds" => date("G:i:s", current_time( 'timestamp', 0 ) ),
	);
	$date_formats = array(
	"none" => "None",
	"short" => date( "n/j/Y", current_time( 'timestamp', 0 ) ),
	"european" => date( "j/n/Y", current_time( 'timestamp', 0 ) ),
	"medium" => date( "M j Y", current_time( 'timestamp', 0 ) ),
	"long" => date( "F j, Y", current_time( 'timestamp', 0 ) ),
	);
	$font_families = array(
	"Arial, Arial, Helvetica, sans-serif" => "Arial",
	"Comic Sans MS, Comic Sans MS, cursive" => "Comic Sans MS",
	"Courier New, Courier New, Courier, monospace" => "Courier New",
	"Georgia, Georgia, serif" => "Georgia",
	"Lucida Sans Unicode, Lucida Grande, sans-serif" => "Lucida Sans Unicode",
	"Tahoma, Geneva, sans-serif" => "Tahoma",
	"Times New Roman, Times, serif" => "Times New Roman",
	"Trebuchet MS, Helvetica, sans-serif" => "Trebuchet MS",
	"Verdana, Verdana, Geneva, sans-serif" => "Verdana",
	);
	$font_sizes = array(
	"8px" => "8",
	"9px" => "9",
	"10px" => "10",
	"11px" => "11",
	"12px" => "12",
	"14px" => "14",
	"16px" => "16",
	"18px" => "18",
	"20px" => "20",
	"22px" => "22",
	"24px" => "24",
	"26px" => "26",
	"28px" => "28",
	"36px" => "36",
	"48px" => "48",
	"72px" => "72",
	);
	$instance = wp_parse_args( (array) $instance, $defaults ); ?>
	
	<script>
	    jQuery(document).ready(function($) {
		/* Duplicate color pickers are shown when widget first added to sidebar. */
		$(document).ajaxComplete(function() {
		    $('.color-picker').wpColorPicker();
		});
	    });
        </script>
	
	<p>
	    <label for="<?php echo $this->get_field_id( 'time_format' ); ?>"><?php _e( 'Time Format:', 'date-time' ); ?></label>
	    <select id="<?php echo $this->get_field_id( 'time_format' ); ?>" name="<?php echo $this->get_field_name( 'time_format' ); ?>" class="widefat">
	    
	    <?php foreach( $time_formats as $key => $value ) {
		$selected = ( $instance[ 'time_format' ] == $key ) ? 'selected="selected"' : '';
		echo '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>';
	    } ?>
	    </select>
	</p>
	<p>
	    <label for="<?php echo $this->get_field_id( 'date_format' ); ?>"><?php _e( 'Date Format:', 'date-time' ); ?></label>
	    <select id="<?php echo $this->get_field_id( 'date_format' ); ?>" name="<?php echo $this->get_field_name( 'date_format' ); ?>" class="widefat">
	    
	    <?php foreach( $date_formats as $key => $value ) {
		$selected = ( $instance[ 'date_format' ] == $key ) ? 'selected="selected"' : '';
		echo '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>';
	    } ?>
	    </select>
	</p>
	<p>
	    <label for="<?php echo $this->get_field_id( 'font_family' ); ?>"><?php _e( 'Font Family:', 'date-time' ); ?></label>
	    <select id="<?php echo $this->get_field_id( 'font_family' ); ?>" name="<?php echo $this->get_field_name( 'font_family' ); ?>" class="widefat">
	    
	    <?php foreach( $font_families as $key => $value ) {
		$selected = ( $instance[ 'font_family' ] == $key ) ? 'selected="selected"' : '';
		echo '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>';
	    } ?>
	    </select>
	</p>
	<p>
	    <label for="<?php echo $this->get_field_id( 'font_size' ); ?>"><?php _e( 'Font Size:', 'date-time' ); ?></label>
	    <select id="<?php echo $this->get_field_id( 'font_size' ); ?>" name="<?php echo $this->get_field_name( 'font_size' ); ?>">
	    
	    <?php foreach( $font_sizes as $key => $value ) {
		$selected = ( $instance[ 'font_size' ] == $key ) ? 'selected="selected"' : '';
		echo '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>';
	    } ?>
	    </select>
	</p>
	<p>
	    <label for="<?php echo $this->get_field_id( 'text_color' ); ?>"><?php _e( 'Text Color', 'date-time' ) ?>:</label>
	    <input id="<?php echo $this->get_field_id( 'text_color' ); ?>"  name="<?php echo $this->get_field_name( 'text_color' ); ?>" type="text" value="<?php echo esc_attr( $instance[ 'text_color' ] ); ?>" class="color-picker" />
	</p>
	<p>
	    <label for="<?php echo $this->get_field_id( 'background_color' ); ?>"><?php _e( 'Background Color', 'date-time' ) ?>:</label>
	    <input id="<?php echo $this->get_field_id( 'background_color' ); ?>"  name="<?php echo $this->get_field_name( 'background_color' ); ?>" type="text" value="<?php echo esc_attr( $instance[ 'background_color' ] ); ?>" class="color-picker" />
	</p>
    <?php
    }
    
    //Save the widget's settings.
    function update( $new_instance, $old_instance ) {
	$instance = $old_instance;

	$instance[ 'time_format' ] = $new_instance[ 'time_format' ];
	$instance[ 'date_format' ] = $new_instance[ 'date_format' ];
	$instance[ 'font_family' ] = $new_instance[ 'font_family' ];
	$instance[ 'font_size' ] = $new_instance[ 'font_size' ];
	$instance[ 'text_color' ] = $new_instance[ 'text_color' ];
	$instance[ 'background_color' ] = $new_instance[ 'background_color' ];

	return $instance;
    }
}
?>