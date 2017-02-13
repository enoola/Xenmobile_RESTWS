<?php
/*
 *
 * We will test out Device methods real and 'virtuals'.
 *
 * I did this with an android device !
 */

require_once('./extlibs/simpletest/autorun.php');
require_once('./class/Xenmobile_RESTWS_Device.php');

class Xenmobile_RESTWS_DeviceTest extends UnitTestCase
{
  protected $_oCliXM_WS = null;
  const DEVICE_ID = 2;

  function setUp()
  {
    $this->_logIn('config_file.ini');
  }

  function tearDown()
  {
    $this->_oCliXM_WS->__destruct();
    $this->_oCliXM_WS = null;
  }

  function testGetDeviceByFilters_Easy()
  {
    $this->assertIdentical($this->_oCliXM_WS->GetDeviceByFilters_EasySearch('user')->status,0);
  }

  function testGetAvailableFilterIds()
  {
    $this->assertTrue(is_array( $this->_oCliXM_WS->GetAvailableFilterIds() ) );
  }

  function testGetDeviceInformationByID()
  {
    $this->assertIdentical( $this->_oCliXM_WS->GetDeviceInformationByID(self::DEVICE_ID)->status, 0);
  }

  function testGetAppsByDeviceID()
  {
    $this->assertIdentical( $this->_oCliXM_WS->GetAppsByDeviceID(self::DEVICE_ID)->status, 0);
    $this->assertNotNull( $this->_oCliXM_WS->getLastRequestResult()->applications );
  }

  function testGetActionsByDeviceID()
  {
    $this->assertIdentical( $this->_oCliXM_WS->GetActionsByDeviceID(self::DEVICE_ID)->status, 0);
    $this->assertNotNull( $this->_oCliXM_WS->getLastRequestResult()->actions );
  }

  function testGetDeliveryGroupsByDeviceID()
  {
    $this->assertIdentical( $this->_oCliXM_WS->GetDeliveryGroupsByDeviceID(self::DEVICE_ID)->status, 0);
    $this->assertNotNull( $this->_oCliXM_WS->getLastRequestResult()->deliveryGroups );
  }

  function testGetManagedSoftwareInventoryByDeviceID()
  {
    $this->assertIdentical( $this->_oCliXM_WS->GetManagedSoftwareInventoryByDeviceID(self::DEVICE_ID)->status, 0);
    $this->assertNotNull( $this->_oCliXM_WS->getLastRequestResult()->softwareInventory );
  }

  function testGetPoliciesByDeviceID()
  {
    $this->assertIdentical( $this->_oCliXM_WS->GetPoliciesByDeviceID(self::DEVICE_ID)->status, 0);
    $this->assertNotNull( $this->_oCliXM_WS->getLastRequestResult()->policies );
  }

  function testGetSoftwareInventoryByDeviceID()
  {
    $this->assertIdentical( $this->_oCliXM_WS->GetSoftwareInventoryByDeviceID(self::DEVICE_ID)->status, 0);
    $this->assertTrue( property_exists($this->_oCliXM_WS->getLastRequestResult(), 'softwareInventories' ) );
  }

  function testGetGPSCoordinateByDeviceID()
  {
    $this->assertIdentical( $this->_oCliXM_WS->GetGPSCoordinateByDeviceID(self::DEVICE_ID)->status, 0);
    $this->assertTrue( property_exists($this->_oCliXM_WS->getLastRequestResult(), 'deviceCoordinates' ) );
    $this->assertNotNull( $this->_oCliXM_WS->getLastRequestResult()->deviceCoordinates );
  }

  function testSendMailToAListOfUserMail()
  {
    //SendMailToAListOfUserMail( $szFrom, $arMailRecipient, $szSubject, $szBody, $bSendAsBCC )
    $arRet = $this->_oCliXM_WS->SendMailToAListOfUserMail('Test XM WS',
                                          array('john.pigeret@gmail.com',
                                                'xenmobile_api@mobilutils.com'),
                                                'test WS',
                                                'un message comme un autre.',
                                                true );

    $this->assertTrue( property_exists($this->_oCliXM_WS->getLastRequestResult(), 'notificationRequests' ) );
    $this->assertNotNull( $this->_oCliXM_WS->getLastRequestResult()->notificationRequests );
    $this->assertTrue( property_exists($this->_oCliXM_WS->getLastRequestResult()->notificationRequests, 'smtpNotifRequestId' ) );
    $this->assertNotNull( $this->_oCliXM_WS->getLastRequestResult()->notificationRequests->smtpNotifRequestId );

    $this->assertTrue( ($this->_oCliXM_WS->getLastRequestResult()->notificationRequests->smtpNotifRequestId > 0) );
  }

  function testSendSMSToAListOfPhoneNumbers()
  {
    $this->_oCliXM_WS->SendSMSToAListOfPhoneNumbers(array ('+33123456789'),
                                                          'un message comme un autre.');

    $this->assertTrue( property_exists($this->_oCliXM_WS->getLastRequestResult(), 'notificationRequests' ) );
    $this->assertNotNull( $this->_oCliXM_WS->getLastRequestResult()->notificationRequests );
    $this->assertTrue( property_exists($this->_oCliXM_WS->getLastRequestResult()->notificationRequests, 'smsNotifRequestId' ) );
    $this->assertNotNull( $this->_oCliXM_WS->getLastRequestResult()->notificationRequests->smsNotifRequestId );

    $this->assertTrue( ($this->_oCliXM_WS->getLastRequestResult()->notificationRequests->smsNotifRequestId > 0) );
  }

  function testSendPushNotificationToAListOfDevice()
  {
    //first we get device information
    $this->_oCliXM_WS->GetDeviceInformationByID( self::DEVICE_ID );
    $deviceToken = $this->_oCliXM_WS->getLastRequestResult()->device->deviceToken;
    $this->_oCliXM_WS->SendPushNotificationToAListOfDevice(self::DEVICE_ID, $deviceToken, 'a notification.');
  }

  function testGetAllKnownPropertiesOnADevice()
  {
    $this->_oCliXM_WS->GetAllKnownPropertiesOnADevice( self::DEVICE_ID );

    $this->assertTrue( property_exists($this->_oCliXM_WS->getLastRequestResult(), 'knownProperties' ) );
    $this->assertNotNull( $this->_oCliXM_WS->getLastRequestResult()->knownProperties );
    $this->assertTrue( property_exists($this->_oCliXM_WS->getLastRequestResult()->knownProperties, 'knownProperties' ) );
    $this->assertNotNull( $this->_oCliXM_WS->getLastRequestResult()->knownProperties->knownProperties );

    $this->assertTrue( property_exists($this->_oCliXM_WS->getLastRequestResult()->knownProperties->knownProperties, 'knownPropertyList' ) );
    $this->assertNotNull( $this->_oCliXM_WS->getLastRequestResult()->knownProperties->knownProperties->knownPropertyList );
  }

  function testGetAllUsedPropertiesOnADevice()
  {
    $this->_oCliXM_WS->GetAllUsedPropertiesOnADevice( self::DEVICE_ID );

    $this->assertTrue( property_exists($this->_oCliXM_WS->getLastRequestResult(), 'deviceUsedPropertiesList' ) );
    $this->assertNotNull( $this->_oCliXM_WS->getLastRequestResult()->deviceUsedPropertiesList );

    $this->assertTrue( property_exists($this->_oCliXM_WS->getLastRequestResult()->deviceUsedPropertiesList, 'deviceUsedProperties' ) );
    $this->assertNotNull( $this->_oCliXM_WS->getLastRequestResult()->deviceUsedPropertiesList->deviceUsedProperties );

    $this->assertTrue( property_exists($this->_oCliXM_WS->getLastRequestResult()->deviceUsedPropertiesList->deviceUsedProperties, 'deviceUsedPropertiesParameters' ) );
    $this->assertNotNull( $this->_oCliXM_WS->getLastRequestResult()->deviceUsedPropertiesList->deviceUsedProperties->deviceUsedPropertiesParameters );
  }

  function testGetAllDevicePropertiesByDeviceID()
  {
    $this->_oCliXM_WS->GetAllDevicePropertiesByDeviceID( self::DEVICE_ID );

    $this->assertTrue( property_exists($this->_oCliXM_WS->getLastRequestResult(), 'device' ) );
    $this->assertNotNull( $this->_oCliXM_WS->getLastRequestResult()->device );

    $this->assertTrue( property_exists($this->_oCliXM_WS->getLastRequestResult()->device, 'id' ) );
    $this->assertTrue( $this->_oCliXM_WS->getLastRequestResult()->device->id == self::DEVICE_ID );
  }


  /*
   * Aim is to test updates (kind of difficult method To Be Honest)
   * Below a working example
   * note we save properties then update one !!
   *
  */

  function testUpdateAllDevicePropertiesByDeviceID()
  {
    $bFound = false;
    $nKeyFounded = -1;

    /* First we get All properties */
    $curProperties = $this->_oCliXM_WS->GetAllDevicePropertiesByDeviceID( self::DEVICE_ID )->device->properties;

    //get oldvalue and update it
    foreach ( $this->_oCliXM_WS->getLastRequestResult()->device->properties as $curKey => $oneProperty )
    {
      if ( strcmp($oneProperty->name, 'SAMSUNG_MDM_VERSION') == 0 )
      {
        $bFound = true;
        $nKeyFound = $curKey;
        break;
      }
    }
    $this->assertTrue( $bFound );

    $newProperties = $curProperties;
    $newProperties[$nKeyFound]->value = $newProperties[$nKeyFound]->value + 1;

    /*
     * We create an acceptable array for Xenmobile...
     *
     * Second we modify one of the properties
     *
     */
    $acceptableProperties = array();
    foreach ( $newProperties as $key => $value)
    {
      $acceptableProperties[$key] = array();
      $acceptableProperties[$key]['name'] = $value->name;
      $acceptableProperties[$key]['value'] = $value->value;
    }

    /*
     * /!\ we updates properties (it's en android device might be good to
     extends in order to support ios devices ?)
     */
    $this->_oCliXM_WS->UpdateAllDevicePropertiesByDeviceID( self::DEVICE_ID, $acceptableProperties );

    $this->_oCliXM_WS->GetAllDevicePropertiesByDeviceID( self::DEVICE_ID );
    $readVersion_SAMSUNG_MDM_VERSION = -1;

    foreach ( $this->_oCliXM_WS->getLastRequestResult()->device->properties as $curKey => $oneProperty )
    {
      if ( strcmp($oneProperty->name, 'SAMSUNG_MDM_VERSION') == 0 )
      {
        $bFound = true;
        $readVersion_SAMSUNG_MDM_VERSION = (int)$oneProperty->value;
        $nKeyFound = $curKey;
        break;
      }
    }

    $this->assertTrue( $bFound );
    $this->assertIdentical( $readVersion_SAMSUNG_MDM_VERSION, $newProperties[$nKeyFound]->value);
  }


  function testAddOrUpdateADevicePropertyByDeviceID()
  {
    $this->expectException( 'enoola_Citrix_Client\Xenmobile_RESTWS_Exception' );
    $this->_oCliXM_WS->AddOrUpdateADevicePropertyByDeviceID_Easy( self::DEVICE_ID, 'SAMSUNG_MDM_VERSION', 11);
  }

  function testDeleteADevicePropertyByDeviceID()
  {
    $this->expectException( 'enoola_Citrix_Client\Xenmobile_RESTWS_Exception' );
    $this->_oCliXM_WS->DeleteADevicePropertyByDeviceID(2, array('SAMSUNG_MDM_VERSION'));
  }

  function testGeneratePinCode()
  {
    $this->_oCliXM_WS->GeneratePinCode( '6' );

    $this->assertTrue( property_exists($this->_oCliXM_WS->getLastRequestResult(), 'pinCode' ) );
    $this->assertNotNull( $this->_oCliXM_WS->getLastRequestResult()->pinCode );

    $this->assertTrue( property_exists($this->_oCliXM_WS->getLastRequestResult()->pinCode, 'answer' ) );
    $this->assertNotNull( $this->_oCliXM_WS->getLastRequestResult()->pinCode->answer );
  }

  /*
   * I commented functions that could "wipe lock.." devices :)
   */
  function testVirtualMethods()
  {
    $arVirtualMethodsName = array();
    $arVirtualMethodsName[]='AuthorizeAListOfDevices';
    //$arVirtualMethodsName[]='ApplyActivationLockBypassOnAListOfDevices';
    //$arVirtualMethodsName[]='ApplyAppLockOnAListOfDevices';
    //$arVirtualMethodsName[]='ApplyAppWipeOnAListOfDevices';
    //$arVirtualMethodsName[]='ApplyContainerLockOnAListOfDevices';
    $arVirtualMethodsName[]='CancelContainerLockOnAListOfDevices';
    //$arVirtualMethodsName[]='ApplyContainerUnlockOnAListOfDevices';
    $arVirtualMethodsName[]='CancelContainerUnlockOnAListOfDevices';
    //$arVirtualMethodsName[]='ResetContainerPasswordOnAListOfDevices';
    $arVirtualMethodsName[]='CancelResetContainerPasswordOnAListOfDevices';
    //$arVirtualMethodsName[]='DisownAListOfDevices';
    //$arVirtualMethodsName[]='LocateAListOfDevices';
    $arVirtualMethodsName[]='CancelLocateAListOfDevices';
    //$arVirtualMethodsName[]='ApplyGPSTrackingOnAListOfDevices';
    $arVirtualMethodsName[]='CancelGPSTrackingOnAListOfDevices';
    //$arVirtualMethodsName[]='LockAListOfDevices';
    $arVirtualMethodsName[]='CancelLockAListOfDevices';
    //$arVirtualMethodsName[]='LockAListOfDevices';
    $arVirtualMethodsName[]='DeployAListOfDevices';
    //$arVirtualMethodsName[]='RequestForAirPlayMirroringOnAListOfDevices';
    //$arVirtualMethodsName[]='CancelRequestForAirPlayMirroringOnAListOfDevices';
    //$arVirtualMethodsName[]='StopAirPlayMirroringOnAListOfDevices';
    //$arVirtualMethodsName[]='CancelStopAirPlayMirroringOnAListOfDevices';
    $arVirtualMethodsName[]='ClearAllRestrictionsOnAListOfDevices';
    //$arVirtualMethodsName[]='CancelClearAllRestrictionsOnAListOfDevices';
    $arVirtualMethodsName[]='RevokeAListOfDevices';
    //$arVirtualMethodsName[]='RingAListOfDevices';
    $arVirtualMethodsName[]='CancelRingAListOfDevices';
    //$arVirtualMethodsName[]='WipeAListOfDevices';
    $arVirtualMethodsName[]='CancelWipeAListOfDevices';
    //$arVirtualMethodsName[]='SelectivelyWipeAListOfDevices';
    $arVirtualMethodsName[]='CancelSelectivelyWipeAListOfDevices';
    //$arVirtualMethodsName[]='WipeTheSDCardsOnAListOfDevices';
    $arVirtualMethodsName[]='CancelWipeTheSDCardsOnAListOfDevices';

    foreach( $arVirtualMethodsName as $vMethodName)
    {
      $this->_oCliXM_WS->$vMethodName( array( self::DEVICE_ID ) );
      if( $this->_oCliXM_WS->getLastRequestResult()->status == 0 )
      {
          echo $vMethodName .' : [OK]'.PHP_EOL;
      }
      else
      {
        echo $vMethodName .' : [KO]('.$this->_oCliXM_WS->getLastRequestResult()->status.')'.PHP_EOL;
      }

    }

  }
  //now virtual methods...




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

    $this->_oCliXM_WS = new enoola_Citrix_Client\Xenmobile_RESTWS_Device( $arConfig['fqdn'], 4443, 'https', false );

    $this->assertPattern( '/Xenmobile_RESTWS_Device/', get_class($this->_oCliXM_WS), 'classname ok' );
    $this->assertIsA($this->_oCliXM_WS, 'enoola_Citrix_Client\Xenmobile_RESTWS_Device', 'something is wrong with classname');
    return ( $this->_oCliXM_WS->login($arConfig['username'], $arConfig['password'] ) );

  }

}
?>
