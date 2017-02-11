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

###Class Xenmobile_RESTWS_Authentication

```php
public function __construct( $szFQDN, $nPort = parent::PORT_DEFAULT_HTTPS, $szProtocol = parent::PROTOCOL_HTTPS, $bVerifySSL = false)
public function login( $szUsername, $szPassword )
public function cwclogin($szContext, $szCustomerId, $szServiceKey)
public function logout()
```

###Class Xenmobile_RESTWS_ServerProperties inherited from Class Xenmobile_RESTWS_Authentication
```php
public function __construct( $szFQDN, $nPort = parent::PORT_DEFAULT_HTTPS, $szProtocol = parent::PROTOCOL_HTTPS, $bVerifySSL = false)
public  __destruct()
public function login( $szUsername, $szPassword )
public function cwclogin($szContext, $szCustomerId, $szServiceKey)
public function logout()
```
