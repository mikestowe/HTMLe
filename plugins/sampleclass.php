<?php
/*
 * HTMLe plugin
 * ————————————————————-
 * File:     sampleclass.php
 * Type:     class
 * Name:     sample class
 * Purpose:  example plugin using only a class
 * Author:   Michael Stowe
 * Example: {sampleclass text="hello world..."} {sampleclass:dosomething}
 * Required: text
 * Special: {sampleclass} is required to initiate the class prior to {sampleclass:dosomething} being called.  This is the case with all classes.
 * ————————————————————-
 * Note: the above information should be included in all plugins and is used to teach 
 * the user about your plugin.  Use the required field for any required attributes, and
 * the special field for any special tags your plugin accepts.
 */

class HTMLe_sampleclass {
	function __construct($params) {
		$this->text = $params['text'];
	}
	
	# Class functions should be either named completely independently, such as the example below:
	function dosomething($params) {
		return $this->text;
	}
	
	# Or using logic similar to namespaces...
	function HTMLe_sampleclass_secondfunction($params) {
		return $this->text;
	}
	
	/* Note, if two functions are named identically, using different naming conventions, the namespace model
	 * will be used instead of the independently named function.
	 */
}
?>