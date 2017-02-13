#Below a list of implemented methods

##Implemented Class/Method

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
        - GetAllServerProperties()
        - GetServerPropertiesByFilter(ar_filter)
        - AddServerProperty(ar_query)
        - AddServerProperty_Easy(name,value,displayname,description)
        - EditServerProperty(ar_names)
        - EditServerProperty_Easy(name,value,displayname,description)
        - DeleteServerProperties(ar_names)
        - ResetServerProperties(ar_names)

      - Xenmobile_RESTWS_LocalUsersGroups : /localusersgroups
        - GetAllLocalUsers
        - GetOneLocalUser
        - AddOneLocalUser
        - AddOneLocalUser_Easy
        - UpdateOneLocalUser
        - UpdateOneLocalUser_Easy
        - ChangeUserPassword
        - DeleteOneUser
        - ImportProvisioningFile
      - Xenmobile_RESTWS_Netscaler : /netscaler
        - listConfigurations


##Different view of implemented Class/Method
### Public and useful usage methods only are exposed here

####Class Xenmobile_RESTWS_Authentication

```php
  public function __construct( $szFQDN, $nPort = parent::PORT_DEFAULT_HTTPS, $szProtocol = parent::PROTOCOL_HTTPS, $bVerifySSL = false)
  public function login( $szUsername, $szPassword )
  public function cwclogin($szContext, $szCustomerId, $szServiceKey)
  public function logout()
```

####Class Xenmobile_RESTWS_ServerProperties inherited from Class Xenmobile_RESTWS_Authentication
```php
  public function __construct($szFQDN, $nPort = parent::PORT_DEFAULT_HTTPS, $szProtocol = parent::PROTOCOL_HTTPS, $bVerifySSL = false)
  public function __destruct()
  public function GetAllServerProperties(  )
  public function GetServerPropertiesByFilter( $arQuery )
  public function GetServerPropertiesByFilter_Easy( $szSearch, $szOrderBy = 'name', $szSortOrder = 'desc', $nStart = 0, $nLimit = 10 )
  public function AddServerProperty(  $arQuery )
  public function AddServerProperty_Easy(  $szName,$szValue,$szDisplayName,$szDescription )
  public function EditServerProperty(  $arQuery )
  public function EditServerProperty_Easy( $szName, $szValue, $szDisplayName, $szDescription )
  public function ResetServerProperties(  $arSequentialNamesOfPropertiesToReset )
  public function DeleteServerProperties(  $arSequentialNamesOfPropertiesToDelete )
```


####Class Xenmobile_RESTWS_Device inherited from Class Xenmobile_RESTWS_Authentication
```php
  public function __construct($szFQDN, $nPort = parent::PORT_DEFAULT_HTTPS, $szProtocol = parent::PROTOCOL_HTTPS, $bVerifySSL = false)
  public function __destruct()
  public function GetDeviceByFilters( $arQuery )
  public function GetDeviceByFilters_EasySearch( $szSearch, $arFilterIds = null, $nLimit = 9 )
  public function GetAvailableFilterIds()
  public function GetDeviceInformationByID( $nDeviceID )
  public function DisplayAvailableFilterIds()
  public function GetAppsByDeviceID( $nDeviceID )
  public function GetActionsByDeviceID( $nDeviceID )
  public function GetDeliveryGroupsByDeviceID( $nDeviceID )
  public function GetManagedSoftwareInventoryByDeviceID( $nDeviceID )
  public function GetPoliciesByDeviceID( $nDeviceID )
  public function GetSoftwareInventoryByDeviceID( $nDeviceID )
  public function GetGPSCoordinateByDeviceID( $nDeviceID )
  public function SendNotificationToAListOfDevicesOrUsers( $arQuery )
  public function SendMailToAListOfUserMail( $szFrom, $arMailRecipient, $szSubject, $szBody, $bSendAsBCC )
  public function SendSMSToAListOfPhoneNumbers( $arNumberToSMS, $szMessage )
  public function SendPushNotificationToALisOfDevice( $nDeviceID, $szToken, $szMessage, $szDeviceType=Android)
  public function GetAllKnownPropertiesOnADevice( $nID )
  public function GetAllUsedPropertiesOnADevice( $nID )
  public function GetAllDevicePropertiesByDeviceID( $nDeviceID )
  public function GetiOSDeviceMDMStatusbyDeviceID( $nDeviceID )
  public function GeneratePinCode( $nLength )
  public function GetDeviceLastLocationByDeviceID( $nDeviceID )
  //can't get the below method to work on xm 10.3x
  public function UpdateAllDevicePropertiesByDeviceID( $nDeviceID, $arAllProperties )
  public function AddOrUpdateADevicePropertyByDeviceID( $nDeviceID, $arOneProperty )
  public function DeleteADevicePropertyByDeviceID( $nDeviceID, $arOneProperty )

```


#####Good news there are virtual methods
```php
Xenmobile_RESTWS_Device::AuthorizeAListOfDevices( $arSequentialDeviceIDs ); //'authorize' );
Xenmobile_RESTWS_Device::ApplyActivationLockBypassOnAListOfDevices( $arSequentialDeviceIDs ); //'activationLockBypass' );
Xenmobile_RESTWS_Device::ApplyAppLockOnAListOfDevices( $arSequentialDeviceIDs ); //'appLock' );
Xenmobile_RESTWS_Device::ApplyAppWipeOnAListOfDevices( $arSequentialDeviceIDs ); //'appWipe' );
Xenmobile_RESTWS_Device::ApplyContainerLockOnAListOfDevices( $arSequentialDeviceIDs ); //'containerLock' );
Xenmobile_RESTWS_Device::CancelContainerLockOnAListOfDevices( $arSequentialDeviceIDs ); //'containerLock(); //'cancel' );
Xenmobile_RESTWS_Device::ApplyContainerUnlockOnAListOfDevices( $arSequentialDeviceIDs ); //'containerUnlock' );
Xenmobile_RESTWS_Device::CancelContainerUnlockOnAListOfDevices( $arSequentialDeviceIDs ); //'containerUnlock(); //'cancel' );
Xenmobile_RESTWS_Device::ResetContainerPasswordOnAListOfDevices( $arSequentialDeviceIDs ); //'containerPwdReset' );
Xenmobile_RESTWS_Device::CancelResetContainerPasswordOnAListOfDevices( $arSequentialDeviceIDs ); //'containerPwdReset(); //'cancel' );
Xenmobile_RESTWS_Device::DisownAListOfDevices( $arSequentialDeviceIDs ); //'disown(); //'cancel' );
Xenmobile_RESTWS_Device::LocateAListOfDevices( $arSequentialDeviceIDs ); //'locate' );
Xenmobile_RESTWS_Device::CancelLocateAListOfDevices( $arSequentialDeviceIDs ); //'locate(); //'cancel' );
Xenmobile_RESTWS_Device::ApplyGPSTrackingOnAListOfDevices( $arSequentialDeviceIDs ); //'track' );
Xenmobile_RESTWS_Device::CancelGPSTrackingOnAListOfDevices( $arSequentialDeviceIDs ); //'track(); //'cancel');
Xenmobile_RESTWS_Device::LockAListOfDevices( $arSequentialDeviceIDs ); //'lock' );
Xenmobile_RESTWS_Device::CancelLockAListOfDevices( $arSequentialDeviceIDs ); //'lock(); //'cancel' );
Xenmobile_RESTWS_Device::LockAListOfDevices( $arSequentialDeviceIDs ); //'unlock' );
Xenmobile_RESTWS_Device::DeployAListOfDevices( $arSequentialDeviceIDs ); //'refresh' );
Xenmobile_RESTWS_Device::RequestForAirPlayMirroringOnAListOfDevices( $arSequentialDeviceIDs ); //'requestMirroring' );
Xenmobile_RESTWS_Device::CancelRequestForAirPlayMirroringOnAListOfDevices( $arSequentialDeviceIDs ); //'requestMirroring(); //'cancel' );
Xenmobile_RESTWS_Device::StopAirPlayMirroringOnAListOfDevices( $arSequentialDeviceIDs ); //'stopMirroring' );
Xenmobile_RESTWS_Device::CancelStopAirPlayMirroringOnAListOfDevices( $arSequentialDeviceIDs ); //'stopMirroring(); //'cancel' );
Xenmobile_RESTWS_Device::ClearAllRestrictionsOnAListOfDevices( $arSequentialDeviceIDs ); //'restrictions(); //'clear' );
Xenmobile_RESTWS_Device::CancelClearAllRestrictionsOnAListOfDevices( $arSequentialDeviceIDs ); //'restrictions(); //'clear/cancel' );
Xenmobile_RESTWS_Device::RevokeAListOfDevices( $arSequentialDeviceIDs ); //'revoke' );
Xenmobile_RESTWS_Device::RingAListOfDevices( $arSequentialDeviceIDs ); //'ring' );
Xenmobile_RESTWS_Device::CancelRingAListOfDevices( $arSequentialDeviceIDs ); //'ring(); //'cancel' );
Xenmobile_RESTWS_Device::WipeAListOfDevices( $arSequentialDeviceIDs ); //'wipe' );
Xenmobile_RESTWS_Device::CancelWipeAListOfDevices( $arSequentialDeviceIDs ); //'wipe(); //'cancel' );
Xenmobile_RESTWS_Device::SelectivelyWipeAListOfDevices( $arSequentialDeviceIDs ); //'selwipe' );
Xenmobile_RESTWS_Device::CancelSelectivelyWipeAListOfDevices( $arSequentialDeviceIDs ); //'selwipe(); //'cancel' );
Xenmobile_RESTWS_Device::WipeTheSDCardsOnAListOfDevices( $arSequentialDeviceIDs ); //'sdcardwipe' );
Xenmobile_RESTWS_Device::CancelWipeTheSDCardsOnAListOfDevices( $arSequentialDeviceIDs ); //'sdcardwipe', 'cancel' );
```

####Class Xenmobile_RESTWS_LocalUsersGroups inherited from Class Xenmobile_RESTWS_Authentication
```php
  public function __construct($szFQDN, $nPort = parent::PORT_DEFAULT_HTTPS, $szProtocol = parent::PROTOCOL_HTTPS, $bVerifySSL = false)
  public function __destruct()
  public function GetAllLocalUsers()
  public function GetOneLocalUser( $szName )
  public function AddOneLocalUser( $arQuery )
  //Extra function to easily leverage AddOneLocalUser
  public function AddOneLocalUser_Easy( $szUsername, $szPassword, $szRole = 'USER', $arGroups = array(), $arAttributes = null )
  public function UpdateOneLocalUser( $arQuery )
  //Extra function to easily leverage UpdateOneLocalUser
  public function UpdateOneLocalUser_Easy( $szUsername, $szPassword = null, $szRole = null, $arGroups = null, $arAttributes = null )
  public function ChangeUserPassword( $szUsername, $szNewPassword )
  private function DeleteUsers( $arUsernames )
  public function DeleteOneUser( $szOneUser )
  public function ImportProvisioningFile( $szCSVFilePath )
```

####Class Xenmobile_RESTWS_Netscaler inherited from Class Xenmobile_RESTWS_Authentication
```php
  public function __construct($szFQDN, $nPort = parent::PORT_DEFAULT_HTTPS, $szProtocol = parent::PROTOCOL_HTTPS, $bVerifySSL = false)
  public function __destruct()
  public function listConfigurations()
```
