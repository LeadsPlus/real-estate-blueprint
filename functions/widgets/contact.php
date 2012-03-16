<?php

class Placester_Contact_Widget extends WP_Widget {

  function Placester_Contact_Widget() {
    $widget_ops = array('classname' => 'Placester_Contact_Widget', 'description' => __( 'Works only on the Property Details Page.') );
    $this->WP_Widget( 'Placester_Contact_Widget', 'Placester Contact Form', $widget_ops );
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
        if (!empty($post) && isset($post->post_type) && $post->post_type == 'property') {
          $listing_data = unserialize($post->post_content);
        } else {
          $listing_data = array();
        }
        extract($args);

				$title = apply_filters('widget_title', empty($instance['title']) ? '&nbsp;' : $instance['title']);
				$submit_value = apply_filters('button', empty($instance['button']) ? 'Send' : $instance['button']);
				$email_label = apply_filters('email_label', empty($instance['email_label']) ? 'Email Address (required)' : $instance['email_label']);
				$fname_label = apply_filters('fname_label', empty($instance['fname_label']) ? 'First Name (required)' : $instance['fname_label']);
				$lname_label = apply_filters('lname_label', empty($instance['lname_label']) ? 'Last Name (required)' : $instance['lname_label']);
				$question_label = apply_filters('question_label', empty($instance['question_label']) ? 'Any questions for us?' : $instance['question_label']);
				$container_class = apply_filters('container_class', empty($instance['container_class']) ? '' : $instance['container_class']);
				$inner_class = apply_filters('inner_class', empty($instance['inner_class']) ? '' : $instance['inner_class']);

        $modern = @$instance['modern'] ? 1 : 0;
        $template_url = get_bloginfo('template_url');

    
        echo '<section class="side-ctnr placester_contact ' . $container_class . '">' . "\n";
        if ( $title ) {
          echo '<h3>' . $title . '</h3>';
        } 
          ?>
              <section class="<?php echo $inner_class; ?> common-side-cont clearfix">
                  <div class="msg">Thank you for the email, we\'ll get back to you shortly</div>
                  <form name="widget_contact" action="" method="post">
                  <?php
                  // For HTML5 enabled themes
                  if ( $modern == 0 ) { ?>
                    <label class="required" for="firstName"><?php echo $fname_label; ?></label><input class="required" type="text" name="firstName"/>
                    <label class="required" for="lastName"><?php echo $lname_label; ?></label><input class="required" type="text" name="lastName"/>
                    <label class="required" for="email"><?php echo $email_label; ?></label><input class="required" type="email" name="email"/>
                    <label for="question"><?php echo $question_label; ?></label><textarea rows="5" name="question"></textarea>
                    <input type="hidden" name="id" value="<?php echo @$data['id'];  ?>">
                    <input type="hidden" name="fullAddress" value="<?php echo @$data['location']['full_address'];  ?>">
                  <?php } else { ?>
                    <input class="required" placeholder="<?php echo $email_label; ?>" type="email" name="email"/>
                    <input class="required" placeholder="<?php echo $fname_label; ?>" type="text" name="firstName"/>
                    <input class="required" placeholder="<?php echo $lname_label; ?>" type="text" name="lastName"/>
                    <textarea rows="5" placeholder="<?php echo $question_label; ?>" name="question"></textarea>
                    <input type="hidden" name="id" value="<?php echo @$data['id'];  ?>">
                    <input type="hidden" name="fullAddress" value="<?php echo @$data['location']['full_address'];  ?>">
                  <?php } ?>
                    <input type="submit" value="<?php echo $submit_value; ?>" />
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
add_action( 'wp_ajax_nopriv_placester_contact', 'ajax_placester_contact' );
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
