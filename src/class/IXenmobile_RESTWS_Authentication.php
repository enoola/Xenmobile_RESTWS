<?php
namespace enoola_Citrix_Client;

require_once('./class/Xenmobile_RESTWS_Exception.php');

/**
 * The interface representing Authentication
 *
 */
interface IXenmobile_RESTWS_Authentication
{
  public function login($szUsername, $szPassword);
  public function cwclogin($szContext, $szCustomerId, $szServiceKey);
  public function logout();

  public function getAuthToken();
}

?>
