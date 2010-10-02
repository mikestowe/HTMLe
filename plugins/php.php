<?php
/*
 * HTMLe plugin
 * ————————————————————-
 * File:     php.php
 * Type:     function/ child functions
 * Name:     php
 * Purpose:  execute php code/ files or run php functions/ grab variables
 * Author:   Michael Stowe
 * Example: Execute Code: {php code="echo 'hello world';"} -OR- {php}echo 'hellow world';{/php} Execute File: {php file="myfile.inc"} $_GET: {php:get key="page"} $_SESSION: {php:session key="page"} Variable: {php:variable key="page"} Constant: {php:constant key="page"} Highlight PHP Code: {php:highlight text="<?php"} OR {php:highlight file="myfile.php"}
 * Required:
 * Special: Child functions: highlight, variable, get, post, request, cookie, session
 * ————————————————————-
 * Note: the above information should be included in all plugins and is used to teach 
 * the user about your plugin.  Use the required field for any required attributes, and
 * the special field for any special tags your plugin accepts.
 */

function HTMLe_php_phpcleanfunction($input) {
	$find = array('php<br />',';<br />');
	$replace = array('php',';');
	return str_replace($find,$replace,$input);
}


# This function's contents have been commented out to prevent contributing users from adding malicious code.
# If you are the only one with the ability to add content to your site through the CMS/ Database, then you
# may remove the comment tags below to enable the ability to run PHP commands through this plugin.

function HTMLe_php($params) {
	/* Be very, very careful with this one
	if(isset($params['file'])) {
		return eval(file_get_contents(getcwd().'/'.$params['file']));
	} elseif (isset($params['code'])) {
		return eval($params['code']);
	} elseif (isset($params['_inner'])) {
		return eval(HTMLe_php_phpcleanfunction($params['_inner']));
	}
	*/
}

function HTMLe_php_highlight($params) {
	if(isset($params['text'])) {
		return highlight_string(htmlspecialchars_decode($params['text']),true);
	} elseif(isset($params['_inner'])) {
		return highlight_string(htmlspecialchars_decode(HTMLe_php_phpcleanfunction($params['_inner'])),true);
	} elseif(isset($params['file'])) {
		return '<div style="width: 100%; overflow-x: auto;"><nowrap>'.str_replace('{','{<img width="1" height="1" style="display: none;">',highlight_file(getcwd().'/'.$params['file'],true)).'</nowrap></div>';
	}
}

function HTMLe_php_variable($params) {
	global $$params['key'];
	return $$params['key'];
}

function HTMLe_php_get($params) {
	return $_GET[$params['key']];
}

function HTMLe_php_post($params) {
	return $_POST[$params['key']];
}

function HTMLe_php_request($params) {
	return $_REQUEST[$params['key']];
}

function HTMLe_php_cookie($params) {
	return $_COOKIE[$params['key']];
}

function HTMLe_php_session($params) {
	return $_SESSION[$params['key']];
}


?>