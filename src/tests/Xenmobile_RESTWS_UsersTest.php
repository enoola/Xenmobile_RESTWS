<?php
/*
 *
 * We will test out Users class
 *
 */
// use \enoola_Citrix_Client;
require_once('./extlibs/simpletest/autorun.php');
require_once('./class/Xenmobile_RESTWS_Users.php');

class Xenmobile_RESTWS_UsersTest extends UnitTestCase
{
  protected $_oCliXM_WS = null;

  function setUp()
  {
    $this->_logIn('config_file.ini');
  }

  function tearDown()
  {
    $this->_oCliXM_WS->__destruct();
    $this->_oCliXM_WS = null;
  }


  function testAddOrUpdateUserProperty()//($szUsername, $szPropertieName, $szPropertieValue )
  {
    $this->_oCliXM_WS->AddOrUpdateUserProperty('user@test.com', 'my_prop', 'a_value' );

    $this->assertTrue(property_exists($this->_oCliXM_WS->getLastRequestResult(), 'status'));
    $this->assertIdentical($this->_oCliXM_WS->getLastRequestResult()->status, 0);
  }

  function AssignUserToLocalGroups()//($szUsername, $szPropertieName, $szPropertieValue )
  {
    $this->_oCliXM_WS->AddOrUpdateUserProperty('user@test.com', 'my_prop', 'a_value' );

    $this->assertTrue(property_exists($this->_oCliXM_WS->getLastRequestResult(), 'status'));
    $this->assertIdentical($this->_oCliXM_WS->getLastRequestResult()->status, 0);

  }
  
/*
 * we assume the config file is well formed !
 * fqdn=xm.contoso.com
 * username=a_username_for_webservices_calls
 * password=user_password
 */
  function _logIn($szPathConfigFile)
  {
    if (!file_exists( $szPathConfigFile ) || is_link( $szPathConfigFile ) )
    {
      die ('Config file not found : ' . $szPathConfigFile . PHP_EOL);
    }

    $arConfig = parse_ini_file( $szPathConfigFile );
    if ($arConfig === false)
    {
      die ('Error reading config file :' . $szPathConfigFile . PHP_EOL);
    }

    if ( (!array_key_exists('fqdn', $arConfig) ) || (!array_key_exists('username', $arConfig) ) || (!array_key_exists('password', $arConfig) ) )
      die ('Missing one or more mandatory field (fqdn, username, password).'.PHP_EOL);

    $this->_oCliXM_WS = new enoola_Citrix_Client\Xenmobile_RESTWS_users( $arConfig['fqdn'], 4443, 'https', false );

    $this->assertPattern( '/Xenmobile_RESTWS_Users/', get_class($this->_oCliXM_WS), 'nom de classe ok' );
    $this->assertIsA($this->_oCliXM_WS, 'enoola_Citrix_Client\Xenmobile_RESTWS_Users');
    return ( $this->_oCliXM_WS->login($arConfig['username'], $arConfig['password'] ) );

  }

}
?>
