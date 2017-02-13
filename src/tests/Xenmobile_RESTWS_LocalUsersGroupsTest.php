<?php
/*
 *
 * We will test out LocalUsersGroups class
 *
 */
// use \enoola_Citrix_Client;
require_once('./extlibs/simpletest/autorun.php');
require_once('./class/Xenmobile_RESTWS_LocalUsersGroups.php');

class Xenmobile_RESTWS_LocalUsersGroupsTest extends UnitTestCase
{
  const ONE_EXISTING_USERNAME = 'user1';
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


  function testGetAllLocalUsers()
  {
    $this->_oCliXM_WS->GetAllLocalUsers();

    $this->assertTrue(property_exists($this->_oCliXM_WS->getLastRequestResult(), 'status'));
    $this->assertIdentical($this->_oCliXM_WS->getLastRequestResult()->status, 0);

    $this->assertTrue(property_exists($this->_oCliXM_WS->getLastRequestResult(), 'result'));
    $this->assertTrue(is_array( $this->_oCliXM_WS->getLastRequestResult()->result) );
  }

  function testGetOneLocalUser()
  {
    $this->_oCliXM_WS->GetOneLocalUser(self::ONE_EXISTING_USERNAME);

    $this->assertTrue(property_exists($this->_oCliXM_WS->getLastRequestResult(), 'status'));
    $this->assertIdentical($this->_oCliXM_WS->getLastRequestResult()->status, 0);

    $this->assertTrue(property_exists($this->_oCliXM_WS->getLastRequestResult(), 'result'));
    $this->assertTrue(is_object( $this->_oCliXM_WS->getLastRequestResult()->result) );
    $this->assertTrue(property_exists($this->_oCliXM_WS->getLastRequestResult()->result, 'username'));
    $this->assertIdentical( $this->_oCliXM_WS->getLastRequestResult()->result->username, self::ONE_EXISTING_USERNAME );
  }

  function testAddOneLocalUser()
  {
    $this->_oCliXM_WS->AddOneLocalUser_Easy('newuser1','apassword');

    //print_r($this->_oCliXM_WS->getLastJsonRequest());
    //print_r($this->_oCliXM_WS->getLastRequestResult());
    $this->assertTrue(property_exists($this->_oCliXM_WS->getLastRequestResult(), 'status'));
    $this->assertIdentical($this->_oCliXM_WS->getLastRequestResult()->status, 0);

    $this->assertTrue(property_exists($this->_oCliXM_WS->getLastRequestResult(), 'user'));
    $this->assertTrue(is_object( $this->_oCliXM_WS->getLastRequestResult()->user ));

    $this->assertTrue(property_exists($this->_oCliXM_WS->getLastRequestResult()->user, 'username'));
    $this->assertIdentical($this->_oCliXM_WS->getLastRequestResult()->user->username, 'newuser1');

    $this->assertTrue(property_exists($this->_oCliXM_WS->getLastRequestResult()->user, 'password'));
    $this->assertIdentical( $this->_oCliXM_WS->getLastRequestResult()->user->password, 'apassword');

    //$this->assertTrue(property_exists($this->_oCliXM_WS->getLastRequestResult(), 'result'));
    //$this->assertTrue(is_object( $this->_oCliXM_WS->getLastRequestResult()->result) );
  }

  function _testUpdateOneLocalUser()
  {
    $this->_oCliXM_WS->UpdateOneLocalUser_Easy('newuser1',null,'ADMIN');

    //print_r($this->_oCliXM_WS->getLastJsonRequest());
    print_r($this->_oCliXM_WS->getLastRequestResult());

    //$this->assertTrue(property_exists($this->_oCliXM_WS->getLastRequestResult(), 'status'));
    //$this->assertIdentical($this->_oCliXM_WS->getLastRequestResult()->status, 0);

    //$this->assertTrue(property_exists($this->_oCliXM_WS->getLastRequestResult(), 'user'));
    //$this->assertTrue(is_object( $this->_oCliXM_WS->getLastRequestResult()->user ));

    //$this->assertTrue(property_exists($this->_oCliXM_WS->getLastRequestResult()->user, 'username'));
    //$this->assertIdentical($this->_oCliXM_WS->getLastRequestResult()->user->username, 'newuser1');
  }

  function testDeleteOneLocalUser()
  {
    //$this->_oCliXM_WS->DeleteUsers(array('newuser1') );
    $this->_oCliXM_WS->DeleteOneUser('newuser1');

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

    $this->_oCliXM_WS = new enoola_Citrix_Client\Xenmobile_RESTWS_LocalUsersGroups( $arConfig['fqdn'], 4443, 'https', false );

    $this->assertPattern( '/Xenmobile_RESTWS_LocalUsersGroups/', get_class($this->_oCliXM_WS), 'nom de classe ok' );
    $this->assertIsA($this->_oCliXM_WS, 'enoola_Citrix_Client\Xenmobile_RESTWS_LocalUsersGroups');
    return ( $this->_oCliXM_WS->login($arConfig['username'], $arConfig['password'] ) );

  }

}
?>
