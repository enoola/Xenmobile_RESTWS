<?Php
namespace enoola_Citrix_Client;

require_once('./class/Xenmobile_RESTWS_Exception.php');
require_once('./class/Xenmobile_RESTWS_Abstract.php');

/**
 * use as an intermediate object to login.
 * can be use alone
 */
class Xenmobile_RESTWS_Main extends Xenmobile_RESTWS_Abstract
{
  const SZ_WS_CLASSNAME = 'authentication';

  private $_szUsername = null;
    //const CLASSNAME =
    function __construct( $szFQDN, $nPort = parent::PORT_DEFAULT_HTTPS, $szProtocol = parent::PROTOCOL_HTTPS, $bVerifySSL = false)
    {
      $this->log('in', __METHOD__);
      //parent::log( __CLASS__, "Construct");
      parent::log( "classname:".self::SZ_WS_CLASSNAME.', FQDN:'. $szFQDN.', Port:'.$nPort.', Protocol:'.$szProtocol.', verifySSL'.$bVerifySSL);
      parent::__construct( self::SZ_WS_CLASSNAME, $szFQDN, $nPort, $szProtocol, $bVerifySSL);
    }

    function __destruct()
    {
      $this->log('in', __METHOD__);
      if ($this->getAuthToken() != null)
        $this->logout();
      parent::__destruct();
    }

    protected function _getImplementedClassnames()
    {
      $this->log('in', __METHOD__);
      $arClassname = array('certificates');

      return( $arClassname );
      //, 'licenses', 'ldap', 'netscaler', 'notificationserver', 'deliverygroups', 'user', 'localusergroups', 'groups', 'serverproperties','application')
    }

    /**
     * Login
     *
     * @param string $szUsername
     * @param string $szPassword
     * @return bool true if connected false elswise
     */
    public function login( $szUsername, $szPassword )
    {
      parent::log('in',__METHOD__);

      $this->_szUsername = $szUsername;
      $arParams = array('login' => $this->_szUsername, 'password' => $szPassword);
      if ( $this->_doRequest('login', null, $arParams, 'post') )
      {
        if ( (int)self::getLastHttpReturnCode() == 200 )
          {
            parent::_setAuthToken( $this->_getLastRequestResult()->auth_token );
            $this->log(' OK ', __METHOD__);

            return ( true );
          }
          print_r(parent::_getLastHttpReturn());
          $this->log($this->_getLastRequestResult()->message, $this->_getLastRequestResult()->status);
      }
      else if ((int)self::getLastHttpReturnCode() == 0)
      {
        if ( parent::_getLastRequestCurlError() !== null)
        {
          $this->_oRequestLastReturn = new \StdClass;
          $this->_oRequestLastReturn->message = parent::_getLastRequestCurlError();
          $this->_oRequestLastReturn->status = 0;
        }
      }

      return ( false );
    }

    public function cwclogin($szContext, $szCustomerId, $szServiceKey)
    {
      parent::log('in',__METHOD__);
      $arHeaders = array('Content-Type: application/json');
      $arHeaders[] = 'Authorization : CWSAuth service='.$szServiceKey ;

      $arParams = array('context'=>$szContext, 'customerId'=>$szCustomerId);

      if ( $this->_doRequest('cwclogin', null, $arParams, 'post', $arHeaders) )
      {
        if ( (int)self::getLastHttpReturnCode() == 200 )
          {
            parent::_setAuthToken( $this->_getLastRequestResult()->auth_token );
            $this->log(' OK ', __METHOD__);

            return ( true );
          }
      }

      return (true);
    }


    public function logout()
    {
      parent::log('in',__METHOD__);

      if ( !is_null($this->_szUsername) && !empty( $this->_szUsername ) && isset( $this->_szUsername ) &&
       !is_null( (parent::_getAuthToken() !== null) && !empty( parent::_getAuthToken())) )
      {
        $this->log($this->_getAuthToken(), __METHOD__.': token');
        if ( $this->_doRequest('logout', null, array('login'=>$this->_szUsername), 'post') )
        {
          if ( ( (int)self::getLastHttpReturnCode() == 200) && ( !is_null($this->_getLastRequestResult() ) ) )
            {
              $this->log(' OK ', __METHOD__);
              $this->_szUsername = null;
              parent::_setAuthToken( null );

              return ( true );
            }
        }
        $this->log('Erreur :'.$this->_getLastRequestResult()->message.'jsoncode:'.$this->_getLastRequestResult()->status.', httpcode:' .$this->getLastHttpReturnCode(), __METHOD__);
      }
      $this->log('Erreur : _doRequest returned false', __METHOD__);

      return ( false );
    }

    public function getLastHttpReturnCode()
    {
      return ( parent::_getLastHttpReturn()['http_code'] );
    }

    function getAuthToken()
    {
      $this->log('in',__METHOD__);

      return ( parent::_getAuthToken() );
    }

}

?>
