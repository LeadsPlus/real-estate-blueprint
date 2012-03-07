<?php

class Placester_Contact_Widget extends WP_Widget {

  function Placester_Contact_Widget() {
    $widget_ops = array('classname' => 'Placester_Contact_Widget', 'description' => __( 'Works only on the Property Details Page.') );
    $this->WP_Widget( 'Placester_Contact_Widget', 'Placester Property Contact Form', $widget_ops );
  }

  //Front end contact form
  function form($instance){
    //Defaults
    $instance = wp_parse_args( (array) $instance, array('title'=>'', 'modern' => 0) );

    $title = htmlspecialchars($instance['title']);

    extract($instance, EXTR_SKIP);

    $checked = $instance['modern'] == 1 ? 'checked' : '';

    // Output the options
    echo '<p><label for="' . $this->get_field_name('title') . '">' . __('Title:') . '</label><input class="widefat" type="text" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" value="' . $title . '" /></p>';

    echo '<p><input class="checkbox" type="checkbox" id="' . $this->get_field_id('modern') . '" name="' . $this->get_field_name('modern') . '"' . $checked . ' style="margin-right: 5px;"/><label for="' . $this->get_field_id('modern') . '">' . __('Use placeholders instead of labels') . '</label></p>';
    
    ?>
     <p style="font-size: 0.9em;">
        Warning: This widget is designed to be used to send queries about a certain listing and therefore only works on the Property Details Page.
    </p>     
    <?php 
  }
  
  // Update settings
  function update($new_instance, $old_instance){
    $instance = $old_instance;
    $instance['title'] = strip_tags(stripslashes($new_instance['title']));
    $instance['modern'] = isset($new_instance['modern']) ? 1 : 0;
    return $instance;
  }
  
  // Admin widget
  function widget($args, $instance) {
        global $post;
    // if(isset($post->post_type) && $post->post_type == 'property') {
        // $data = placester_property_get($post->post_name);
        if (!empty($post)) {
          $listing_data = json_decode(stripslashes($post->post_content), true);
        } else {
          $listing_data = array();
        }
        extract($args);

        $title = apply_filters('widget_title', empty($instance['title']) ? '&nbsp;' : $instance['title']);
        $modern = @$instance['modern'] ? 1 : 0;
        $template_url = get_bloginfo('template_url');

    
        echo '<section class="side-ctnr placester_contact">' . "\n";
        if ( $title ) {
          echo '<h3>' . $title . '</h3>';
        } 
          ?>
              <section class="common-side-cont clearfix">
                  <div class="msg">Thank you for the email, we\'ll get back to you shortly</div>
                  <form name="widget_contact" action="" method="post">
                  <?php
                  // For HTML5 enabled themes
                  if ( $modern == 0 ) { ?>
                    <label class="required" for="email">Email Address (required)</label><input class="required" type="email" name="email"/>
                    <label class="required" for="firstName">First Name (required)</label><input class="required" type="text" name="firstName"/>
                    <label class="required" for="lastName">Last Name (required)</label><input class="required" type="text" name="lastName"/>
                    <label for="question">Any questions for us?</label><textarea rows="5" name="question"></textarea>
                    <input type="hidden" name="id" value="<?php echo @$data['id'];  ?>">
                    <input type="hidden" name="fullAddress" value="<?php echo @$data['location']['full_address'];  ?>">
                  <?php } else { ?>
                    <input class="required" placeholder="Email Address (required)" type="email" name="email"/>
                    <input class="required" placeholder="First Name (required)" type="text" name="firstName"/>
                    <input class="required" placeholder="Last Name (required)" type="text" name="lastName"/>
                    <textarea rows="5" placeholder="Any questions for us?" name="question"></textarea>
                    <input type="hidden" name="id" value="<?php echo @$data['id'];  ?>">
                    <input type="hidden" name="fullAddress" value="<?php echo @$data['location']['full_address'];  ?>">
                  <?php } ?>
                    <input type="submit" value="Send it" />
                  </form>
                <div class="placester_loading"></div>
              </section>  
              <div class="separator"></div>
            </section>
    <?php }
  // }
} // End Class

// add_action('init', 'placester_contact_widget');
// // Style
// function placester_contact_widget() {
//     $myStyleUrl = WP_PLUGIN_URL . '/placester/css/contact.widget.ajax.css';
//     wp_enqueue_style( 'contactwidgetcss', $myStyleUrl );
//     $myScriptUrl = WP_PLUGIN_URL . '/placester/js/contact.widget.ajax.js';
//     wp_enqueue_script( 'contactwidgetjs', $myScriptUrl, array('jquery') );

//     // Get current page protocol
//     $protocol = isset( $_SERVER['HTTPS'] ) ? 'https://' : 'http://';

//     $params = array(
//         'ajaxurl' => admin_url( 'admin-ajax.php', $protocol ),
//     );
//     wp_localize_script( 'contactwidgetjs', 'contactwidgetjs', $params );
// }

// Ajax function
add_action( 'wp_ajax_placester_contact', 'ajax_placester_contact' );
function ajax_placester_contact() {
    
    if( !empty($_POST) ) {
      $error = "";
      $message = "A prospective client wants to get in touch with you. \n\n";

      // Check to make sure that the first name field is not empty
      if( trim($_POST['firstName']) == '' ) {
        $error .= "Your first name is required<br/>";
      } else {
        $message .= "First Name: " . trim($_POST['firstName']) . " \n";
      }

      // Check to make sure that the last name field is not empty
      if( trim($_POST['lastName']) == '' ) {
        $error .= "Your last name is required<br/>";
      } else {
        $message .= "Last Name: " . trim($_POST['lastName']) . " \n";
      }

      // Check to make sure sure that a valid email address is submitted
      if( trim($_POST['email']) == '' )  {
        $error .= "An email address is required<br/>";
      } else if ( !eregi("^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,4}$", trim($_POST['email'])) ) {
        $error .= "A valid email address is required<br/>";
      } else {
        $message .= "Email Address: " . trim($_POST['email']) . " \n";
      }

      // Check the question field
      if( trim($_POST['question']) == '' ) {
        $question = "They had no questions at this time \n\n ";
      } else {
        $message .= "Questions: " . trim($_POST['question']) . " \n";
      }

      if( empty($_POST['id']) ) {
        $message .= "Listing ID: No specific listing \n";
      } else {
        $message .= "Listing ID: " . trim($_POST['id']) . " \n";
      }

      if( trim($_POST['fullAddress']) == '' ) {
        $message .= "Listing Address: No specific listing \n";
      } else {
        $message .= "Listing Address: " . $_POST['fullAddress'] . " \n";
      }

    if( empty($error) ) {

      $api_whoami = PLS_Plugin_API::get_user_details();
      if ($api_whoami['email']) {
        $placester_Mail = wp_mail($api_whoami['email'], 'Prospective client from ' . get_bloginfo('url'), $message);
      }
      
      $name = $_POST['firstName'] . ' ' . $_POST['lastName'];
      PLS_Membership::create_person(array('metadata' => array('name' => $name, 'email' => $_POST['email'] ) )) ;

      echo "sent";
    } else {
      echo $error;
    }
    die;
  }
}
