<?php
/*
 *
 * We will test out Netscaler class
 *
 */
// use \enoola_Citrix_Client;
require_once('./extlibs/simpletest/autorun.php');
require_once('./class/Xenmobile_RESTWS_LocalGroups.php');

class Xenmobile_RESTWS_LocalGroupsTest extends UnitTestCase
{
  const LOCAL_GROUPNAME = '__My_ATestGroup';
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


  function testGetAllLocalGroups()
  {

    $this->_oCliXM_WS->GetAllLocalGroups();

  //  print_r( $this->_oCliXM_WS->getLastRequestResult() );

    $this->assertTrue(property_exists($this->_oCliXM_WS->getLastRequestResult(), 'status'));
    $this->assertIdentical($this->_oCliXM_WS->getLastRequestResult()->status, 0);

    $this->assertTrue(property_exists($this->_oCliXM_WS->getLastRequestResult(), 'userGroups') );
    $this->assertTrue(is_array( $this->_oCliXM_WS->getLastRequestResult()->userGroups ) );
  }

  function _testSearchLocalGroupByName()
  {
    $this->_oCliXM_WS->SearchLocalGroupByName( self::LOCAL_GROUPNAME );

    $this->assertTrue(property_exists($this->_oCliXM_WS->getLastRequestResult(), 'status'));
    $this->assertIdentical($this->_oCliXM_WS->getLastRequestResult()->status, 0);
  }


  function testAddLocalGroup()
  {
    $this->_oCliXM_WS->AddLocalGroup( self::LOCAL_GROUPNAME );

    $this->assertTrue(property_exists($this->_oCliXM_WS->getLastRequestResult(), 'status'));
    $this->assertIdentical($this->_oCliXM_WS->getLastRequestResult()->status, 0);

    $this->assertTrue(property_exists($this->_oCliXM_WS->getLastRequestResult(), 'group') );
    $this->assertTrue(is_object( $this->_oCliXM_WS->getLastRequestResult()->group ) );

    $this->assertTrue(property_exists($this->_oCliXM_WS->getLastRequestResult()->group, 'name') );
    $this->assertTrue(is_string( $this->_oCliXM_WS->getLastRequestResult()->group->name ) );
    $this->assertTrue(strcmp( $this->_oCliXM_WS->getLastRequestResult()->group->name, self::LOCAL_GROUPNAME ) == 0 );
  }

  function testDeleteLocalGroup()
  {
    $this->_oCliXM_WS->DeleteLocalGroup( self::LOCAL_GROUPNAME );

    $this->assertTrue(property_exists($this->_oCliXM_WS->getLastRequestResult(), 'status'));
    $this->assertIdentical($this->_oCliXM_WS->getLastRequestResult()->status, 0);

    $this->assertTrue(property_exists($this->_oCliXM_WS->getLastRequestResult(), 'userGroups') );
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

    $this->_oCliXM_WS = new enoola_Citrix_Client\Xenmobile_RESTWS_LocalGroups( $arConfig['fqdn'], 4443, 'https', false );

    $this->assertPattern( '/Xenmobile_RESTWS_LocalGroups/', get_class($this->_oCliXM_WS), 'nom de classe ok' );
    $this->assertIsA($this->_oCliXM_WS, 'enoola_Citrix_Client\Xenmobile_RESTWS_LocalGroups');
    return ( $this->_oCliXM_WS->login($arConfig['username'], $arConfig['password'] ) );

  }

}
?>
