<?php
/*
 *
 * We will test out LocalUsers class
 *
 */
require_once('./extlibs/simpletest/autorun.php');
require_once('./class/Xenmobile_RESTWS_LocalUsers.php');

class Xenmobile_RESTWS_LocalUsersTest extends UnitTestCase
{
  const ONE_TEST_USERNAME = 'MyTestUser1';
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

  function testAddOneLocalUser()
  {
    $this->_oCliXM_WS->AddOneLocalUser_Easy(self::ONE_TEST_USERNAME, 'apassword', 'USER');

    //print_r($this->_oCliXM_WS->getLastJsonRequest());
    //print_r($this->_oCliXM_WS->getLastRequestResult());
    $this->assertTrue(property_exists($this->_oCliXM_WS->getLastRequestResult(), 'status'));
    $this->assertIdentical($this->_oCliXM_WS->getLastRequestResult()->status, 0);

    $this->assertTrue(property_exists($this->_oCliXM_WS->getLastRequestResult(), 'user'));
    $this->assertTrue(is_object( $this->_oCliXM_WS->getLastRequestResult()->user ));

    $this->assertTrue(property_exists($this->_oCliXM_WS->getLastRequestResult()->user, 'username'));
    $this->assertIdentical($this->_oCliXM_WS->getLastRequestResult()->user->username, self::ONE_TEST_USERNAME);

    $this->assertTrue(property_exists($this->_oCliXM_WS->getLastRequestResult()->user, 'password'));
    $this->assertIdentical( $this->_oCliXM_WS->getLastRequestResult()->user->password, 'apassword');

    //$this->assertTrue(property_exists($this->_oCliXM_WS->getLastRequestResult(), 'result'));
    //$this->assertTrue(is_object( $this->_oCliXM_WS->getLastRequestResult()->result) );
  }

  function testGetOneLocalUser()
  {
    $this->_oCliXM_WS->GetOneLocalUser( self::ONE_TEST_USERNAME );

    $this->assertTrue(property_exists($this->_oCliXM_WS->getLastRequestResult(), 'status'));
    $this->assertIdentical($this->_oCliXM_WS->getLastRequestResult()->status, 0);

    $this->assertTrue(property_exists($this->_oCliXM_WS->getLastRequestResult(), 'result'));
    $this->assertTrue(is_object( $this->_oCliXM_WS->getLastRequestResult()->result) );
    $this->assertTrue(property_exists($this->_oCliXM_WS->getLastRequestResult()->result, 'username'));
    $this->assertIdentical( $this->_oCliXM_WS->getLastRequestResult()->result->username, self::ONE_TEST_USERNAME );
  }


  function testUpdateOneLocalUser()
  {
    //$this->_oCliXM_WS->UpdateOneLocalUser_Easy(self::ONE_TEST_USERNAME, null, 'ADMIN');

    $arInfos = array('username' => self::ONE_TEST_USERNAME,
                    'password' => '',
                    'role'=> 'USER',
                    'groups'=>array(),
                    'attributes'=> new \StdClass
              );

    $this->_oCliXM_WS->UpdateOneLocalUser( $arInfos );

    $this->assertTrue(property_exists($this->_oCliXM_WS->getLastRequestResult(), 'status'));
    $this->assertIdentical($this->_oCliXM_WS->getLastRequestResult()->status, 0);

    $this->assertTrue(property_exists($this->_oCliXM_WS->getLastRequestResult(), 'user'));
    $this->assertTrue(is_object( $this->_oCliXM_WS->getLastRequestResult()->user ));

    $this->assertTrue(property_exists($this->_oCliXM_WS->getLastRequestResult()->user, 'username'));
    $this->assertTrue(strcmp($this->_oCliXM_WS->getLastRequestResult()->user->username, self::ONE_TEST_USERNAME) == 0);
  }

  function testDeleteOneLocalUser()
  {
    $this->_oCliXM_WS->DeleteOneUser(self::ONE_TEST_USERNAME);

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

    $this->_oCliXM_WS = new enoola_Citrix_Client\Xenmobile_RESTWS_LocalUsers( $arConfig['fqdn'], 4443, 'https', false );

    $this->assertPattern( '/Xenmobile_RESTWS_LocalUsers/', get_class($this->_oCliXM_WS), 'nom de classe ok' );
    $this->assertIsA($this->_oCliXM_WS, 'enoola_Citrix_Client\Xenmobile_RESTWS_LocalUsers');

    return ( $this->_oCliXM_WS->login($arConfig['username'], $arConfig['password'] ) );
  }

}
?>
