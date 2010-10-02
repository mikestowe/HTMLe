<?php
/*
Plugin Name: HTMLe
Plugin URI: http://www.mikestowe.com/HTMLe
Description: HTMLe allows you to build and call Smarty style plugins through the use of HTMLe tags (identical to Smarty tags).  By accepting unlimited parameters, you can provide your client with a rich PHP plugin that can be customized quickly and easily, without knowledge of HTML or PHP.  <br /><br />Default plugins include {iframe} which accepts any attribute the HTML tag accepts, {php} which allows you to call misc. PHP functions, get PHP variables, or highlight PHP code, {rssreader} which allows you to display RSS feeds, and then a sample hello world and sample class plugin.  <br /><br />HTMLe also contains a built in editor to allow you to quickly and easily create, edit, and delete plugins from the WP-Admin, or simply change the permissions to disable this on a plugin.  HTMLe was designed for developers to provide their users with a simple way to include advanced code again and again, or just as a way to simply their everyday blogging activities (view http://www.mikestowe.com to see examples of the HTMLe plugins at work).  HTMLe plugins are stored in wp-content/plugins/HTMLe/plugins/.    <br /><br /><strong style="color: #990000;"><em>Before Upgrading Backup Any Plugins.  WordPress will DELETE ALL Custom Plugins when Upgrading HTMLe.</em></strong>
Version: 2.2.1 - October 1, 2010
Author: Michael Stowe
Author URI: http://www.mikestowe.com
License: GPL-2
*/


############################################ WORDPRESS SUPPORT (CAN BE REMOVED IF NOT USING HTMLe FOR WORDPRESS)


if(defined('WP_PLUGIN_DIR')) {
	add_filter('the_content', 'HTMLe_process');
	add_action('admin_menu', 'HTMLe_wp_menus');

	function HTMLe_wp_menus() {
		if(function_exists('add_meta_box')) {
			add_meta_box('HTMLe','HTMLe: Installed Plugins','HTMLe_wp_installedtags','post', 'normal','low');
			add_meta_box('HTMLe','HTMLe: Installed Plugins','HTMLe_wp_installedtags','page', 'normal','low');
		}

		if(function_exists('add_submenu_page')) {
			add_submenu_page('plugins.php','HTMLe Installed Plugins', 'HTMLe Plugins', 1, 'HTMLe_menu', 'HTMLe_wp_admin');
		}
	}

	function HTMLe_wp_admin() {
		$_POST['code'] = stripslashes($_POST['code']);
	
		echo '<h1>HTMLe Plugins</h1>';
		if ($_REQUEST['do'] == 'new') {
			echo '<form action="'.$_SERVER['PHP_SELF'].'?page=HTMLe_menu" method="post">';
			echo '<input type="hidden" name="do" value="create">';
			echo '<h3>Create a Plugin</h3>';
			echo 'Enter Plugin Name: <input type="text" name="plugin_name"> <input type="submit" value="Next">';
			echo '</form>';
		} elseif ($_REQUEST['do'] == 'create') {
			$bad = array('!','@','#','$','%','^','&','*','(',')','-','=','+','.',';',':',',','<','>',' ','"','\'','{','}','[',']','\\','|');
			$plugin_name = str_replace($bad,'',$_REQUEST['plugin_name']);
			if(file_exists(dirname(__FILE__).'/plugins/'.$plugin_name.'.php')) { echo 'Whoops!  This plugin already exists.  Please hit "back" and try again.'; break; }
			echo '<form action="'.$_SERVER['PHP_SELF'].'?page=HTMLe_menu" method="post">';
			echo '<input type="hidden" name="plugin_name" value="'.$plugin_name.'">';
			echo '<input type="hidden" name="do" value="save">';
			echo '<h3>Edit '.strtoupper($plugin_name).'</h3>';
			echo '<textarea name="code" style="width: 100%; height: 600px;">';
			echo <<<HTMLETEMPLATE
<?php
/*
 * HTMLe plugin
 * ---------------------
 * File:     $plugin_name.php
 * Type:     function
 * Name:     $plugin_name
 * Purpose:  enter plugin purpose here
 * Author:   your name goes here
 * Example:  {{$plugin_name}} (example of how to use the plugin)
 * Required: required attributes
 * Special:  any special attributes or tags
 * ---------------------
 * Note: the above information should be included in all plugins and is used to teach 
 * the user about your plugin.  Use the required field for any required attributes, and
 * the special field for any special tags your plugin accepts.
 */

function HTMLe_$plugin_name(\$params,\$return='') {

	/*
	 * Your content goes here
	 */
	 
	return \$return;
}

?>
HTMLETEMPLATE;
			echo '</textarea><br /><input type="button" value="Cancel" onclick="location.href=\''.$_SERVER['PHP_SELF'].'?page=HTMLe_menu\'"> <input type="submit" value="Save Changes"></form>';
		} elseif (isset($_REQUEST['plugin'])) {
			if(is_writeable(dirname(__FILE__).'/plugins/'.$_REQUEST['plugin'].'.php')) {
				echo '<form action="'.$_SERVER['PHP_SELF'].'?page=HTMLe_menu" method="post">';
				echo '<input type="hidden" name="plugin_name" value="'.$_REQUEST['plugin'].'">';
				echo '<input type="hidden" name="do" value="save">';
				echo '<h3>Edit '.strtoupper($_REQUEST['plugin']).'</h3>';
				echo '<textarea name="code" style="width: 100%; height: 600px;">';
				echo file_get_contents(dirname(__FILE__).'/plugins/'.$_REQUEST['plugin'].'.php');
				echo '</textarea><br /><input type="button" value="Cancel" onclick="location.href=\''.$_SERVER['PHP_SELF'].'?page=HTMLe_menu\'"> <input type="submit" value="Save Changes"></form>';
			} else {
				echo '<h3>Edit '.strtoupper($_REQUEST['plugin']).'</h3>';
				echo 'Error!  This file is not writeable.  Please set permissions to 777';
			}
		} elseif (isset($_REQUEST['delete'])) {
			echo '<form action="'.$_SERVER['PHP_SELF'].'?page=HTMLe_menu" method="post">';
			echo '<input type="hidden" name="do" value="delete">';
			echo '<h3>Delete '.strtoupper($_REQUEST['delete']).'</h3>';
			echo 'Are you sure you want to PERMANENTLY delete this plugin.  This cannot be undone. <br /><br /><input type="hidden" name="plugin_name" value="'.$_REQUEST['delete'].'"> <input type="button" value="Cancel" onclick="location.href=\''.$_SERVER['PHP_SELF'].'?page=HTMLe_menu\'"> <input type="submit" value="DELETE" style="color: #990000;">';
			echo '</form>';
		} else {
			if($_POST['do'] == 'save') {
				if($_SERVER['PHP_SELF'] == '/wp-admin/plugins.php') {	
					$fp = fopen(dirname(__FILE__).'/plugins/'.$_POST['plugin_name'].'.php', 'w');
					fwrite($fp, $_POST['code']);
					fclose($fp);
					echo '<div style="width: 99%; padding: 5px; margin-bottom: 10px; background: #F3F3F3; border: 1px solid #333; color: #333; font-weight: bold;">'.strtoupper($_POST['plugin_name']).' Saved</div>';
				}
			} elseif ($_POST['do'] == 'delete') {
				if($_SERVER['PHP_SELF'] == '/wp-admin/plugins.php') {
					unlink(dirname(__FILE__).'/plugins/'.$_POST['plugin_name'].'.php');
					echo '<div style="width: 99%; padding: 5px; margin-bottom: 10px; background: #F3F3F3; border: 1px solid #333; color: #333; font-weight: bold;">'.strtoupper($_POST['plugin_name']).' Deleted</div>';
				}
			} elseif(!is_writable(dirname(__FILE__).'/plugins/')) {
				echo '<div style="width: 99%; padding: 5px; margin-bottom: 10px; background: #F3F3F3; border: 1px solid #333; color: #333; font-weight: bold;">To create and edit HTMLe Plugins, make sure the HTMLe/plugins/ folder has its permissions set to 777 (chmod).</div>';
			}
			echo '<h3>Installed Plugins:</h3>';
			echo '<table width="100%">';
			echo '<tr style="background: #333; color: #fff; font-weight: bold;"><td style="width: 150px; height: 35px; padding: 5px;">PLUGIN</td><td style="height: 35px; padding: 5px;">USAGE EXAMPLE <span style="color: #999;">(SHOWN WITH ALL OPTIONAL ATTRIBUTES)</span></td><td style="width: 150px; height: 35px; padding: 5px;">REQUIRED ATTRIBUTES</td><td style="width: 150px; height: 35px; padding: 5px;">SPECIAL TAGS</td><td>&nbsp;</td></tr>';
			HTMLE_wp_installedtags('','',true);
			echo '</table>';
			if(is_writable(dirname(__FILE__).'/plugins/')) { echo '<input type="button" value="Create New Plugin" onclick="location.href=\''.$_SERVER['PHP_SELF'].'?page=HTMLe_menu&do=new\'">'; }
			echo '<br /><br /><br />';
			echo '<h3>Find New Plugins:</h3>';
			echo '<form action="'.$_SERVER['PHP_SELF'].'?page=HTMLe_menu" method="post">Look for: <input type="text" name="keyword" value="'.$_POST['keyword'].'" style="color: #333;"><input type="submit" value="Search"></form>';
			if(isset($_POST['keyword']) && !empty($_POST['keyword'])) {
				echo '<iframe src="http://www.mikestowe.com/htmle_plugin_search.php?q='.$_POST['keyword'].'" width="100%" height="400" frameborder="0" style="overflow-x: hidden; overflow-y: auto; margin-bottom: 60px;"></iframe>';
			}
		}
	}

	function HTMLe_wp_installedtags($one,$two,$admin=false) {
		if(!$admin) {
			echo '<table cellpadding="5" cellspacing="0">';
		}
		
		foreach ((scandir(dirname(__FILE__).'/plugins/')) as $file) {
			if(substr($file,0,1) != '.') { 
				$title = '<strong>'.strtoupper(substr($file,0,-4)).'</strong>:';
				$example = file_get_contents(dirname(__FILE__).'/plugins/'.$file);
				$parts = explode("\n",$example);
				$i = 1;
				$examplecode = $purpose = $required = $special = '';
				foreach($parts as $part) {
					if(preg_match('/example:/i',$part)) {
						$line = explode(':',$part);
						array_shift($line);
						$examplecode =  htmlentities(trim(implode(':',$line)));
					} elseif(preg_match('/purpose:/i',$part)) {
						$line = explode(':',$part);
						array_shift($line);
						$purpose =  htmlentities(trim(implode(':',$line)));
					} elseif(preg_match('/required:/i',$part)) {
						$line = explode(':',$part);
						array_shift($line);
						$required =  trim(implode(':',$line));
					} elseif(preg_match('/special:/i',$part)) {
						$line = explode(':',$part);
						array_shift($line);
						$special =  trim(implode(':',$line));
					} elseif($i == 20) {
						break;
					}
					$i++;
				}
				
				if($admin) {
					echo '<tr style="background: #fff;"><td style="height: 35px; padding: 5px;">'.$title.'<br /><span style="font-size: 10px;">'.$purpose.'</span></td><td style="height: 35px; padding: 5px; color: #666;">'.$examplecode.'</td><td style="height: 35px; padding: 5px; color: #990000; font-weight: bold; text-align: center;">'.$required.'</td><td style="height: 35px; padding: 5px; color: #666;">'.$special.'</td><td>'.(is_writable(dirname(__FILE__).'/plugins/'.$file)?'<nobr><input type="button" value="edit" onclick="location.href=\''.$_SERVER['PHP_SELF'].'?page=HTMLe_menu&plugin='.substr($file,0,-4).'\'"><input type="button" style="color: #990000;" value="x" title="delete" onclick="location.href=\''.$_SERVER['PHP_SELF'].'?page=HTMLe_menu&delete='.substr($file,0,-4).'\'"></nobr>':'&nbsp;').'</tr>';
					echo '<tr><td colspan="5" style="height: 1px; border-bottom: 1px solid #333;"></td></tr>';
				} else {
					echo '<tr><td valign="top" style="border-bottom: 1px solid #333; padding: 15px 0px 15px 5px;">'.$title.'</td><td style="border-bottom: 1px solid #333; color: #666; padding: 15px 0px 15px 10px;" valign="top">'.$examplecode.'</td><td style="border-bottom: 1px solid #333; padding: 15px 15px 15px 10px; color: #990000; font-weight: bold; text-align: left;">'.$required.'</td></tr>';
				}
			}
		}
		
		if(!$admin) {
			echo '</table>';
		}
	}
}


############################################ PROCEDURAL STARTUP


// DO NOT REMOVE IF USING A CMS/ BLOG
function HTMLe_process($content) {
	$HTMLe = new HTMLe;
	return $HTMLe->parse($content);
}


############################################ START CLASS


class HTMLe {


############################################ FIND PLUGINS


function __construct() {
	foreach ((scandir(dirname(__FILE__).'/plugins/')) as $file) {
          if(substr($file,-4) == '.php') { $this->tags[] = substr($file,0,-4); }
	}

	$this->find = array('"','}','((','))');
	$this->replace = array('','','="','"');
}


############################################ FIX QUOTES (WordPress and CMS)


	function cleanquote($input) {
		return str_ireplace(array('{'.$tag,'&#8221;','&#8243;','&quot;','&#8216;','&#8217;'),array('','"','"','"','\'','\''),$input);
	}

	
############################################ FIND PLUGIN CALLS


	function parse($HTMLIN) {
		foreach($this->tags as $tag) {

			if(preg_match_all('/{'.$tag.':[^}]+}[^{]+{\/'.$tag.':[^}]+/i',$HTMLIN,$innersubmatches)) {
				for($i=0; $i < count($innersubmatches[0]); $i++) {
					unset($params);
					$inm_tmp = explode('}',$innersubmatches[0][$i]);
					$tag_parts = explode(':',$inm_tmp[0]);
					$tag = substr($tag_parts[0],1);
					$call_function = $tag_parts[1];
					$inm_tmp = explode('{',$inm_tmp[1]);
					$params['_inner'] = $this->cleanquote($inm_tmp[0]);
					$HTMLIN = str_ireplace($innersubmatches[0][$i].'}',$this->build($tag,$call_function,$params),$HTMLIN);
				}
			}
			
			
			if(preg_match_all('/{'.$tag.'}[^{]+{\/'.$tag.'}[^.]*/i',$HTMLIN,$innermatches)) {
				for($i=0; $i < count($innermatches[0]); $i++) {
					unset($params);
					$inm_tmp = explode('}',$innermatches[0][$i]);
					$tag = $call_function = substr($inm_tmp[0],1);
					$inm_tmp = explode('{',$inm_tmp[1]);
					$params['_inner'] = $this->cleanquote($inm_tmp[0]);
					$HTMLIN = str_ireplace($innermatches[0][$i],$this->build($tag,$call_function,$params),$HTMLIN);
				}
			}
			
			if(preg_match_all('/{'.$tag.'[^}]*}/i',$HTMLIN,$matches)) {
				for($i=0; $i < count($matches[0]); $i++) {
					unset($params);
					// modified for WordPress
					$tmp = $this->cleanquote($matches[0][$i]);
					// -- end modification
					preg_match_all('/\s[a-z]*="[^"]+/i',$tmp,$tmp);
					for($t=0; $t < count($tmp[0]); $t++) {
						$parts = explode('="',$tmp[0][$t]);
						$params[trim($parts[0])] = trim(str_replace($this->find,$this->replace,$parts[1]));
					}
					
					if(preg_match('/{[^\s]+:[^\s]+/',$matches[0][$i])) { 
						$tmp_tmp = explode(' ',$matches[0][$i]);
						$tmp_tmp = explode(':',$tmp_tmp[0]);
						$call_function = str_replace(array(':','}'),'',$tmp_tmp[1]);
					} else { 
						$call_function = $tag;
					}
					
					$HTMLIN = str_ireplace($matches[0][$i],$this->build($tag,$call_function,$params),$HTMLIN);
				}
			}
		}
		return $HTMLIN;
	}


############################################ ACTIVATE PLUGINS


	function build($tag,$call_function,$params,$return='') {
		if(function_exists('runkit_lint_file')) {
			if(!runkit_lint_file(dirname(__FILE__).'/plugins/'.$tag.'.php')) {
				return '<!-- PLUGIN CONTAINS ERRORS -->';
			}
		}

		include_once(dirname(__FILE__).'/plugins/'.$tag.'.php');
		$tagf = 'HTMLe_'.$tag;
		$tagfs = $tagf.'_'.$call_function;

		if($tag != $call_function) {
			if(function_exists($tagfs)) {
				return $tagfs($params);
			} elseif(is_a($this->current_class, $tagf)) {
				if(method_exists($this->current_class,$tagfs)) {
					return $this->current_class->$tagfs($params);
				} elseif(method_exists($this->current_class,$call_function)) {
					return $this->current_class->$call_function($params);
				} else {
					return '<!-- SUB PLUGIN OBJECT FAILED TO LOAD -->';
				}
			} else {
				return '<!-- SUB PLUGIN FAILED TO LOAD -->';
			}
		} else {
			if(function_exists($tagf)) {
				return $tagf($params);
			} elseif(class_exists($tagf)) {
				$this->current_class = new $tagf($params); 
			} else {
				return '<!-- PLUGIN FAILED TO LOAD -->';
			}
		}
	}


############################################ END CLASS


}
?>