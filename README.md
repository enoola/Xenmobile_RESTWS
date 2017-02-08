# This is a PhP Client for Xenmobile REST Webservice


Aim : Provide a PhP client for Citrix Xenmobile Rest Webservices (for version superior or equal to 10.3)
Means : php mainly maybe other later like ( composer, pear, phpDocumentor, github, community I hope :) )
Constraints :
  - provider webservices responses can be disturbing (with a status containing a message without a status..),
  so singularity have been added to classes in order to handle that.

##Pre-requisites
Citrix Xenmobile (version >10.3), if you have equal to 10.3 it will work with most of the functions implemented but not all.
Do not hesitate to reach out if you need help in implementing workaround methods or whatever.
 . https://docs.citrix.com/content/dam/docs/en-us/xenmobile/10-2/Downloads/XenMobile-Public-API.pdf
 . https://docs.citrix.com/en-us/xenmobile/10-3/xenmobile-rest-api-reference-main.html

PhP >= 5.6
 . https://secure.php.net/manual/en/

#Implemented class/method

- Xenmobile_RESTWS_Abstract
  - Xenmobile_RESTWS_Authentication /authentication
    - login : /login
    - cwclogin : /cwclogin
    - logout : /logout
  - Xenmobile_RESTWS_Device /device
    - GetDeviceByFilters /filter
    - GetDeviceByFilters_EasySearch
    - GetAvailableFilterIds
    - DisplayAvailableFilterIds
    - GetDeviceInformationByID /filter/{id}
    - AuthorizeAListOfDevices(ar_ids)
    - ApplyActivationLockBypassOnAListOfDevices(ar_ids)
    - ApplyAppLockOnAListOfDevices(ar_ids)
    - ApplyAppWipeOnAListOfDevices(ar_ids)
    - ApplyContainerLockOnAListOfDevices(ar_ids)
    - CancelContainerLockOnAListOfDevices(ar_ids)
    - ApplyContainerUnlockOnAListOfDevices(ar_ids)
    - CancelContainerUnlockOnAListOfDevices(ar_ids)
    - ResetContainerPasswordOnAListOfDevices(ar_ids)
    - CancelResetContainerPasswordOnAListOfDevices(ar_ids)
    - DisownAListOfDevices(ar_ids)
    - LocateAListOfDevices(ar_ids)
    - CancelLocateAListOfDevices(ar_ids)
    - ApplyGPSTrackingOnAListOfDevices(ar_ids)
    - CancelGPSTrackingOnAListOfDevices(ar_ids)
    - LockAListOfDevices(ar_ids)
    - CancelLockAListOfDevices(ar_ids)
    - LockAListOfDevices(ar_ids)
    - DeployAListOfDevices(ar_ids)
    - RequestForAirPlayMirroringOnAListOfDevices(ar_ids)
    - CancelRequestForAirPlayMirroringOnAListOfDevices(ar_ids)
    - StopAirPlayMirroringOnAListOfDevices(ar_ids)
    - CancelStopAirPlayMirroringOnAListOfDevices(ar_ids)
    - ClearAllRestrictionsOnAListOfDevices(ar_ids)
    - CancelClearAllRestrictionsOnAListOfDevices(ar_ids)
    - RevokeAListOfDevices(ar_ids)
    - RingAListOfDevices(ar_ids)
    - CancelRingAListOfDevices(ar_ids)
    - WipeAListOfDevices(ar_ids)
    - CancelWipeAListOfDevices(ar_ids)
    - SelectivelyWipeAListOfDevices(ar_ids)
    - CancelSelectivelyWipeAListOfDevices(ar_ids)
    - WipeTheSDCardsOnAListOfDevices(ar_ids)
    - CancelWipeTheSDCardsOnAListOfDevices(ar_ids)
    - GetAllKnownPropertiesOnADevice(device_id) test=KO
    - GetAllUsedPropertiesOnADevice(devide_id) test=KO
    - GetAllDevicePropertiesByDeviceID(device_id)
    - UpdateAllDevicePropertiesByDeviceID(device_id, arNewDeviceProps)
    - AddOrUpdateADevicePropertyByDeviceID test=KO
    - DeleteADevicePropertyByDeviceID test=KO argument no clear
    - GetiOSDeviceMDMStatusbyDeviceID(devide_id)
    - GeneratePinCode(devide_id)
    - GetDeviceLastLocationByDeviceID(device_id) xm 10.4 which I haven't yet.

   NO Obvious Method to add devices.
   Need to dig that. Was doable with SOAP
   
  - Xenmobile_RESTWS_ServerProperties : /serverproperties
    - GetAll()
    - GetByFilter(ar_filter)


##Usage Example
```php
/*
 * Consume authentication webservice
 * login, logout
 */
./require_once('./class/Xenmobile_RESTWS_Authentication.php');
$szFQDN = 'xenmobile.contoso.com';

$oCliXM_WS = new Xenmobile_RESTWS_Main( $szFQDN, 4443, 'https',false );

if ( $oCliXM_WS->login('username', 'password' ) == false )
{
  echo PHP_EOL.$oCliXM_WS->_getLastRequestResult()->message.'json code:'.
        $oCliXM_WS->_getLastRequestResult()->status.', ' .
        $oCliXM_WS->getLastHttpReturnCode().
        PHP_EOL;
  exit (1);
}

echo 'auth_token :' . $oCliXM_WS->getAuthToken() . PHP_EOL;

if ( $oCliXM_WS->logout() == true)
{
  echo 'status : ' . $oCliXM_WS->_getLastRequestResult()->Status . PHP_EOL;
}
exit (0);
```
