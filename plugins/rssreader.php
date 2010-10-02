<?php
/*
 * HTMLe plugin
 * -------------------- 
 * File:     rssreader.php
 * Type:     function
 * Name:     rssreader
 * Purpose:  read external rss feeds
 * Author:   Michael Stowe
 * Example: {rssreader url='http://rss.cnn.com/rss/cnn_topstories.rss' posts='5' words='25' style="<strong><a href((%link%)) target='_blank'>%title%</a></strong><br />%description%<br /><br />"}
 * Required: url
 * Special: For style attribute:<ul><li>%link% = url</li><li>%title% = title</li><li>%description% = description</li></ul>
 * Advanced: {rssreader url='http://rss.cnn.com/rss/cnn_topstories.rss' posts='5' words='25' style="<strong><a href((%link%)) target='_blank'>%title%</a></strong><br />%description%<br /><br />"}
 * -------------------- 
 */
 
function HTMLe_rssreader($params, $return='') {
    $feed_url = (!empty($params['url'])?$params['url']:'http://rss.cnn.com/rss/cnn_topstories.rss');
    $feed_number = (!empty($params['posts'])?$params['posts']:'5');
    $wordsize = (!empty($params['words'])?$params['words']:'25');
    $skip = (!empty($params['skipfirst'])?$params['skipfirst']:'0');
    $endwith = (!empty($params['endwith'])?$params['endwith']:'');
    
    $library = simplexml_load_file($feed_url);
    $i = 1;
    
    foreach($library->channel->item as $item) {
    	if($i <= $feed_number) {
    	
    		$find = array("\n \n","\r \r","\n\n","\r\r","\n","\r","  ");
    		$replace = array("\n","\r","\n","\r",' ',' ',' ');
    		
    		$words = explode(" ",str_replace($find,$replace,$item->description));
    		$words = array_slice($words,$skip);
    		array_splice($words,$wordsize);
    	
    		if(!isset($params['style'])) {
	    		$return .= '<a href="'.$item->link.'" target="_blank" style="font-weight: strong;">'.$item->title.'</a><br />';
	    		$return .= implode(" ",$words).$endwith.'<br /><br />';
    		} else {
    			$key = array('%link%','%title','%description%');
    			$value = array($item->link, $item->title, implode(" ",$words).$endwith);
    			$return .= str_replace($key,$value,$params['style']);
    		}
    	
    		$i++;
    	} else {
    		break;
    	}
    }
    return $return;
}

?>