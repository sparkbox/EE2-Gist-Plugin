<?php
/**
* Plugin file for gist.
* 
* This file must be placed in the
* /expressionengine/third_party/gist folder in your ExpressionEngine installation.
*
* @package Gist
* @version 1.0
* @author Rob Harr <rob@heysparkbox.com>
* @link http://olivierbon.com/projects/dreebbble
* @copyright Copyright (c) 2011, Sparkbox
* @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0 Unported
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gist {
  
  var $return_data;

  /**
  * fetch the gist
  **/
  public function fetch() {
    $this->EE =& get_instance();  
    $gistId = ( $this->EE->TMPL->fetch_param('id'));
    //the file is a optional param
    $gistFile = ( $this->EE->TMPL->fetch_param('file'));
    
    //if there is no gist ID then we need to return nothing.
    if(!isset($gistId) || empty($gistId)) { 
      return $this->return_data;
    }

    $url = 'https://gist.github.com/'.$gistId.'.js';
    
    //if a file is specified then lets append it to the end of the URL
    if(isset($gistFile) && !empty($gistFile)){
      
       $url =  $url.'?file='.$gistFile;
      
    }
    
    // go and get the contents of the URL that we just built. 
    $result = file_get_contents($url);
    
    //if the result comes back with some content then we need to strip out the pesky javascript and linebreaks
    if ( isset($result) && !empty($result) ) {     
      $result = $this->format_return($result);
    }
    
    $this->return_data = $result;
    return $this->return_data;
  }
  
  //Format the return to get ride of the line breaks and the javascript
  function format_return($return) {
    $return = preg_replace( '/document.write\(\'/', '', $return );
    $return = preg_replace('/\'\)/', '', $return );
    $return = preg_replace('%(?<!/)\\\\n%', '', $return);
    $return = stripslashes( $return );
    return $return;
  }

}

/* End of file pi.gist.php */
/* Location: ./system/expressionengine/third_party/gist/pi.gist.php */