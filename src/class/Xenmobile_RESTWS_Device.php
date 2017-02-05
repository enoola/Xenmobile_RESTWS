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
  }

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
    elseif ($this->_getLastHttpReturn()['http_code'] == 403)
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

  public function GetDeviceInformationByID($nDeviceID)
  {
    $this->log(__METHOD__);

    $retValue = $this->_doRequest($nDeviceID, null, null, 'get');

    if ($this->_getLastHttpReturn()['http_code'] == 403)
    {
      throw new Xenmobile_RESTWS_Exception( $this->getLastRequestResult() );
    }
    else
    {
      echo '$retValue == true'.PHP_EOL;
      if ( $this->getLastRequestResult() && is_object($this->getLastRequestResult()) )
        {
          echo 'getLastRequestResult()';
          print_r($this->getLastRequestResult());
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



  /* Begin implementation Get Device applications by device ID
   * xenmobile/api/v1/device/{device_id}
  */

  /* End implementation
   * xenmobile/api/v1/device/filter
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
          //echo '  Human Readable : '. $oneEntry->displayName. PHP_EOL;
          //echo '  Programatic    : '. $oneEntry->name. PHP_EOL;
          echo ' '.$oneEntry->displayName . ':' . $oneEntry->name. PHP_EOL;
        }
      }
    }
    return ;
  }

}

?>
