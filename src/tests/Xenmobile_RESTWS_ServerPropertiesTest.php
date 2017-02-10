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
require_once('./class/Xenmobile_RESTWS_ServerProperties.php');

class Xenmobile_RESTWS_ServerPropertiesTest extends UnitTestCase
{
  protected $_oCliXM_WS = null;

  function setUp()
  {
    //
    $this->_logIn('config_file.ini');
  }

  function tearDown()
  {
    $this->_oCliXM_WS->__destruct();
    $this->_oCliXM_WS = null;
  }

  function testGetAllServerPropertiesIsArray()
  {
    $arProp = $this->_oCliXM_WS->GetAllServerProperties();

    $this->assertNotNull($this->_oCliXM_WS->GetAllServerProperties(),'');
    $this->assertTrue( isset($this->_oCliXM_WS->getLastRequestResult()->allEwProperties) , 'this property shall exists' );
    $this->assertTrue(is_array($this->_oCliXM_WS->getLastRequestResult()->allEwProperties), 'and be an array');
  }

  function testServerPropertiesHaveOneProperty()
  {
    $this->assertNotNull($this->_oCliXM_WS->GetAllServerProperties(),'');
    //print_r($this->_oCliXM_WS);
    $bFound = false;
    foreach ($this->_oCliXM_WS->getLastRequestResult()->allEwProperties as $oneProperty)
    {
      if (strcmp($oneProperty->name, 'webservices.enable'))
      {
        $bFound = true;
        break;
      }
    }
    $this->assertTrue( $bFound, 'shall find an entry with name webservices.enable in allEwProperties' );
  }

  function testAddServerPropertyIsWorking()
  {

    $arNewProp = array( 'name'=> 'MySeRvErPrOpErTy',
    'value' => 'My SeRvEr PrOpErTy VaLuE',
    'displayName' => 'My SeRvEr PrOpErTy',
    'description' => 'My SeRvEr PrOpErTy description'
    );


    $this->_oCliXM_WS->AddServerProperty_Easy($arNewProp['name'], $arNewProp['value'], $arNewProp['displayName'], $arNewProp['description']);
    $this->assertIdentical($this->_oCliXM_WS->getLastRequestResult()->status, 0);

    $arQuerySearchNewProp = array("start"=> 0,
    "limit"=> 1,
    "orderBy"=> "name",
    "sortOrder"=> "desc",
    "searchStr"=> "MySeRvErPrOpErTy");

    //$this->assertIdentical($this->_oCliXM_WS->GetServerPropertiesByFilter( $arQuerySearchNewProp )->allEwProperties[0]->name, $arNewProp['name']) ;
    $this->assertIdentical($this->_oCliXM_WS->GetServerPropertiesByFilter_Easy( $arQuerySearchNewProp['searchStr'],
                                                              $arQuerySearchNewProp['orderBy'],
                                                              $arQuerySearchNewProp['sortOrder'],
                                                              $arQuerySearchNewProp['start'],
                                                              $arQuerySearchNewProp['limit'] )->allEwProperties[0]->name,
                            $arNewProp['name']) ;

    $this->assertIdentical( $this->_oCliXM_WS->getLastRequestResult()->allEwProperties[0]->value, $arNewProp['value']);
    $this->assertIdentical( $this->_oCliXM_WS->getLastRequestResult()->allEwProperties[0]->displayName, $arNewProp['displayName']);
    $this->assertIdentical( $this->_oCliXM_WS->getLastRequestResult()->allEwProperties[0]->description, $arNewProp['description']);

  }

  function testEditServerPropertyIsWorking()
  {

    $arNewProp = array( 'name'=> 'MySeRvErPrOpErTy',
    'value' => 'My SeRvEr PrOpErTy VaLuE',
    'displayName' => 'My SeRvEr PrOpErTy',
    'description' => 'My SeRvEr PrOpErTy description'
    );


    $this->_oCliXM_WS->AddServerProperty_Easy($arNewProp['name'], $arNewProp['value'], $arNewProp['displayName'], $arNewProp['description']);
    $this->assertIdentical($this->_oCliXM_WS->getLastRequestResult()->status, 0);

    $arQuerySearchNewProp = array("start"=> 0,
    "limit"=> 1,
    "orderBy"=> "name",
    "sortOrder"=> "desc",
    "searchStr"=> "MySeRvErPrOpErTy");

    //$this->assertIdentical($this->_oCliXM_WS->GetServerPropertiesByFilter( $arQuerySearchNewProp )->allEwProperties[0]->name, $arNewProp['name']) ;
    $this->assertIdentical($this->_oCliXM_WS->GetServerPropertiesByFilter_Easy( $arQuerySearchNewProp['searchStr'],
                                                              $arQuerySearchNewProp['orderBy'],
                                                              $arQuerySearchNewProp['sortOrder'],
                                                              $arQuerySearchNewProp['start'],
                                                              $arQuerySearchNewProp['limit'] )->allEwProperties[0]->name,
                            $arNewProp['name']) ;

    $this->assertIdentical( $this->_oCliXM_WS->getLastRequestResult()->allEwProperties[0]->value, $arNewProp['value']);
    $this->assertIdentical( $this->_oCliXM_WS->getLastRequestResult()->allEwProperties[0]->displayName, $arNewProp['displayName']);
    $this->assertIdentical( $this->_oCliXM_WS->getLastRequestResult()->allEwProperties[0]->description, $arNewProp['description']);

    $arnv = $arNewProp;
    $arnv['value'] = 'new Value';
    $arnv['displayName'] = 'My new server prop';
    $arnv['description'] = 'a description ...';
    //edition now
    $this->assertIdentical($this->_oCliXM_WS->EditServerProperty_Easy($arNewProp['name'],
                          $arnv['value'],$arnv['displayName'],
                          $arnv['description'] )->status, 0);


    $this->assertIdentical($this->_oCliXM_WS->GetServerPropertiesByFilter_Easy( $arQuerySearchNewProp['searchStr'],
                                                              $arQuerySearchNewProp['orderBy'],
                                                              $arQuerySearchNewProp['sortOrder'],
                                                              $arQuerySearchNewProp['start'],
                                                              $arQuerySearchNewProp['limit'] )->allEwProperties[0]->name,
                            $arNewProp['name']) ;

    $this->assertIdentical( $this->_oCliXM_WS->getLastRequestResult()->allEwProperties[0]->value, $arnv['value']);
    $this->assertIdentical( $this->_oCliXM_WS->getLastRequestResult()->allEwProperties[0]->displayName, $arnv['displayName']);
    $this->assertIdentical( $this->_oCliXM_WS->getLastRequestResult()->allEwProperties[0]->description, $arnv['description']);



  }


  function testDeleteServerPropertyIsWorking()
  {

    $arNewProp = array( 'name'=> 'MySeRvErPrOpErTy',
    'value' => 'My SeRvEr PrOpErTy VaLuE',
    'displayName' => 'My SeRvEr PrOpErTy',
    'description' => 'My SeRvEr PrOpErTy description'
    );


    $this->_oCliXM_WS->AddServerProperty_Easy($arNewProp['name'], $arNewProp['value'], $arNewProp['displayName'], $arNewProp['description']);
    $this->assertIdentical($this->_oCliXM_WS->getLastRequestResult()->status, 0);

    $arQuerySearchNewProp = array("start"=> 0,
    "limit"=> 1,
    "orderBy"=> "name",
    "sortOrder"=> "desc",
    "searchStr"=> "MySeRvErPrOpErTy");

    $this->assertIdentical($this->_oCliXM_WS->GetServerPropertiesByFilter_Easy( $arQuerySearchNewProp['searchStr'],
                                                              $arQuerySearchNewProp['orderBy'],
                                                              $arQuerySearchNewProp['sortOrder'],
                                                              $arQuerySearchNewProp['start'],
                                                              $arQuerySearchNewProp['limit'] )->allEwProperties[0]->name,
                            $arNewProp['name']) ;

    $this->assertIdentical( $this->_oCliXM_WS->getLastRequestResult()->allEwProperties[0]->value, $arNewProp['value']);
    $this->assertIdentical( $this->_oCliXM_WS->getLastRequestResult()->allEwProperties[0]->displayName, $arNewProp['displayName']);
    $this->assertIdentical( $this->_oCliXM_WS->getLastRequestResult()->allEwProperties[0]->description, $arNewProp['description']);


    $this->_oCliXM_WS->DeleteServerProperties(array($arNewProp['name'],'testprop') );
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

    $this->_oCliXM_WS = new enoola_Citrix_Client\Xenmobile_RESTWS_ServerProperties( $arConfig['fqdn'], 4443, 'https', false );

    $this->assertPattern( '/Xenmobile_RESTWS_ServerProperties/', get_class($this->_oCliXM_WS), 'nom de classe resultante KO' );
    $this->assertIsA($this->_oCliXM_WS, 'enoola_Citrix_Client\Xenmobile_RESTWS_ServerProperties');
    return ( $this->_oCliXM_WS->login($arConfig['username'], $arConfig['password'] ) );

  }

}
?>
