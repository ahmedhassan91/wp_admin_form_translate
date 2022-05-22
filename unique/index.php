
<?php


/**
 * Plugin Name:       Unique plugins
 * Plugin URI:        http://developershub.uk//
 * Description:       this plugin is just for training
 * Version:           1.10.3
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Ahmed Hassan
 * Author URI:        https://author.example.com/
 * License:           MIT
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       test_domain
 * Domain Path:       /languages
 */


class unique_plugin{

   function __construct(){
      add_action('admin_menu' , array( $this , 'custom_admin_menu') );
      add_action('admin_init' , array( $this , 'custom_settings'));
      add_filter('the_content' , array($this , 'content_check') );
      add_action('init' , array($this, 'languages'));
   }

   function languages(){
    //

    print(dirname(plugin_basename(__FILE__)).'/languages');
      load_plugin_textdomain('test_domain', false, dirname(plugin_basename(__FILE__)).'/languages'  );
   }

   function content_check($content){
     if((is_main_query()) AND (is_singular()) AND (
       get_option('third_field') OR get_option('fourth_field') OR get_option('fifth_field')
     )){
       return $this->content_modified($content);
     }else{
       return $content;
     }
   }

   function content_modified($content){
     return $content.' data';
     // return get_option('third_field').''.get_option('fourth_field').''.get_option('fifth_field');
   }

   function print_between_fields(){
     ?>
     <h2>Please Select Fields</h2>
     <?php
   }

   function custom_settings(){
     add_settings_section( 'first_section' , 'Title of the Section' , array( $this , 'print_between_fields') , 'custom_slugs' );


     add_settings_field( 'first_field' , 'Display Location' , array( $this, 'outputsection') , 'custom_slugs', 'first_section' );
     register_setting( 'first_setting_group' , 'first_field'  ,array( 'sanitize_callback' => array($this , 'custom_sanitizer') , 'default' => '0' ));



     add_settings_field('second_field', 'HeadLine Text option' , array( $this , 'second_field_output' ),'custom_slugs', 'first_section');
     register_setting('first_setting_group' , 'second_field' ,array( 'sanitize_callback' => 'sanitize_text_field' , 'default' => 'Add Heading' ) );


     add_settings_field('third_field', 'Word Count' , array( $this , 'third_field_output') ,'custom_slugs' , 'first_section' );
     register_setting('first_setting_group' , 'third_field',array( 'sanitize_callback' => 'sanitize_text_field' , 'default' => '' ));

     //
     add_settings_field('fourth_field', 'Character Count' , array( $this , 'fourth_field_output') ,'custom_slugs' , 'first_section' );
     register_setting('first_setting_group' , 'fourth_field',array( 'sanitize_callback' => 'sanitize_text_field' , 'default' => '' ));
     //
     add_settings_field('fifth_field', 'Word Count' , array( $this , 'fifth_field_output') ,'custom_slugs' , 'first_section' );
     register_setting('first_setting_group' , 'fifth_field',array( 'sanitize_callback' => 'sanitize_text_field' , 'default' => '' ));
   }



   function custom_sanitizer($input){
     if($input=='0' || $input=='1'){
       return $input;
     }else{
       add_settings_error('first_field','first_field_Error','Wrong input added','error');
       return get_option('first_field');
     }
   }

   function outputsection(){?>

     <select  name="first_field">
       <option value="0" <?php echo get_option('first_field')=='0'?  'selected':'' ?>>First</option>
       <option value="1" <?php echo get_option('first_field')=='1'?  'selected':'' ?>>Last</option>
     </select>
     <?php
   }

   function second_field_output(){?>
      <input type="text" name="second_field" value="<?php echo ( get_option('second_field') ); ?>">
      <?php
    }

   function third_field_output(){?>
     <input type="checkbox" name="third_field" <?php echo get_option('third_field')=='on'?'checked':'' ?> >
     <?php
   }

   function fourth_field_output(){?>
     <input type="checkbox" name="fourth_field" <?php echo get_option('fourth_field')=='on'?'checked':'' ?> >
     <?php
   }

   function fifth_field_output(){?>
     <input type="checkbox" name="fifth_field" <?php echo get_option('fifth_field')=='on'?'checked':'' ?>>
  <?php  }




   function custom_admin_menu(){
     add_options_page( 'Page Title', __('Menu Title' , 'test_domain') , 'manage_options','custom_slugs', array($this, 'menu_output') );
   }

   function menu_output(){  ?>
     <div class="wrap">
       <h1>Custom Menu heading</h1>

       <form  action="options.php" method="POST">
         <?php
          settings_fields('first_setting_group');

          do_settings_sections('custom_slugs');
          submit_button();
         ?>
       </form>
     </div>
     <?php
       }


}

  $unique_ad = new unique_plugin();









?>
