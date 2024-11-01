<?php
/*
Plugin Name: Simple Contact Form (Basic)
Plugin URI: https://wordpress.org/plugins/simple-contact-form-basic/
Description: A simple, yet powerful and effective contact form, that integrates easily into any WordPress site just by using a simple shortcode. Visitors can email you directly through your website, providing their name, email, phone, and message, so that you can respond quickly and easily.
Version: 1.4.1
Author: nCroud
Author URI: http://www.ncroud.com/
License: GNU v3 or later
*/

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

// Call the required functions.
require 'simplecf-functions.php';

// Add the default style sheet to the WordPress page.
add_action('wp_enqueue_scripts','scfb_register_styles');

// Create the shortcode for WordPress.
add_shortcode( 'simplecf', 'scfb_shortcode' );

?>