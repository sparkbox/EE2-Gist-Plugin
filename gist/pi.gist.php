<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* gist Class
*
* @package     ExpressionEngine
* @version     2.0.0
* @category    Plugin
* @author      Rob Harr <rob@heysparkbox.com>, Patrick Simpson <patrick@heysparkbox.com>
* @link        http://seesparkbox.com
* @copyright   Copyright (c) 2014, Sparkbox
* @license     http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0 Unported
*/

// Required for EE2 plugins
$plugin_info = array(
    'pi_name'         => 'gist',
    'pi_version'      => '2.0.0',
    'pi_author'       => 'Rob Harr',
    'pi_author_url'   => 'http://seesparkbox.com/',
    'pi_description'  => 'Gets Gists',
    'pi_usage'        => Gist::usage()
);


class Gist {

  public $return_data = "";

  /**
   * fetch
   *
   * This function returns a gist code
   *
   * @access  public
   * @return  string
   */
  public function fetch() {
    $gistId = ( ee()->TMPL->fetch_param('id'));
    //the file is a optional param
    $gistFile = ( ee()->TMPL->fetch_param('file'));
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
    $result = @file_get_contents($url);

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

    /**
     * Usage
     *
     * This function describes how the plugin is used.
     *
     * @access  public
     * @return  string
     */
    public static function usage()
    {
        ob_start();  ?>

    To Get Gists
    {exp:gist:fetch id='your_gist_id' file='js'}
    <?php
        $buffer = ob_get_contents();
        ob_end_clean();

        return $buffer;
    }
    // END
}

/* End of file pi.gist.php */
/* Location: ./system/expressionengine/third_party/gist/pi.gist.php */
