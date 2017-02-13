<?php
/*
 *
 * We will test out Netscaler class
 *
 */
// use \enoola_Citrix_Client;
require_once('./extlibs/simpletest/autorun.php');
require_once('./class/Xenmobile_RESTWS_DeliveryGroups.php');

class Xenmobile_RESTWS_DeliveryGroups extends UnitTestCase
{
  protected $_oCliXM_WS = null;
  const DELIVERY_GROUPNAME = "__Test__DeliveryOne";

  function setUp()
  {
    $this->_logIn('config_file.ini');
  }

  function tearDown()
  {
    $this->_oCliXM_WS->__destruct();
    $this->_oCliXM_WS = null;
  }


  function testGetDeliveryGroupsByFilter()
  {
    //$arQuery = array('start' => 0, 'sortOrder' => 'DESC','deliveryGroupSortColumn'=>'id','search'=>self::DELIVERY_GROUPNAME);

    $this->_oCliXM_WS->GetDeliveryGroupsByFilter_Easy( self::DELIVERY_GROUPNAME );

    $this->assertTrue(property_exists($this->_oCliXM_WS->getLastRequestResult(), 'status'));
    $this->assertIdentical($this->_oCliXM_WS->getLastRequestResult()->status, 0);

    $this->assertTrue(property_exists($this->_oCliXM_WS->getLastRequestResult(), 'dgListData'));
    $this->assertTrue(is_object( $this->_oCliXM_WS->getLastRequestResult()->dgListData ));

    $this->assertTrue(property_exists($this->_oCliXM_WS->getLastRequestResult()->dgListData, 'dgList'));
    $this->assertTrue( is_array($this->_oCliXM_WS->getLastRequestResult()->dgListData->dgList) );
  }

/*
  {
      "name": "temp3",
      "description": "temp3 desc",
      "applications": [
  {
                     "name": "TESTAPP",
                      "priority": -1,
                      "required": false
                                  } ],
         "devicePolicies": [
             {
                     "name":"test terms conditions",
                     "priority":-1
              }
         ],
         "smartActions": [
             {
                 "name":"Smart Action Name 1",
                 "priority":-1
              }
         ],
      "groups": [
          {
     "uniqueName": "AC08EP61S75",
              "domainName": "local",
              "name": "AC08EP61S75",
              "objectSid": "AC08EP61S75",
  "uniqueId": "AC08EP61S75",
  "customProperties": {
          "gr1": "gr1",
          "gr2": "gr2"
      }
          }
      ],
      "users": [
          {
              "uniqueName": "testuser",
              "domainName": "local",
              "name": " testuser ",
              "objectId": " testuser "
          }
      ],
      "rules": "{\"AND\":[{\"eq\":{\"property\":{\"type\":\"USER_PROPERTY\",\"name\":\"mail\"},\"type\":\"STRING\",\"value\":\" testuser@citrix.com\"}}]}"
  }
*/
function testAddOneDeliveryGroup()
{
  $arQuery = array(
    'name'=>self::DELIVERY_GROUPNAME,
    'description'=>'a description added by wsd client',
    //'applications'=> array(),
    //'devicePolicies'=> array(),
    //'smartActions'=> array(),
    'groups'=> array(),
    'users' => array()
  );

  $this->_oCliXM_WS->AddOneDeliveryGroup( $arQuery );

  $this->assertTrue(property_exists($this->_oCliXM_WS->getLastRequestResult(), 'status'));
  $this->assertIdentical($this->_oCliXM_WS->getLastRequestResult()->status, 0);

  $this->assertTrue(property_exists($this->_oCliXM_WS->getLastRequestResult(), 'role'));
  $this->assertTrue(is_object( $this->_oCliXM_WS->getLastRequestResult()->role ));
}

function testGetDeliveryGroupByName()
{
  $this->_oCliXM_WS->GetDeliveryGroupByName( self::DELIVERY_GROUPNAME );

  $this->assertTrue(property_exists($this->_oCliXM_WS->getLastRequestResult(), 'status'));
  $this->assertIdentical($this->_oCliXM_WS->getLastRequestResult()->status, 0);

  $this->assertTrue(property_exists($this->_oCliXM_WS->getLastRequestResult(), 'role'));
  $this->assertTrue(is_object( $this->_oCliXM_WS->getLastRequestResult()->role ));

  $this->assertTrue(property_exists($this->_oCliXM_WS->getLastRequestResult()->role, 'name'));
  $this->assertIdentical( $this->_oCliXM_WS->getLastRequestResult()->role->name, self::DELIVERY_GROUPNAME);
}

function testEditDeliveryGroup()
{
  $arQuery = array(
    'name'=>self::DELIVERY_GROUPNAME,
    'description'=>'a description  asdas added by asd wsdsss',
    //'applications'=> array(),
    //'devicePolicies'=> array(),
    //'smartActions'=> array(),
    'groups'=> array(),
    'users' => array()
  );

  $this->_oCliXM_WS->EditDeliveryGroup( $arQuery );

  $this->assertTrue(property_exists($this->_oCliXM_WS->getLastRequestResult(), 'status'));
  $this->assertIdentical($this->_oCliXM_WS->getLastRequestResult()->status, 0);

  $this->assertTrue(property_exists($this->_oCliXM_WS->getLastRequestResult(), 'role'));
  $this->assertTrue(is_object( $this->_oCliXM_WS->getLastRequestResult()->role ));
}


//Not working
function _testEnableOrDisableOneDeliveryGroup()
{
  //echo 'wtf ??'.PHP_EOL;
  $this->_oCliXM_WS->EnableOrDisableOneDeliveryGroup( self::DELIVERY_GROUPNAME, 'disable' );

  //echo 'wtf ??'.PHP_EOL;

  $this->assertTrue(property_exists($this->_oCliXM_WS->getLastRequestResult(), 'status'));
  $this->assertIdentical($this->_oCliXM_WS->getLastRequestResult()->status, 0);

}

function testDeleteDeliveryGroups()
{
  $this->_oCliXM_WS->DeleteDeliveryGroups( array( self::DELIVERY_GROUPNAME ) );

  //print_r( $this->_oCliXM_WS->getLastJsonRequest());
  //print_r( $this->_oCliXM_WS->getLastRequestResult() );

  $this->assertTrue(property_exists($this->_oCliXM_WS->getLastRequestResult(), 'status'));
  $this->assertIdentical($this->_oCliXM_WS->getLastRequestResult()->status, 0);

  $this->assertTrue(property_exists($this->_oCliXM_WS->getLastRequestResult(), 'roleNames'));
  $this->assertTrue(is_array( $this->_oCliXM_WS->getLastRequestResult()->roleNames ));
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

    $this->_oCliXM_WS = new enoola_Citrix_Client\Xenmobile_RESTWS_DeliveryGroups( $arConfig['fqdn'], 4443, 'https', false );

    $this->assertPattern( '/Xenmobile_RESTWS_DeliveryGroups/', get_class($this->_oCliXM_WS), 'nom de classe ko' );
    $this->assertIsA($this->_oCliXM_WS, 'enoola_Citrix_Client\Xenmobile_RESTWS_DeliveryGroups');
    return ( $this->_oCliXM_WS->login($arConfig['username'], $arConfig['password'] ) );

  }

}
?>
