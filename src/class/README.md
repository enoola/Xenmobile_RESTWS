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
  public function GetAllKnownPropertiesOnADevice( $nID )
  public function GetAllUsedPropertiesOnADevice( $nID )
  public function GetAllDevicePropertiesByDeviceID( $nDeviceID )
  public function UpdateAllDevicePropertiesByDeviceID( $nDeviceID, $arAllProperties )
  public function AddOrUpdateADevicePropertyByDeviceID( $nDeviceID, $arOneProperty )
  public function DeleteADevicePropertyByDeviceID( $nDeviceID, $arOneProperty )
  public function GetiOSDeviceMDMStatusbyDeviceID( $nDeviceID )
  public function GeneratePinCode( $nLength )
  public function GetDeviceLastLocationByDeviceID( $nDeviceID )
  public function __call( $szName, $arAguments )
```
#####Good news there are virtual methods
```php
Xenmobile_RESTWS_Device::AuthorizeAListOfDevices(); //'authorize' );
Xenmobile_RESTWS_Device::ApplyActivationLockBypassOnAListOfDevices(); //'activationLockBypass' );
Xenmobile_RESTWS_Device::ApplyAppLockOnAListOfDevices(); //'appLock' );
Xenmobile_RESTWS_Device::ApplyAppWipeOnAListOfDevices(); //'appWipe' );
Xenmobile_RESTWS_Device::ApplyContainerLockOnAListOfDevices(); //'containerLock' );
Xenmobile_RESTWS_Device::CancelContainerLockOnAListOfDevices(); //'containerLock(); //'cancel' );
Xenmobile_RESTWS_Device::ApplyContainerUnlockOnAListOfDevices(); //'containerUnlock' );
Xenmobile_RESTWS_Device::CancelContainerUnlockOnAListOfDevices(); //'containerUnlock(); //'cancel' );
Xenmobile_RESTWS_Device::ResetContainerPasswordOnAListOfDevices(); //'containerPwdReset' );
Xenmobile_RESTWS_Device::CancelResetContainerPasswordOnAListOfDevices(); //'containerPwdReset(); //'cancel' );
Xenmobile_RESTWS_Device::DisownAListOfDevices(); //'disown(); //'cancel' );
Xenmobile_RESTWS_Device::LocateAListOfDevices(); //'locate' );
Xenmobile_RESTWS_Device::CancelLocateAListOfDevices(); //'locate(); //'cancel' );
Xenmobile_RESTWS_Device::ApplyGPSTrackingOnAListOfDevices(); //'track' );
Xenmobile_RESTWS_Device::CancelGPSTrackingOnAListOfDevices(); //'track(); //'cancel');
Xenmobile_RESTWS_Device::LockAListOfDevices(); //'lock' );
Xenmobile_RESTWS_Device::CancelLockAListOfDevices(); //'lock(); //'cancel' );
Xenmobile_RESTWS_Device::LockAListOfDevices(); //'unlock' );
Xenmobile_RESTWS_Device::DeployAListOfDevices(); //'refresh' );
Xenmobile_RESTWS_Device::RequestForAirPlayMirroringOnAListOfDevices(); //'requestMirroring' );
Xenmobile_RESTWS_Device::CancelRequestForAirPlayMirroringOnAListOfDevices(); //'requestMirroring(); //'cancel' );
Xenmobile_RESTWS_Device::StopAirPlayMirroringOnAListOfDevices(); //'stopMirroring' );
Xenmobile_RESTWS_Device::CancelStopAirPlayMirroringOnAListOfDevices(); //'stopMirroring(); //'cancel' );
Xenmobile_RESTWS_Device::ClearAllRestrictionsOnAListOfDevices(); //'restrictions(); //'clear' );
Xenmobile_RESTWS_Device::CancelClearAllRestrictionsOnAListOfDevices(); //'restrictions(); //'clear/cancel' );
Xenmobile_RESTWS_Device::RevokeAListOfDevices(); //'revoke' );
Xenmobile_RESTWS_Device::RingAListOfDevices(); //'ring' );
Xenmobile_RESTWS_Device::CancelRingAListOfDevices(); //'ring(); //'cancel' );
Xenmobile_RESTWS_Device::WipeAListOfDevices(); //'wipe' );
Xenmobile_RESTWS_Device::CancelWipeAListOfDevices(); //'wipe(); //'cancel' );
Xenmobile_RESTWS_Device::SelectivelyWipeAListOfDevices(); //'selwipe' );
Xenmobile_RESTWS_Device::CancelSelectivelyWipeAListOfDevices(); //'selwipe(); //'cancel' );
Xenmobile_RESTWS_Device::WipeTheSDCardsOnAListOfDevices(); //'sdcardwipe' );
Xenmobile_RESTWS_Device::CancelWipeTheSDCardsOnAListOfDevices(); //'sdcardwipe', 'cancel' );
```
