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
  public function GetAll(  )
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


  public function GetByFilter( $arQuery )
  {
    $this->log('in', __METHOD__);

    $retValue = $this->_doRequest(null, null, $arQuery, 'post');

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






}
?>
