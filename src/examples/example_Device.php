<?Php
namespace enoola_Citrix_Client;
require_once('./class/Xenmobile_RESTWS_Exception.php');
require_once('./class/Xenmobile_RESTWS_Device.php');

/*
*
* Sample to use Xenmobile_RESTWS_Device
*
*/

$szPathConfigFile = 'config_file.ini';
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

$oCliXM_WS = new Xenmobile_RESTWS_Device( $arConfig['fqdn'], 4443, 'https',false );

if ( $oCliXM_WS->login($arConfig['username'], $arConfig['password'] ) == false )
{
  echo PHP_EOL.$oCliXM_WS->getLastRequestResult()->message.'json code:'.$oCliXM_WS->getLastRequestResult()->status.', ' .$oCliXM_WS->getLastHttpReturnCode().PHP_EOL;
  exit (1);
}
echo 'auth_token :' . $oCliXM_WS->getAuthToken() . PHP_EOL;


//var_dump( $oCliXM_WS->GetDeviceByFilters_EasySearch('user') );

//$oCliXM_WS->DisplayAvailableFilterIds();

$oCliXM_WS->GetDeviceInformationByID(2);
$deviceToken = $oCliXM_WS->getLastRequestResult()->device->deviceToken;
$oCliXM_WS->SendNotificationToAListOfDevice(2, $deviceToken, 'a notification.');

print_r($oCliXM_WS->getLastRequestResult() );



//print_r($oCliXM_WS->GetAppsByDeviceID(74));
//print_r($oCliXM_WS->GetDeliveryGroupsByDeviceID(74));
//print_r($oCliXM_WS->GetManagedSoftwareInventoryByDeviceID(74));
//print_r($oCliXM_WS->GetGPSCoordinateByDeviceID(74));

//$arQuery = array('start'=>0, 'limit'=>9, "sortOrder"=>"ASC","sortColumn"=>"ID","enableCount"=>"false");
//$arDevices = $oCliXM_WS->GetDeviceByFilters($arQuery)->filteredDevicesDataList;
//print_r($arDevices);


$arQueryNotification = array ( 'smtpFrom' => 'Xenmobile',
  'to'          => array ( array( 'deviceId'      => '1',
                          'email'         => 'john.pigeret@gmail.com'
                          //'osFamily'      => 'android'
                          //'serialNumber'  => '???',
                          //'smsTo'         => '0646762733'
                          //token????
                    ) ),
  'smtpSubject' => '2sujet xm',
  'smtpMessage' => 'SMTP Message how are you?',
  //'smsMessage'  => 'SMS Message',
  //'agentMessage'=> 'agentMessage',
  'sendAsBCC'   => 'false',
  'smtp'        => 'true',
  'sms'         => 'false',
  'agent'       => 'false',
  'templateId'  => '-1');

//print_r( $oCliXM_WS->SendNotificationToAListOfDevicesOrUsers($arQueryNotification));
//print_r( $oCliXM_WS->SendMailToAListOfUserMail('Test XM WS', array('john.pigeret@gmail.com', 'xenmobile_api@mobilutils.com'), 'test WS','un message comme un autre.', true) );
//print_r( $oCliXM_WS->SendSMSToAListOfPhoneNumbers(array( '0646762733','0646762733'),'un message sms :P' ) );
//print_r( $oCliXM_WS->SendNotificationToAListOfDevicesOrUsers_Agent(1,'une Notification :D') );


//print_r( $oCliXM_WS->AuthorizeAListOfDevices( array(1,2)));


//print_r( $oCliXM_WS->CancelContainerLockOnAListOfDevices( array(1,2) ));

//print_r( $oCliXM_WS->ApplyContainerUnlockOnAListOfDevices( array(1,2) ));


//print_r( $oCliXM_WS->ResetContainerPasswordOnAListOfDevices( array(1,2) ));

//Xenmobile_RESTWS_Device::_myCallback_DoByAListOfDevices($oCliXM_WS,'arg1','arg2','arg3');
//_myCallback_DoByAListOfDevices

//$oCliXM_WS->lol(211);

//print_r( $oCliXM_WS->CancelWipeAListOfDevices(array(1)) );
//print_r( json_encode( array('name'=>'testprop','value'=>'value_of_newprop') ) );

//print_r($oCliXM_WS->AddOrUpdateADevicePropertyByDeviceID(1, array('name'=>'newprop','value'=>'value_of_newprop') ) );

//print_r($oCliXM_WS->DeleteADevicePropertyByDeviceID(1, array('name'=>'testprop', 'value'=>'valuetestprop') ) );

//print_r( json_encode( array('properties'=>array('name'=>'test','value'=>'test') )));
//print_r($oCliXM_WS->UpdateAllDevicePropertiesByDeviceID(1,array(array('name'=>'test','value'=>'test')  )));



//print_r( $oCliXM_WS->GetDeviceLastLocationByDeviceID(2) );

//print_r($oCliXM_WS->GeneratePinCode( array('parameters'=>array('pinCodeLength'=>8 ) ) ) );


//var_dump( $oCliXM_WS->getLastRequestCurlError());

echo 'will logout' . PHP_EOL;
if ( $oCliXM_WS->logout() == true)
{
  echo 'status : ' . $oCliXM_WS->getLastRequestResult()->Status . PHP_EOL;
}

?>
