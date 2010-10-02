<?php
/*
 * HTMLe plugin
 * -------------------- 
 * File:     iframe.php
 * Type:     function
 * Name:     iframe generator
 * Purpose:  creates an iframe with all available attributes
 * Author:   Michael Stowe
 * Example: {iframe src="http://www.mikestowe.com" width="900" height="900" frameborder="0"}
 * Required: src
 * Special:
 * -------------------- 
 * Note: the above information should be included in all plugins and is used to teach 
 * the user about your plugin.  Use the required field for any required attributes, and
 * the special field for any special tags your plugin accepts.
 */

function HTMLe_iframe($params,$return='<iframe') {

	foreach($params as $key=>$value) {
		$return .= ' '.$key.'="'.$value.'"';
	}
	
	return $return.'></iframe>';
}


?>