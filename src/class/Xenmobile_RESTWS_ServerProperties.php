<?php
namespace enoola_Citrix_Client;

require_once('./class/Xenmobile_RESTWS_Exception.php');
require_once('./class/Xenmobile_RESTWS_Authentication.php');

class Xenmobile_RESTWS_ServerProperties extends Xenmobile_RESTWS_Authentication
{
  const SZ_WS_CLASSNAME = 'serverproperties';
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

  /*
   * Get all current xenmobile server properties,
   *
   * @param array() arQuery ['start'=>0-999, 'limit'=>0-999, "sortOrder":"ASC,DESC,DSC","sortColumn":"ID,SERIAL,IMEI...","enableCount":"false]
   * @return objStdClass() arQuery : {(int)status,(string)message,(objStdClass)currentFilter->(array)detail}
   *
   * cf : https://docs.citrix.com/en-us/xenmobile/10-3/xenmobile-rest-api-reference-main.html
   */
  public function GetAllServerProperties(  )
  {
    $this->log('in', __METHOD__);

    $retValue = $this->_doRequest(null, null, null, 'get');

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
  * Get server property by filter
  *
  * @param array arQuery
      "start": 0,
      "limit": 1000,
      "orderBy": "name",
      "sortOrder": "desc",
      "searchStr": "justaserver1"
  * @return mixed
  */

  public function GetServerPropertiesByFilter( $arQuery )
  {
    $this->log('in', __METHOD__);

    $retValue = $this->_doRequest('filter', null, $arQuery, 'post');

    return ( parent::_handleResponse() );
  }

  /*
  * Get server property by filter
  *
  * @param array arQuery
      "start": 0,
      "limit": 1000,
      "orderBy": "name",
      "sortOrder": "desc",
      "searchStr": "justaserver1"
  * @return mixed
  */

  public function GetServerPropertiesByFilter_Easy( $szSearch, $szOrderBy = 'name', $szSortOrder = 'desc', $nStart = 0, $nLimit = 10 )
  {
    $this->log('in', __METHOD__);
    $arQuery = array ( "start"=> $nStart,
                      "limit"=> $nLimit,
                      "orderBy"=> $szOrderBy,
                      "sortOrder"=> $szSortOrder,
                      "searchStr"=> $szSearch );

    $retValue = $this->_doRequest('filter', null, $arQuery, 'post');

    return ( parent::_handleResponse() );
  }


  /*
   * Add a server property
   *
   * @param array arQuery ('value'=>(string),'name'=>(string),'displayName'=>(string), 'description'=>(string))
   *
   * @return (StdClass|null)
   *
   */
  public function AddServerProperty(  $arQuery )
  {
    $this->log(__METHOD__);

    $retValue = $this->_doRequest(null, null, $arQuery, 'POST');

    return (parent::_handleResponse());
  }

  /*
   * Add a server property
   *
   * @param string szName
   * @param string szValue
   * @param string szDisplayName
   * @param string szDescription
   * @return (StdClass|null)
   *
   */
  public function AddServerProperty_Easy(  $szName,$szValue,$szDisplayName,$szDescription )
  {
    $this->log(__METHOD__);

    $arQuery = array('name'=>$szName,'value'=>$szValue, 'displayName'=>$szDisplayName,'description'=>$szDescription);

    //$retValue = $this->_doRequest(null, null, $arQuery, 'POST');
    $this->AddServerProperty( $arQuery );

    return (parent::_handleResponse());
  }


  /*
   * Edit a server property
   *
   * @param array arQuery  ('value'=>(string),'name'=>(string),'displayName'=>(string), 'description'=>(string))
   *
   * @return (StdClass|null)
   *
   */
  public function EditServerProperty(  $arQuery )
  {
    $this->log(__METHOD__);

    $retValue = $this->_doRequest(null, null, $arQuery, 'PUT');

    return (parent::_handleResponse());
  }

  /*
   * Edit a server property
   *
   * @param string szName
   * @param string szValue
   * @param string szDisplayName
   * @param string szDescription
   *
   * @return (StdClass|null)
   *
   */
  public function EditServerProperty_Easy( $szName, $szValue, $szDisplayName, $szDescription )
  {
    $this->log(__METHOD__);

    $arQuery = array('name'=>$szName,'value'=>$szValue, 'displayName'=>$szDisplayName,'description'=>$szDescription);

    $retValue = $this->_doRequest(null, null, $arQuery, 'PUT');

    return (parent::_handleResponse());
  }

  /*
   * Reset server property
   *
   * @param array arQuery ( array('name1,name2') )
   *
   * @return (StdClass|null)
   * @todo checkout what is wrong with the query
   *
   */
  public function ResetServerProperties(  $arSequentialNamesOfPropertiesToReset )
  {
    $this->log(__METHOD__);

    if (!is_array($arSequentialNamesOfPropertiesToReset) )
      throw new \InvalidArgumentException('invalid argument array expected '.gettype($arSequentialNamesOfPropertiesToReset).' given' );

    $arQuery = array( 'names' => $arSequentialNamesOfPropertiesToReset );
    $retValue = $this->_doRequest('reset', null, $arQuery, 'POST');

    return (parent::_handleResponse());
  }

  /*
   * Delete server properties (return bad request format whatever I try)
   *
   * @param array arSequentialNamesOfPropertiesToReset ( array('name1,name2') )
   *
   * @return (StdClass|null)
   * @todo dig it error message "unrecognized field names"
   *
   */
  public function DeleteServerProperties(  $arSequentialNamesOfPropertiesToDelete )
  {
    $this->log(__METHOD__);
    throw new Xenmobile_RESTWS_Exception( 'Unable to make this one work...' );
    $strQuery = '{ '.PHP_EOL.'"'.implode('" ,'.PHP_EOL.'"', $arSequentialNamesOfPropertiesToDelete).'"'.PHP_EOL.' }';
  //  if (!is_array($arSequentialNamesOfPropertiesToReset) )
  //    throw new \InvalidArgumentException('invalid argument array expected '.gettype($arSequentialNamesOfPropertiesToReset).' given' );

    //$arQuery = array( 'names' => $arSequentialNamesOfPropertiesToReset );
    $retValue = $this->_doRequest(null, null, (string) $strQuery, 'DELETE');

  //  echo '---->'.PHP_EOL;
  //  print_r($this->_szLastJsonRequest);
  //  echo PHP_EOL.'<------'.PHP_EOL;
    return (parent::_handleResponse());
  }





}
?>
