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



}




?>
