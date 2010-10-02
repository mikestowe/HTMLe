<?php
/*
 * HTMLe plugin
 * ————————————————————-
 * File:     hello.php
 * Type:     function
 * Name:     hello
 * Purpose:  example hello world plugin
 * Author:   Michael Stowe
 * Example: {hello} {hello:subfunction} {hello:inner}this is your text{/hello:inner}
 * Required:
 * Special:
 * ————————————————————-
 * Note: the above information should be included in all plugins and is used to teach 
 * the user about your plugin.  Use the required field for any required attributes, and
 * the special field for any special tags your plugin accepts.
 */

function HTMLe_hello($params) {

	/*
	 * To access this HTMLe plugin, all you need to do is call the HTMLe tag identical
	 * to the page name minus the .php extension.  So to access the hello.php plugin,
	 * just use the {hello} tag in your post.
	 *
	 * Also, any attributes listed would be supplied as $params['attribute_name']
	 * For example, if the tag where {hello color="white"} we would be able to access
	 * the Color attribute value by calling upon $params['color'] in our function.
	 * 
	 * Each HTMLe tag is given the potential for unlimited attributes, so the sky's
	 * the limit... just use this power wisely.
	 *
	 * Oh yeah, try to avoid using 'echo' and 'print' in your function as they will output
	 * the data before it is prepared, making your site look really goofy.  You should also
	 * send it back using a 'return' instead.
	 */
	 
	return 'hello world';
}

function HTMLe_hello_subfunction($params,$return='') {
	/*
	 * And yes, you can have multiple functions, even classes (see sampleclass plugin) in your 
	 plugin code... just make sure that the intial function to be called is named 
	 * HTMLe_($filename excluding the .php%) otherwise you may just end up causing the end of 
	 * the world... which would be very, very bad...
	 *
	 * Oh, and if you would like to be able to call this function by itself, you can now use the
	 * child method, or {hello:subfunction}, and yes even {hello:subfunction color="blue"}.  Just
	 * make sure you use the proper naming convention for your plugin's child function, that being
	 * HTMLe_%plugin-name%_%subfunction-name% or in the case of plugin "hello" it would be (as
	 * shown above, HTML_hello_subfunction.
	*/
	
	$return .= 'I\'m not used... I feel sad...';
	$return .= '<br />';
	$return .= 'But wait, I can be called independently of my parent function!!!  ';
	$return .= 'Yay for me!  I\'m an independent child, whoo hoo!';
	
	return $return;
}

function HTMLe_hello_inner($params,$return='') {
	/*
	 * This function is designed to take the input IN BETWEEN two tags and modify it.  To do this
	 * we will use $params['_inner'].
	 *
	 * Note: at this time you can only use attributes OR select inner text, not both.  If the HTMLe
	 * tag contains an ending tag, the script will automatically grab the inner text, even if there 
	 * isn't any and the tag contains attributes.  This will hopefully be resolved in a future
	 * release.
	 */

	return 'The text being sent to hello:inner is: '.$params['_inner'];
}

?>