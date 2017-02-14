<?php
/*
 *
 * We will test out authentication methods
 * Bear in mind I have created 3 ini_file
 * containing fqdn,username,password fields
 *
 * badfqdn_config_file : bad fqdn
 * badusername_config_file : bad username/password
 * config_file : working
 */
// use \enoola_Citrix_Client;
require_once('./extlibs/simpletest/autorun.php');
require_once('./class/Xenmobile_RESTWS_Authentication.php');

class Xenmobile_RESTWS_AuthenticationTest extends UnitTestCase
{
  protected $_oCliXM_WS = null;

  function setUp()
  {
    //
  }

  function tearDown()
  {
    //
  }

  function testLoggingWithGoodCredentials()
  {
    $bRet = $this->_logIn('config_file.ini');
    $this->assertNotEqual($this->_oCliXM_WS->getAuthToken(),'');

    $ini = parse_ini_file('config_file.ini');
    $this->assertIdentical($this->_oCliXM_WS->getUsername(), $ini['username']);

    $this->_oCliXM_WS = null;
  }

  function testLoggingWithBadFQDN()
  {
    $bRet = $this->_logIn('./tests/badfqdn_config_file.ini');
    $this->assertEqual($this->_oCliXM_WS->getAuthToken(),'');

    $ini = parse_ini_file('./tests/badfqdn_config_file.ini');
    $this->assertIdentical($this->_oCliXM_WS->getUsername(), $ini['username']);

    $this->_oCliXM_WS = null;
  }

  function testLoggingWithBadUsername()
  {
    $bRet = $this->_logIn('./tests/badusername_config_file.ini');
    $this->assertEqual($this->_oCliXM_WS->getAuthToken(),'');

    $ini = parse_ini_file('./tests/badusername_config_file.ini');
    $this->assertIdentical($this->_oCliXM_WS->getUsername(), $ini['username']);

    $this->_oCliXM_WS = null;
  }

  function testLogout()
  {
    $this->_logIn('config_file.ini');
    $this->_oCliXM_WS->logout();

    $this->assertNull( $this->_oCliXM_WS->getAuthToken(), 'authtoken shall be null at this point' );
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

    $this->_oCliXM_WS = new enoola_Citrix_Client\Xenmobile_RESTWS_Authentication( $arConfig['fqdn'], 4443, 'https', false );

    $this->assertPattern( '/Xenmobile_RESTWS_Authentication/', get_class($this->_oCliXM_WS), 'nom de classe ok' );
    $this->assertIsA($this->_oCliXM_WS, 'enoola_Citrix_Client\Xenmobile_RESTWS_Authentication');
    return ( $this->_oCliXM_WS->login($arConfig['username'], $arConfig['password'] ) );

  }

}
?>
