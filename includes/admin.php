<?php

add_action( 'admin_menu', 'my_plugin_menu' );

function my_plugin_menu() {
	add_menu_page( 'My Plugin Options', 'Games Rating Options', 'manage_options', 'my-unique-identifier', 'mt_settings_page' );
}





function mt_settings_page() {

    if (!current_user_can('manage_options'))
    {
      wp_die( __('You do not have sufficient permissions to access this page.') );
    }

    $opt_color_rate = 'mt_button_color';
    $opt_color_unrate = 'mt_button_unrate_color';

    
    $data_field_name_symbol = 'mt_symbol';
    $hidden_field_name = 'mt_submit_hidden';
    $button_rate = 'mt_button_color'; 
    $button_unrate = 'mt_button_unrate_color';

    $opt_val_rate = get_option( $opt_color_rate);
    $opt_val_unrate = get_option( $opt_color_unrate );

    if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {

        $opt_val_rate = $_POST[ $button_rate ];
        $opt_val_unrate = $_POST[ $button_unrate ];
        
        if($_POST['symbol'] == 1) {
          update_option( 'symbol', '&#11088' );
        }elseif($_POST['symbol'] == 2){
          update_option( 'symbol', '&#129505' );
        }elseif($_POST['symbol'] == 3){
          update_option( 'symbol', '&#128156' );          
        }elseif($_POST['symbol'] == 4){
          update_option( 'symbol', '&#128155' );  
        }elseif($_POST['symbol'] == 5){
          update_option( 'symbol', '&#128154' );
        }else{
          update_option( 'symbol', '&#128153' );
        }
        update_option( $opt_color_rate, $opt_val_rate );
        update_option( $opt_color_unrate, $opt_val_unrate );
        


?>
    <div class="updated"><p><strong><?php _e('Settings saved.', 'menu-test' ); ?></strong></p></div>
    <?php

    }

    echo '<div class="wrap">';

    echo "<h1>" . __( 'Plugin Settings', 'menu-test' ) . "</h1>";

    echo '<div class="wrap">';

    echo "<h3>" . __( 'Plugin colors and symbols', 'menu-test' ) . "</h3>";
    
    ?>

    <form name="plugin_colors" method="post" action="">
      <input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">

      <p><?php _e("Change color of Rate button:", 'menu-test' ); ?> 
      <input type="text" name="<?php echo $button_rate; ?>" value="<?php echo $opt_val_rate; ?>" size="20">
      </p><hr />
      
      <p><?php _e("Change color of Unrate button:", 'menu-test' ); ?> 
      <input type="text" name="<?php echo $button_unrate; ?>" value="<?php echo $opt_val_unrate; ?>" size="20">
      </p><hr />

      <label for="symbol">Choose rating symbol:</label>
      <select id="symbol" name="symbol">
        <option name="<?php echo $data_field_name_symbol; ?>" value="1">&#11088</option>
        <option name="<?php echo $data_field_name_symbol; ?>" value="2">&#129505</option>
        <option name="<?php echo $data_field_name_symbol; ?>" value="3">&#128156</option>
        <option name="<?php echo $data_field_name_symbol; ?>" value="4">&#128155</option>
        <option name="<?php echo $data_field_name_symbol; ?>" value="5">&#128154</option>
        <option name="<?php echo $data_field_name_symbol; ?>" value="6">&#128153</option>
      </select>

      <p class="submit">
      <input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
      </p>

    </form>
    <?php
    }


?>