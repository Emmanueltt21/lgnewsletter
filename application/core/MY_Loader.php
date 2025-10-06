<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * custom loader file extends CI_Loader
 */

class MY_Loader extends CI_Loader {
    
    public function template($template_name, $vars = array(), $return = FALSE)
    {
      if ($return)
      {
        $S3Header = $this->view('templates/header', $vars, TRUE); // header
        $S3Content = $this->view($template_name, $vars, TRUE); // view
        $S3Footer = $this->view('templates/footer', $vars, TRUE); // footer
        
        return $S3Header . $S3Content . $S3Footer;
      }
      else
      {
        $this->view('templates/header', $vars, FALSE); // header
        $this->view($template_name, $vars, FALSE); // view
        $this->view('templates/footer', $vars, FALSE); // footer
      }
    }

    public function views($template_name, $vars = array(), $return = FALSE)
    {
      //$S3Header = $this->view('templates/header', $vars, $return); // header
      $S3Content = $this->view($template_name, $vars, $return); // view
      //$S3Footer = $this->view('templates/footer', $vars, $return); // footer

      if ($return)
      {
      /// return $content;

      //return $S3Header; // default header
      return $S3Content; // view as controller
      //return $S3Footer; // default footer

      }
    }
}
