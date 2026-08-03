<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('csrf_field')) {
	/**
	 * Renders a hidden CSRF token input for use inside POST forms.
	 */
	function csrf_field()
	{
		$CI =& get_instance();
		$name = $CI->security->get_csrf_token_name();
		$hash = $CI->security->get_csrf_hash();
		return '<input type="hidden" name="' . html_escape($name) . '" value="' . html_escape($hash) . '" />' . "\n";
	}
}
