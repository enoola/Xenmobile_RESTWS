<?Php
namespace enoola_Citrix_Client;

require_once('./class/Xenmobile_RESTWS_Exception.php');
require_once('./class/Xenmobile_RESTWS_Authentication.php');

class Xenmobile_RESTWS_Device extends Xenmobile_RESTWS_Authentication
{
  const SZ_WS_CLASSNAME = 'device';
  private $_arImplementedMethod = array();

  public function __construct($szFQDN, $nPort = parent::PORT_DEFAULT_HTTPS, $szProtocol = parent::PROTOCOL_HTTPS, $bVerifySSL = false)
  {
    $this->log('in', __METHOD__);
    $this->log(self::SZ_WS_CLASSNAME, __METHOD__);
    parent::__construct( $szFQDN, $nPort = parent::PORT_DEFAULT_HTTPS, $szProtocol = parent::PROTOCOL_HTTPS, $bVerifySSL = false);
    parent::_setClassname( self::SZ_WS_CLASSNAME );
  }

  public function __destruct()
  {
    $this->log('in', __METHOD__);
    parent::__destruct();
  }

/******** ADD ********/
  /* Begin implementation
   * xenmobile/api/v1/device/filter
   */

  /*
  "start": "0-999",
   "limit": "0-999",
   "sortOrder": "ASC",
   "sortColumn": "ID",
   "search": "Any search term",
   "enableCount": "false",
   "constraints":
  "{'constraintList':[{'constraint':'DEVICE_OS_FAMILY','parameters':[{'name':'osFamily','type':'STRING','value':'iO
  S'}]}]}",
   "filterIds": "['group#/group/MSP@_fn_@normal']"

  */

  /*
  //public function GetDeviceByFilters( $filterIds, $szLimit, $szStart = '0-999', $szSortOrder = 'ASC', $szSortColumn = 'ID',
  //    $szSearch = 'Any search term',$szEnableCount = 'false', $arConstraintList = null,  )
  public function GetDeviceByFiltersArgs( $filterIds, $szSearch = 'Any search term', $szSortOrder = 'ASC', $szSortColumn = 'ID',
     $szStart = '0', $szLimit = '999', $bEnableCount = false, $arConstraintList = null  )
  {
    $arQuery = array();
    if ( !is_null( $szStart ) )
      $arQuery['start'] = $szStart;
    if ( !is_null( $szLimit ) )
      $arQuery['limit'] = $szLimit;
    if ( !is_null( $szSortOrder ) )
      $arQuery['sortOrder'] = $szSortOrder;
    if ( !is_null( $szSortColumn ) )
      $arQuery['sortColumn'] = $szSortColumn;
    if ( $bEnableCount !== null )
    {
      $arQuery['enableCount'] = 'false';
      if ($bEnableCount['enableCount'] === true)
            $arQuery['enableCount'] = 'true';
    }
    if ( !is_null($arConstraintList) )
      $arQuery['constraints'] = array('constraintList'=> $arConstraintList );

    $arQuery['search'] = $szSearch;

    if ($filterIds != null)
      $arQuery['filterIds'] = $filterIds;

    self::GetDeviceByFiltersAr( $arQuery );
  } */

  /*
   * GetDeviceByFilters,
   *
   * @param array() arQuery ['start'=>0-999, 'limit'=>0-999, "sortOrder":"ASC,DESC,DSC","sortColumn":"ID,SERIAL,IMEI...","enableCount":"false]
   * @return objStdClass() arQuery : {(int)status,(string)message,(objStdClass)currentFilter->(array)detail}
   *
   * cf : https://docs.citrix.com/en-us/xenmobile/10-3/xenmobile-rest-api-reference-main.html
   */
  public function GetDeviceByFilters( $arQuery )
  {
    $this->log('in', __METHOD__);

    $retValue = $this->_doRequest('filter', null, $arQuery, 'post');

    if ( $retValue == true )
    {
      if ( $this->getLastRequestResult() && is_object($this->getLastRequestResult()) )
        {
          if ( isset( $this->getLastRequestResult()->status ) )
            {
              return ( $this->getLastRequestResult() );
            }
        }
    }
    elseif ($this->getLastHttpReturn()['http_code'] == 403)
    {
      throw new Xenmobile_RESTWS_Exception( $this->getLastRequestResult() );
    }
    if ($this->getLastRequestResult()->status != 0)
    {
        $this->log( $this->getLastRequestResult(), __METHOD__ );
    }

    throw new Xenmobile_RESTWS_Exception( $this->getLastRequestResult()->message, $this->getLastRequestResult()->status );
  }

  /*
   * GetDeviceByFilters_EasySearch
   *
   * @param string szSearch
   * @param int nLimit
   * @param array arFilterIds
   * @return objStdClass() arQuery : {(int)status,(string)message,(objStdClass)currentFilter->(array)detail}
   */
  public function GetDeviceByFilters_EasySearch($szSearch, $arFilterIds = null, $nLimit = 9)
  {
    if (!is_null($arFilterIds) && !is_array($arFilterIds))
      throw new Xenmobile_RESTWS_Exception( 'Second argument arFilterIds shall be an sequential array',__METHOD__ );
    if ( !is_numeric($nLimit) )
      throw new Xenmobile_RESTWS_Exception( 'Third argument nLimit shall be a number',__METHOD__ );
    $arQuery = array('start' => 0, 'limit' => $nLimit, 'sortOrder' => 'ASC','sortColumn'=>'ID','enableCount'=>'false','search'=>$szSearch);
    if (!is_null($arFilterIds) )
      $arQuery['filterIds'] = $arFilterIds;

    return ( self::GetDeviceByFilters($arQuery) );
  }

  /*
   * GetAvailableFilterIds,
   *
   * @return (array)ar[nodename][]->(string)displayName
   *                              ->(array)arFilters[]->(string)displayName
   *                                                  ->(string)name
   */
  public function GetAvailableFilterIds()
  {
    //we send a "fake query" to ONLY get the content of : currentFiler
    $arCurrentFilters = array();
    $arQuery = array('start' => 0, 'limit' => 0, 'sortOrder' => 'ASC','sortColumn'=>'ID','enableCount'=>'false',
                    'search'=>'__NOT_TO_BE_FOUND__');

    $oReturn = self::GetDeviceByFilters( $arQuery );
    if ( isset ($oReturn->currentFilter) )
    {
      foreach ($oReturn->currentFilter->detail as $oneDetail)
      {
        $arCurrentFilters[ $oneDetail->name ] = new \StdClass;
        $arCurrentFilters[ $oneDetail->name ]->displayName = $oneDetail->displayName;
        $arCurrentFilters[ $oneDetail->name ]->arFilters = array();

        $idx = 0;
        foreach ($oneDetail->nodes as $key => $oneEntry)
        {
          $arCurrentFilters[ $oneDetail->name ]->arFilters[$idx] = new \StdClass;
          $arCurrentFilters[ $oneDetail->name ]->arFilters[$idx]->displayName = $oneEntry->displayName;
          $arCurrentFilters[ $oneDetail->name ]->arFilters[$idx]->name = $oneEntry->name;
          $idx++;
        }
      }
      return ( $arCurrentFilters );
    }
    return ( null );
  }

  /* End implementation
  * xenmobile/api/v1/device/filter
   */

   /* Begin implementation Get Device information by ID
    * xenmobile/api/v1/device/{device_id}
    */
  /*
   * GetDeviceInformationByID,
   *
   * @params deviceID
   * @return (array)ar[nodename][]->(string)displayName
   *                              ->(array)arFilters[]->(string)displayName
   *                                                  ->(string)name
   */
  public function GetDeviceInformationByID($nDeviceID)
  {
    $this->log(__METHOD__);

    $retValue = $this->_doRequest($nDeviceID, null, null, 'get');

    if ($this->getLastHttpReturn()['http_code'] == 403)
    {
      throw new Xenmobile_RESTWS_Exception( $this->getLastRequestResult() );
    }
    else
    {
      if ( $this->getLastRequestResult() && is_object($this->getLastRequestResult()) )
        {
          if ( isset( $this->getLastRequestResult()->status ) )
            {
              return ( $this->getLastRequestResult() );
            }
        }
    }

    if ($this->getLastRequestResult()->status != 0)
    {
        $this->log( $this->getLastRequestResult(), __METHOD__ );
    }

    throw new Xenmobile_RESTWS_Exception( $this->getLastRequestResult()->message, $this->getLastRequestResult()->status );
  }
  /* End implementation Get Device information by ID
   * xenmobile/api/v1/device/{device_id}
  */



  /*
   * Display available filters,
   * show what value are authrorized in search field 'filterIds'
   *
   * @return void
  */
  public function DisplayAvailableFilterIds()
  {

    $arCurrentFilters = self::GetAvailableFilterIds();

    if (!is_null($arCurrentFilters))
    {
      foreach ( $arCurrentFilters as $oneDetailName => $oneArDetail )
      {
        echo 'Node Name : '. $oneArDetail->displayName . PHP_EOL;
        echo 'Available Filter in this Node : '. PHP_EOL;

        foreach ($oneArDetail->arFilters as $key => $oneEntry)
        {
          echo ' '.$oneEntry->displayName . ':' . $oneEntry->name. PHP_EOL;
        }
      }
    }
    return (null) ;
  }

  /*
   * Get device apps by device ID,
   *
   * @param int[0-999] nDevice
   *
   * @return StdClassname application[]->(string)name, status,...
   */
   public function GetAppsByDeviceID($nDeviceID)
   {
     return ( $this->_getInformationByDeviceID('apps', $nDeviceID) );
   }

   /*
    * Get device actions by device ID,
    *
    * @param int[0-999] nDevice
    *
    * @return StdClassname actions[]->(string)ressourceType, ressourceTypeLabel,...
    */
    public function GetActionsByDeviceID($nDeviceID)
    {
      return ( $this->_getInformationByDeviceID('actions', $nDeviceID) );
    }

    /*
     * Get device delivery groups by device ID,
     *
     * @param int[0-999] nDevice
     *
     * @return StdClassname deliveryGroups[]->(string)statuslabel, linkey, status,...
     */
     public function GetDeliveryGroupsByDeviceID($nDeviceID)
     {
       return ( $this->_getInformationByDeviceID('deliverygroups', $nDeviceID) );
     }


    /*
     * Get device Managed Software Inventory by device ID,
     *
     * @param int[0-999] nDevice
     *
     * @return StdClassname softwareInventory[]->(string)version, blacklistCompliant, suggestedListCompliant,...
     */
     public function GetManagedSoftwareInventoryByDeviceID($nDeviceID)
     {
       return ( $this->_getInformationByDeviceID('managedswinventory', $nDeviceID) );
     }


    /*
     * Get device Policies by Device ID,
     *
     * @param int[0-999] nDevice
     *
     * @return StdClassname policies[]->(string)version, ressourceType, ressourceTypeLabel,...
     */
     public function GetPoliciesByDeviceID($nDeviceID)
     {
       return ( $this->_getInformationByDeviceID('policies', $nDeviceID) );
     }


     /*
      * Get device Software Inventory by device ID,
      *
      * @param int[0-999] nDevice
      *
      * @return StdClassname softwareInventory[]->(string)version, blacklistCompliant, suggestedListCompliant,...
      */
      public function GetSoftwareInventoryByDeviceID($nDeviceID)
      {
        return ( $this->_getInformationByDeviceID('softwareinventory', $nDeviceID) );
      }

    /*
     * Get device Software Inventory by device ID,
     *
     * @param int[0-999] nDevice
     *
     * @return StdClassname softwareInventory[]->(string)version, blacklistCompliant, suggestedListCompliant,...
     */
     public function GetGPSCoordinateByDeviceID($nDeviceID)
     {
       return ( $this->_getInformationByDeviceID($nDeviceID, 'locations') );
     }


   /*
    * _getAvailableFilterIds,
    * made to ease implementation of different methods Get***ByDeviceID()
    *
    * @param string szMethod
    * @param string szPath
    * @return (StdClass|null)
    *
    */
   //private function _getInformationByDeviceID( $szSeekedInformation, $nDeviceID )
   private function _getInformationByDeviceID(  $szPath, $szMethod )
   {
     $this->log(__METHOD__);

     if ( is_string( $szMethod ) )
      {
        if (!is_numeric($szPath))
           throw new Xenmobile_RESTWS_Exception( 'argument $szPath must be numeri if $szMethod is not string. value given :'.$szPath  );
      }
      if ( is_numeric( $szMethod ) )
       {
         if (!is_string($szPath))
           throw new Xenmobile_RESTWS_Exception( 'argument $szPath must be string if $szMethod is numeric. value given :'.$szPath  );
       }

     $retValue = $this->_doRequest($szMethod, $szPath, null, 'get');

     return ($this->_handleResponse());
   }

/******** ADD ********/

  /*
  * Send a notification to a list of devices or users
  *
  * @param
  */

  /*
  {
    "smtpFrom": "Test",
    "to": [
      {
        "deviceId": "1",
        "email": "user@test.com",
        "osFamily": "iOS",
        "serialNumber": "F7NLX6WDF196",
        "smsTo": "+123456676",
        "token": {
          "type": "apns",
          "value": "dfb2fb351a4fb068e40858ecad572e317e6c39b4fa7de6fb29ea1ad7e2254499"
        }
      }
    ],
    "smtpSubject": "This is test subject",
    "smtpMessage": "This is test message",
    "smsMessage": "This is test message",
    "agentMessage": "This is test message",
    "sendAsBCC": "true",
    "smtp": "true",
    "sms": "true",
    "agent": "true",
    "templateId": "-1",
    "agentCustomProps": {
      "sound": "Casino.wav"
    }
  */
  public function SendNotificationToAListOfDevicesOrUsers($arQuery)
  {
    $this->log(__METHOD__);


    $retValue = $this->_doRequest('notify', null, $arQuery, 'post');

    return ($this->_handleResponse());
  }



  /*
  * Send a Mail notification to a list of users mail
  *
  * Extra function
  * @param string szFrom : From content
  * @param array arMailRecipient
  * @param string szSubject
  * @param string szBody
  * @param bool bSendAsBCC : send the email as bcc to expeditor
  *
  */
  public function SendMailToAListOfUserMail( $szFrom, $arMailRecipient, $szSubject, $szBody, $bSendAsBCC )
  {
    $this->log(__METHOD__);
    if ( !is_array( $arMailRecipient ) )
           throw new Xenmobile_RESTWS_Exception( 'Bad argument' );

    $arMailTo = array();
    foreach ($arMailRecipient as $oneMail)
    {
      $arMailTo[] = array('email' => $oneMail);
    }
    $arQueryNotification = array ( 'smtpFrom' => $szFrom,
      'to'          =>  $arMailTo ,
      'smtpSubject' => $szSubject,
      'smtpMessage' => $szBody,
      'sendAsBCC'   => $bSendAsBCC,
      'smtp'        => 'true',
      'sms'         => 'false',
      'agent'       => 'false',
      'templateId'  => -1);

    $retValue = $this->_doRequest('notify', null, $arQueryNotification, 'POST');

    return ($this->_handleResponse());
  }

  /*
  * Send a SMS notification ausers (Simplified method)
  * Query are weird if I only put a device ID it won't send it
  * I need to put the number too
  *
  * Extra function
  * @param array arNumberToSMS
  * @param string szMessage
  */
  public function SendSMSToAListOfPhoneNumbers( $arNumberToSMS, $szMessage )
  {
    $this->log(__METHOD__);
    if ( !is_array( $arNumberToSMS ) )
           throw new Xenmobile_RESTWS_Exception( 'Bad argument' );

    $arSMSTo = array();
    foreach ($arNumberToSMS as $oneNumber)
    {
      $arSMSTo[] = array('smsTo' => $oneNumber);
    }
    $arQueryNotification = array (
      'to'          =>  $arSMSTo ,
      'smsMessage'  => $szMessage,
      'smtp'        => 'false',
      'sms'         => 'true',
      'agent'       => 'false',
      'templateId'  => -1);

    $retValue = $this->_doRequest('notify', null, $arQueryNotification, 'POST');

    return ($this->_handleResponse());
  }


  /*
  * Shall send a notification to a list of device
  * I don't know how to implement it YET
  *
  */
  private function SendNotificationToAListOfDevice( $nDeviceID, $szMessage )
  {
    $this->log(__METHOD__);
    $arQueryNotification = array (
      'to'          => array ( array(
                              'deviceId' => $nDeviceID ,
                              'osFamily'      => 'Android', //==> GET BUGGY When using this (for mail and SMS)
                              'token' => array ('type'=>'shtp')
                        ) ),
      'smtp'        => 'false',
      'sms'         => 'false',
      'agent'       => 'true',
      'templateId'  => -1);

    $retValue = $this->_doRequest('notify', null, $arQueryNotification, 'POST');

    return ($this->_handleResponse());
  }

  /*
  * Authorize a list of devices
  *
  * Extra function
  * @param array arSequentialID
  * @return mixed
  */
  public function AuthorizeAListOfDevices( $arSequentialID )
  {
    $this->log(__METHOD__);

    $this->_throwExceptionIfNotArray( $arSequentialID );
    $this->_doRequest('authorize', null, $arSequentialID, 'POST');

    return ( $this->_handleResponse());
  }

  /*
  * Apply Activation Lock Bypass on a List of Devices
  *
  * @param array arSequentialID
  * @return mixed
  */
  public function ApplyActivationLockBypassOnAListOfDevices( $arSequentialID )
  {
    $this->log(__METHOD__);

    $this->_throwExceptionIfNotArray( $arSequentialID );
    $this->_doRequest('activationLockBypass', null, $arSequentialID, 'POST');

    return ( $this->_handleResponse());
  }

  /*
  * Apply App Lock on a List of Devices
  *
  * @param array arSequentialID
  * @return mixed
  */
  public function ApplyAppLockOnAListOfDevices( $arSequentialID )
  {
    $this->log(__METHOD__);

    $this->_throwExceptionIfNotArray( $arSequentialID );
    $this->_doRequest('appLock', null, $arSequentialID, 'POST');

    return ( $this->_handleResponse());
  }

  /*
  * Apply App Wipe on a List of Devices
  *
  * @param array arSequentialID
  * @return mixed
  */
  public function ApplyAppWipeOnAListOfDevices( $arSequentialID )
  {
    $this->log(__METHOD__);

    $this->_throwExceptionIfNotArray( $arSequentialID );
    $this->_doRequest('appWipe', null, $arSequentialID, 'POST');

    return ( $this->_handleResponse());
  }

  /*
  * Apply Container Lock on a List of Devices
  *
  * @param array arSequentialID
  * @return mixed
  */
  public function ApplyContainerLockOnAListOfDevices( $arSequentialID )
  {
    $this->log(__METHOD__);

    $this->_throwExceptionIfNotArray( $arSequentialID );
    $this->_doRequest('containerLock', null, $arSequentialID, 'POST');

    return ( $this->_handleResponse());
  }

  /*
  * Cancel Container Lock on a List of Devices
  *
  * @param array arSequentialID
  * @return mixed
  */
  public function CancelContainerLockOnAListOfDevices( $arSequentialID )
  {
    $this->log(__METHOD__);

    $this->_throwExceptionIfNotArray( $arSequentialID );
    $this->_doRequest('containerLock', 'cancel', $arSequentialID, 'POST');

    return ( $this->_handleResponse());
  }

  /*
  * Apply Container Unlock on a List of Devices
  *
  * @param array arSequentialID
  * @return mixed
  * @note documentation states a newPinCode is required but I am not SendNotificationToAListOfDevicesOrUsers_Agent
  * @todo : need to be tested
  */
  public function ApplyContainerUnlockOnAListOfDevices( $arSequentialID )
  {
    $this->log(__METHOD__);

    $this->_throwExceptionIfNotArray( $arSequentialID );
    $this->_doRequest('containerUnlock', null, $arSequentialID, 'POST');

    return ( $this->_handleResponse());
  }

  /*
  * Cancel Container Unlock on a List of Devices
  *
  * @param array arSequentialID
  * @return mixed
  */
  public function CancelContainerUnlockOnAListOfDevices( $arSequentialID )
  {
    $this->log(__METHOD__);

    $this->_throwExceptionIfNotArray( $arSequentialID );
    $this->_doRequest('containerUnlock', 'cancel', $arSequentialID, 'POST');

    return ( $this->_handleResponse());
  }


  /*
  * Reset Container Password on a List of Devices
  *
  * @param array arSequentialID
  * @return mixed
  * @note documentation states a newPinCode is required but I am not SendNotificationToAListOfDevicesOrUsers_Agent
  * @todo : need to be tested furthermore
  */
  public function ResetContainerPasswordOnAListOfDevices( $arSequentialID )
  {
    $this->log(__METHOD__);

    $this->_throwExceptionIfNotArray( $arSequentialID );
    $this->_doRequest('containerPwdReset', null, $arSequentialID, 'POST');

    return ( $this->_handleResponse());
  }

  /*
  * Cancel Reset Container Password on a List of Devices
  *
  * @param array arSequentialID
  * @return mixed
  * @note documentation states a newPinCode is required but I am not SendNotificationToAListOfDevicesOrUsers_Agent
  * @todo : need to be tested furthermore
  */
  public function CancelResetContainerPasswordOnAListOfDevices( $arSequentialID )
  {
    $this->log(__METHOD__);

    $this->_throwExceptionIfNotArray( $arSequentialID );
    $this->_doRequest('containerPwdReset', 'cancel', $arSequentialID, 'POST');

    return ( $this->_handleResponse());
  }

  /*
  * Disown a List of Devices
  *
  * @param array arSequentialID
  * @return mixed
  * @note documentation states a newPinCode is required but I am not SendNotificationToAListOfDevicesOrUsers_Agent
  * @todo : need to be tested furthermore
  */
  public function DisownAListOfDevices( $arSequentialID )
  {
    $this->log(__METHOD__);

    $this->_throwExceptionIfNotArray( $arSequentialID );
    $this->_doRequest('disown', null, $arSequentialID, 'POST');

    return ( $this->_handleResponse());
  }

  /*
  * Locate a List of Devices
  *
  * @param array arSequentialID
  * @return mixed
  * @note documentation states a newPinCode is required but I am not SendNotificationToAListOfDevicesOrUsers_Agent
  * @todo : need to be tested furthermore
  */
  public function LocateAListOfDevices( $arSequentialID )
  {
    $this->log(__METHOD__);

    $this->_throwExceptionIfNotArray( $arSequentialID );
    $this->_doRequest('locate', null, $arSequentialID, 'POST');

    return ( $this->_handleResponse());
  }

  /*
  * Cancel Locate a List of Devices
  *
  * @param array arSequentialID
  * @return mixed
  */
  public function CancelLocateAListOfDevices( $arSequentialID )
  {
    $this->log(__METHOD__);

    $this->_throwExceptionIfNotArray( $arSequentialID );
    $this->_doRequest('locate', 'cancel', $arSequentialID, 'POST');

    return ( $this->_handleResponse());
  }

  /*
  * Apply GPS Tracking on a List of Devices
  *
  * @param array arSequentialID
  * @return mixed
  */
  public function ApplyGPSTrackingOnAListOfDevices( $arSequentialID )
  {
    $this->log(__METHOD__);

    $this->_throwExceptionIfNotArray( $arSequentialID );
    $this->_doRequest('track', null, $arSequentialID, 'POST');

    return ( $this->_handleResponse());
  }

  /*
  * Cancel GPS Tracking on a List of Devices
  *
  * @param array arSequentialID
  * @return mixed
  */
  public function CancelGPSTrackingOnAListOfDevices( $arSequentialID )
  {
    $this->log(__METHOD__);

    $this->_throwExceptionIfNotArray( $arSequentialID );
    $this->_doRequest('track', 'cancel', $arSequentialID, 'POST');

    return ( $this->_handleResponse());
  }

  /*
  * Lock a List of Devices
  *
  * @param array arSequentialID
  * @return mixed
  */
  public function LockAListOfDevices( $arSequentialID )
  {
    $this->log(__METHOD__);

    $this->_throwExceptionIfNotArray( $arSequentialID );
    $this->_doRequest('lock', null, $arSequentialID, 'POST');

    return ( $this->_handleResponse());
  }

  /*
  * Cancel Lock a List of Devices
  *
  * @param array arSequentialID
  * @return mixed
  */
  public function CancelLockAListOfDevices( $arSequentialID )
  {
    $this->log(__METHOD__);

    $this->_throwExceptionIfNotArray( $arSequentialID );
    $this->_doRequest('lock', 'cancel', $arSequentialID, 'POST');

    return ( $this->_handleResponse());
  }

  /*
  * Unlock a List of Devices
  *
  * @param array arSequentialID
  * @return mixed
  */
  public function UnlockAListOfDevices( $arSequentialID )
  {
    $this->log(__METHOD__);

    $this->_throwExceptionIfNotArray( $arSequentialID );
    $this->_doRequest('unlock', null, $arSequentialID, 'POST');

    return ( $this->_handleResponse());
  }

  /*
  * Cancel Unlock a List of Devices
  *
  * @param array arSequentialID
  * @return mixed
  */
  public function CancelUnlockAListOfDevices( $arSequentialID )
  {
    $this->log(__METHOD__);

    $this->_throwExceptionIfNotArray( $arSequentialID );
    $this->_doRequest('unlock', 'cancel', $arSequentialID, 'POST');

    return ( $this->_handleResponse());
  }

  /*
  * Deploy a List of Devices
  *
  * @param array arSequentialID
  * @return mixed
  */
  public function DeployAListOfDevices( $arSequentialID )
  {
    $this->log(__METHOD__);

    $this->_throwExceptionIfNotArray( $arSequentialID );
    $this->_doRequest('refresh', null, $arSequentialID, 'POST');

    return ( $this->_handleResponse());
  }


  /*
  * Request for AirPlay Mirroring on a List of Devices
  *
  * @param array arSequentialID
  * @return mixed
  * @notes : from citrix doc we need those in the query
        dstName – destination name, as either destination name or destination device ID
        dstDevId – MAC address for destination device, as either destination name or destination device ID
        scanTime – number of seconds to scan
        screenSharingPwd – password for screen sharing
  * need to be tested
  */
  public function RequestForAirPlayMirroringOnAListOfDevices( $arSequentialID )
  {
    $this->log(__METHOD__);

    $this->_throwExceptionIfNotArray( $arSequentialID );
    $this->_doRequest('requestMirroring', null, $arSequentialID, 'POST');

    return ( $this->_handleResponse());
  }

  /*
  * Cancel Request for AirPlay Mirroring on a List of Devices
  *
  * @param array arSequentialID
  * @return mixed
  * @todo : need to be tested
  */
  public function CancelRequestForAirPlayMirroringOnAListOfDevices( $arSequentialID )
  {
    $this->log(__METHOD__);

    $this->_throwExceptionIfNotArray( $arSequentialID );
    $this->_doRequest('requestMirroring', 'cancel', $arSequentialID, 'POST');

    return ( $this->_handleResponse());
  }

  /*
  * Stop AirPlay Mirroring on a List of Devices
  *
  * @param array arSequentialID
  * @return mixed
  */
  public function StopAirPlayMirroringOnAListOfDevices( $arSequentialID )
  {
    $this->log(__METHOD__);

    $this->_throwExceptionIfNotArray( $arSequentialID );
    $this->_doRequest('stopMirroring', null, $arSequentialID, 'POST');

    return ( $this->_handleResponse());
  }

  /*
  * Cancel Stop AirPlay Mirroring on a List of Devices
  *
  * @param array arSequentialID
  * @return mixed
  */
  public function CancelStopAirPlayMirroringOnAListOfDevices( $arSequentialID )
  {
    $this->log(__METHOD__);

    $this->_throwExceptionIfNotArray( $arSequentialID );
    $this->_doRequest('stopMirroring', 'cancel', $arSequentialID, 'POST');

    return ( $this->_handleResponse());
  }

  /*
  * Clear All Restrictions on a List of Devices
  *
  * @param array arSequentialID
  * @return mixed
  */
  public function ClearAllRestrictionsOnAListOfDevices( $arSequentialID )
  {
    $this->log(__METHOD__);

    $this->_throwExceptionIfNotArray( $arSequentialID );
    $this->_doRequest('restrictions', 'clear', $arSequentialID, 'POST');

    return ( $this->_handleResponse());
  }

  /*
  * Cancel Clear All Restrictions on a List of Devices
  *
  * @param array arSequentialID
  * @return mixed
  */
  public function CancelClearAllRestrictionsOnAListOfDevices( $arSequentialID )
  {
    $this->log(__METHOD__);

    $this->_throwExceptionIfNotArray( $arSequentialID );
    $this->_doRequest('restrictions', 'clear/cancel', $arSequentialID, 'POST');

    return ( $this->_handleResponse());
  }

  /*
  * Revoke a List of Devices
  *
  * @param array arSequentialID
  * @return mixed
  */
  public function RevokeAListOfDevices( $arSequentialID )
  {
    $this->log(__METHOD__);

    $this->_throwExceptionIfNotArray( $arSequentialID );
    $this->_doRequest('revoke', null, $arSequentialID, 'POST');

    return ( $this->_handleResponse());
  }

  /*
  * Ring a List of Devices
  *
  * @param array arSequentialID
  * @return mixed
  */
  public function RingAListOfDevices( $arSequentialID )
  {
    $this->log(__METHOD__);

    $this->_throwExceptionIfNotArray( $arSequentialID );
    $this->_doRequest('ring', null, $arSequentialID, 'POST');

    return ( $this->_handleResponse());
  }

  /*
  * Cancel Ring a List of Devices
  *
  * @param array arSequentialID
  * @return mixed
  */
  public function CancelRingAListOfDevices( $arSequentialID )
  {
    $this->log(__METHOD__);

    $this->_throwExceptionIfNotArray( $arSequentialID );
    $this->_doRequest('ring', 'cancel', $arSequentialID, 'POST');

    return ( $this->_handleResponse());
  }

  /*
  * Wipe a List of Devices
  *
  * @param array arSequentialID
  * @return mixed
  */
  public function WipeAListOfDevices( $arSequentialID )
  {
    $this->log(__METHOD__);

    $this->_throwExceptionIfNotArray( $arSequentialID );
    $this->_doRequest('wipe', null, $arSequentialID, 'POST');

    return ( $this->_handleResponse());
  }

  /*
  * Cancel Wipe a List of Devices
  *
  * @param array arSequentialID
  * @return mixed
  */
  public function CancelWipeAListOfDevices( $arSequentialID )
  {
    $this->log(__METHOD__);

    $this->_throwExceptionIfNotArray( $arSequentialID );
    $this->_doRequest('wipe', 'cancel', $arSequentialID, 'POST');

    return ( $this->_handleResponse());
  }



  /*
  * Throw an exception if parameter is not an array
  *
  * @param array arSequentialID
  * @return mixed
  */
  protected function _throwExceptionIfNotArray( $arSequentialID )
  {
    if (!is_array( $arSequentialID ) )
    {
      throw Xenmobile_RESTWS_Exception('Invalid argument expected array.');
    }
  }


    /*
    * handle response of Xenmobile
    *
    * @return mixed : return _oRequestLastReturn
    *                         Xenmobile_RESTWS_Exception if an error occurs
    */
   private function _handleResponse( )
   {
     if ($this->getLastHttpReturn()['http_code'] == 403)
     {
       throw new Xenmobile_RESTWS_Exception( $this->getLastRequestResult() );
     }
     else
     {
       if ( $this->getLastRequestResult() && is_object($this->getLastRequestResult()) )
         {
           if ( isset( $this->getLastRequestResult()->status ) )
             {
               return ( $this->getLastRequestResult() );
             }
         }
     }

     if ($this->getLastRequestResult()->status != 0)
     {
         $this->log( $this->getLastRequestResult(), __METHOD__ );
     }

     throw new Xenmobile_RESTWS_Exception( $this->getLastRequestResult()->message, $this->getLastRequestResult()->status );
   }




/*Others*

[x] Send Notification to a List of Devices or Users
[x] Authorize a List of Devices
[x] Apply Activation Lock Bypass on a List of Devices
[x] Apply App Lock on a List of Devices
[x] Apply App Wipe on a List of Devices
[x] Apply Container Lock on a List of Devices
[x] Cancel Container Lock on a List of Devices
[x] Apply Container Unlock on a List of Devices
[x] Cancel Container Unlock on a List of Devices
[x] Reset Container Password on a List of Devices
[x] Cancel Reset Container Password on a List of Devices
[x] Disown a List of Devices
[x] Locate a List of Devices
[x] Cancel Locate a List of Devices
[x] Apply GPS Tracking on a List of Devices
[x] Cancel GPS Tracking on a List of Devices
[x] Lock a List of Devices
[x] Cancel Lock of a List of Devices
[x] Unlock a List of Devices
[x] Cancel Unlock of a List of Devices
[x] Deploy a List of Devices
[x] Request AirPlay Mirroring on a List of Devices
[x] Cancel Request for AirPlay Mirroring on a List of Devices
[x] Stop AirPlay Mirroring on a List of Devices
[x] Cancel Stop AirPlay Mirroring on a List of Devcies
[x] Clear All Restrictions on a List of Devices
[x] Cancel Clear All Restrictions on a List of Devices
[x] Revoke a List of Devices
[x] Ring a List of Devices
[x] Cancel Ringing a List of Devices
[x] Wipe a List of Devices
[x] Cancel Wipe of a List of Devices
[ ] Selectively Wipe a List of Devices
[ ] Cancel Selectively Wiping a List of Devices
[ ] Wipe the SD Cards on a List of Devices
[ ] Cancel Wiping SD Cards on a List of Devices
[ ] Get All Known Properties on a Device
[ ] Get All Used Properties on a Device
[ ] Get All Device Properties by Device ID
[ ] Update All Device Properties by Device ID
[ ] Add or Update a Device Property by Device ID
[ ] Delete a Device Property by Device ID
[ ] Get iOS Device MDM Status by Device ID
[ ] Generate PIN code
*/

}




?>
