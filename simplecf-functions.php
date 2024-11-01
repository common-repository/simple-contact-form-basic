<?php
/*
Simple Contact Form (Basic)

Copyright (C) 2015 nCroud Company, LLC

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/


// Display the HTML form.
function scfb_display_form($lan) {
	// define the form labels
	if($lan == 'es') {
	  define('SCFB_NAME','Su Nombre');
	  define('SCFB_MAIL','Su Correo Electr&oacute;nico');
	  define('SCFB_PHON','Su Tel&eacute;fono');
	  define('SCFB_NOTE','Su Mensaje');
	  define('SCFB_SEND','Env&iacute;a Mensaje');
	  define('SCFB_MUST','necesario');
	}
	else {
	  define('SCFB_NAME','Your Name');
	  define('SCFB_MAIL','Your Email');
	  define('SCFB_PHON','Your Phone');
	  define('SCFB_NOTE','Your Message');
	  define('SCFB_SEND','Send Message');
	  define('SCFB_MUST','required');
	}

    echo '<form action="' . esc_url($_SERVER['REQUEST_URI']) . '" method="post">';
    echo '<p>';
    echo SCFB_NAME . ' (' . SCFB_MUST . ') <br />';
    echo '<input type="text" name="simplecf-name" pattern="[a-zA-Z0-9 ]+" value="' . (isset($_POST["simplecf-name"]) ? stripslashes($_POST["simplecf-name"]) : '') . '" size="40" />';
    echo '</p>';
    echo '<p>';
    echo SCFB_MAIL . ' (' . SCFB_MUST . ') <br />';
    echo '<input type="email" name="simplecf-email" value="' . (isset($_POST["simplecf-email"]) ? stripslashes($_POST["simplecf-email"]) : '') . '" size="40" />';
    echo '</p>';
    echo '<p>';
    echo SCFB_PHON . '<br />';
    echo '<input type="phone" name="simplecf-phone" pattern="[0-9\-]+" value="' . (isset($_POST["simplecf-phone"]) ? stripslashes($_POST["simplecf-phone"]) : '') . '" size="40" />';
    echo '</p>';
    echo '<p>';
    echo SCFB_NOTE . ' (' . SCFB_MUST . ') <br />';
    echo '<textarea rows="10" cols="35" name="simplecf-message">' . (isset($_POST["simplecf-message"]) ? stripslashes($_POST["simplecf-message"]) : '') . '</textarea>';
    echo '</p>';
    echo '<p><input id="simplecf-submit" type="submit" name="simplecf-submit" value="' . SCFB_SEND . '" /></p>';
    echo '</form>';
}


// Check the submission and deliver the message.
function scfb_deliver_mail($to,$lan) {
  // define the delivery email address
  $scfb_to = $to;

  // define the user messages
  if($lan == 'es') {
	  define('SCFB_MISSING','Faltan datos requeridos. Int&eacute;ntelo de nuevo.');
	  define('SCFB_INVALID','El correo electr&oacute;nico no es v&aacute;lido.');
	  define('SCFB_SUCCESS','Gracias para su mensaje. Responderemos pronto.');
	  define('SCFB_UNKNOWN','Error inesperado.');
  }
  else {
	  define('SCFB_MISSING','One or more required fields are missing. Please try again.');
	  define('SCFB_INVALID','The email address is not valid.');
	  define('SCFB_SUCCESS','Thank you for taking the time to send a message. Expect a response soon.');
	  define('SCFB_UNKNOWN','An unexpected error occurred.');
  }

  // if the submit button is clicked, send the email
  if(isset($_POST['simplecf-submit'])) {
	// check required fields are entered
	if(empty($_POST['simplecf-name']) || empty($_POST['simplecf-email']) || empty($_POST['simplecf-message'])) {
	  echo '<p class="failure">' . SCFB_MISSING . '</p>';
	}
	else {
	  // sanitize form values
	  $scfb_name    = $_POST["simplecf-name"];
	  $scfb_email   = $_POST["simplecf-email"];
	  $scfb_phone   = $_POST["simplecf-phone"];
	  $scfb_subject = '[SimpleCF]: ' . get_bloginfo() . ' Contact Form';
	  $scfb_message = $_POST["simplecf-message"];

	  // verify the email is valid and, if so, send the message
	  if(is_email($scfb_email)) {
		$scfb_valid_email = $scfb_email;

		// if the email is a valid format, verify the domain itself is valid
		$scfb_domain = substr(strrchr($scfb_valid_email,'@'),1);

		if(checkdnsrr($scfb_domain,'MX')) {
		  $scfb_headers = "From: $scfb_name <$scfb_valid_email>" . "\r\n";

		  // If email has been processed for sending, display a success message
		  if(wp_mail($scfb_to,$scfb_subject,stripslashes($scfb_message),$scfb_headers)) {
			echo '<p class="success">' . SCFB_SUCCESS . '</p>';

			// unset the form field variables
			unset($_POST['simplecf-name']);
			unset($_POST['simplecf-email']);
			unset($_POST['simplecf-phone']);
			unset($_POST['simplecf-message']);
		  } 
		  else {
			echo '<p class="failure">' . SCFB_UNKNOWN . '</p>';
		  }
		}
		else {
		  if(checkdnsrr($scfb_domain,'A')) {
			$scfb_headers = "From: $scfb_name <$scfb_valid_email>" . "\r\n";
			
			// If email has been processed for sending, display a success message
			if(wp_mail($scfb_to,$scfb_subject,stripslashes($scfb_message),$scfb_headers)) {
			  echo '<p class="success">' . SCFB_SUCCESS . '</p>';

			  // unset the form field variables
			  unset($_POST['simplecf-name']);
			  unset($_POST['simplecf-email']);
			  unset($_POST['simplecf-phone']);
			  unset($_POST['simplecf-message']);
			} 
			else {
			  echo '<p class="failure">' . SCFB_UNKNOWN . '</p>';
			}
		  }
		  else {
			echo '<p class="failure">' . SCFB_INVALID . '</p>';
		  }
		}
	  }
	  else {
		echo '<p class="failure">' . SCFB_INVALID . '</p>';
	  }
	}
  }
}


// The function that powers the WordPress shortcode.
function scfb_shortcode($atts) {
    ob_start();

	$scfb_vars = shortcode_atts(array(

	  // if the email is not provided, use the administrator email
	  'scfb_to' => get_option('admin_email'),
	  // if the language is not define, use english
	  'scfb_lan' => 'en'

    ), $atts );

    scfb_deliver_mail($scfb_vars['scfb_to'],$scfb_vars['scfb_lan']);
    scfb_display_form($scfb_vars['scfb_lan']);

    return ob_get_clean();
}


// Register the default style sheet.
function scfb_register_styles() {
  wp_register_style('simplecf-default',plugins_url('simple-contact-form-basic/css/simplecf-default.css'),false,'1.2','all');
  wp_enqueue_style('simplecf-default');
}

?>