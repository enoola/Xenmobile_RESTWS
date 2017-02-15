<?Php
/**
 * @author John PIGERET <github@mobilutils.com>
 */

namespace enoola_Citrix_Client;

require_once('./class/Xenmobile_RESTWS_Exception.php');
require_once('./class/Xenmobile_RESTWS_Abstract.php');
require_once('./class/IXenmobile_RESTWS_Authentication.php');

/**
 * Use as a parent object to login.
 * can be used alone
 *
 */
class Xenmobile_RESTWS_Authentication extends Xenmobile_RESTWS_Abstract implements IXenmobile_RESTWS_Authentication
{
  const SZ_WS_CLASSNAME = 'authentication';

  private $_szUsername = null;

  /**
   * Constructor to define fqdn ports ...
   *
   * @param string $szFQDN FQDN of the server (xenmobile.contoso.com)
   * @param int $nPort Port to access the server Xenmobile_RESTWS_Abstract::PORT_DEFAULT_HTTPS (4443)
   * @param string $szProtocol https or https Xenmobile_RESTWS_Abstract::PROTOCOL_HTTPS (https)
   * @param boolean $bVerifySSL shall we verify https certificate (false)
   *
   * @return void
   */
  public function __construct( $szFQDN, $nPort = parent::PORT_DEFAULT_HTTPS, $szProtocol = parent::PROTOCOL_HTTPS, $bVerifySSL = false)
  {
    $this->log('in', __METHOD__);
    $this->log(self::SZ_WS_CLASSNAME, __METHOD__);

    parent::log( "classname:".self::SZ_WS_CLASSNAME.', FQDN:'. $szFQDN.', Port:'.$nPort.', Protocol:'.$szProtocol.', verifySSL'.$bVerifySSL);
    parent::__construct( self::SZ_WS_CLASSNAME, $szFQDN, $nPort, $szProtocol, $bVerifySSL);
  }

  public function __destruct()
  {
    $this->log('in', __METHOD__);

    if ($this->getAuthToken() != null)
      $this->logout();
    parent::__destruct();
  }

  /**
   * Login
   *
   * @param string $szUsername
   * @param string $szPassword
   *
   * @return bool true if connected false elsewise
   */
  public function login( $szUsername, $szPassword )
  {
    parent::log('in',__METHOD__);
    parent::log('switch from classname '.parent::_getClassname().' to ' . self::SZ_WS_CLASSNAME, __METHOD__);

    //keeping the child "classname" that needs to be revert back.
    $this->_szUsername = $szUsername;
    $arParams = array('login' => $this->_szUsername, 'password' => $szPassword);

    return ( $this->_login('login', $arParams) );
  }

  /**
   * CWC Login
   *
   * @param string $szContext
   * @param string $szCustomerId
   * @return bool true if connected false otherwise
   */

  public function cwclogin($szContext, $szCustomerId, $szServiceKey)
  {
    parent::log('in',__METHOD__);

    $arHeaders = array('Content-Type: application/json');
    $arHeaders[] = 'Authorization : CWSAuth service='.$szServiceKey ;

    $arParams = array('context'=>$szContext, 'customerId'=>$szCustomerId);


    return ( $this->_login('login', $arParams, $arHeaders) );
  }

  /**
   * Logout
   *
   * @return bool true if disconnected false otherwise
   */

  public function logout()
  {
    parent::log('in',__METHOD__);
    $szCurrentClassName = parent::_getClassName();
    parent::_setClassname( self::SZ_WS_CLASSNAME );

    if ( !is_null($this->_szUsername) && !empty( $this->_szUsername ) && isset( $this->_szUsername ) &&
     !is_null( ($this->getAuthToken() !== null) && !empty( $this->getAuthToken())) )
    {
      $this->log($this->getAuthToken(), __METHOD__.': token ---> ');

      if ( $this->_doRequest('logout', null, array('login'=>$this->_szUsername), 'post') )
      {
        if ( ( (int)$this->getLastHttpReturnCode() == 200) && ($this->getLastRequestResult() !== null ) )
          {
            $this->log(' OK ', __METHOD__);
            $this->_szUsername = null;
            self::_setAuthToken( null );

            return ( true );
          }
      }
      $this->log('Erreur :'.$this->getLastRequestResult()->message.'jsoncode:'.$this->getLastRequestResult()->status.', httpcode:' .$this->getLastHttpReturnCode(), __METHOD__);
    }
    $this->log('Erreur : _doRequest returned false', __METHOD__);

    return ( false );
  }

  /**
   * Protected Login method used by login and loginCWC
   *
   * @param string $szMethod (login, cwclogin)
   * @param array $arParam provide post parameters
   * @param array $arHeaders use to tweek headers
   *
   * @return bool true if connected false elswise
   */
  protected function _login($szMethod, $arParams, $arHeaders = null)
  {
    parent::log('in',__METHOD__);
    parent::log('switch from classname '.parent::_getClassname().' to ' . self::SZ_WS_CLASSNAME, __METHOD__);

    //keeping the child "classname" that needs to be revert back.
    $szCurrentClassName = parent::_getClassname();
    //changing to authentication classname
    parent::_setClassname( self::SZ_WS_CLASSNAME );

    if ( $this->_doRequest($szMethod, null, $arParams, 'post', $arHeaders) )
    {
      if ( (int)$this->getLastHttpReturnCode() == 200 )
        {
          self::_setAuthToken( $this->getLastRequestResult()->auth_token );
          $this->log(' token obtained ', __METHOD__);
          parent::_setClassname( $szCurrentClassName );

          return ( true );
        }
        $this->log($this->getLastRequestResult()->message, $this->getLastRequestResult()->status);
    }
    else if ((int)$this->getLastHttpReturnCode() == 0)
    {
      if ( $this->getLastRequestCurlError() !== null)
      {
        $this->_oRequestLastReturn = new \StdClass;
        $this->_oRequestLastReturn->message = $this->getLastRequestCurlError();
        $this->_oRequestLastReturn->status = 0;
      }
    }
    parent::_setClassname( $szCurrentClassName );
    parent::log('switched back from classname '.parent::_getClassname().' to ' . $szCurrentClassName, __METHOD__);

    return ( false );
  }

  /**
   * Protected Set auth_token different data
   *
   * @param string $szAuthToken : set token obtained when login
   * @return void
   */
  protected function _setAuthToken( $szAuthToken )
  {
    $this->_szAuthToken = $szAuthToken ;
    return ;
  }

  /**
   * Get username used to login
   *
   * @return string username used to login
   */
  public function getUsername()
  {
    return ( $this->_szUsername );
  }

  /**
   * Get authtoken obtained when login
   *
   * @return string username used to login
   */
  public function getAuthToken()
  {
    $this->log('in',__METHOD__);

    return ( $this->_szAuthToken );
  }


  public function getLastHttpReturnCode()
  {
    return ( $this->_arLastHttpReturn['http_code'] );
  }

  public function getLastHttpReturn()
  {
    return ( $this->_arLastHttpReturn );
  }

  public function getLastRequestCurlError()
  {
    return ( $this->_szLastRequestCurlError );
  }

  /**
   * Get the last curl ressult
   *
   * @return mixed null or curl_result
   */
  public function getLastRequestResult()
  {
    return ( $this->_oRequestLastReturn );
  }

  public function getLastJsonRequest()
  {
    return ( $this->_szLastJsonRequest );
  }



}

?>
