<?php
namespace enoola_Citrix_Client;
/*
 * From what I saw so far :
 *  500 -> Internal error (return json with the error)
      json status : 1001 -> invalid device id
 *
 *
 */


class Xenmobile_RESTWS_Exception extends \Exception
{

  public function __construct($szMessage = null, $nCode = null)
  {
    parent::__construct($szMessage, $nCode);
  }
}


?>
