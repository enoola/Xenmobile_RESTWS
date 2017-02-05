<?Php
namespace enoola_Citrix_Client;

require_once('./class/Xenmobile_RESTWS_Exception.php');
require_once('./class/Xenmobile_RESTWS_Abstract.php');
require_once('./class/IXenmobile_RESTWS_Authentication.php');

/**
 * use as an intermediate object to login.
 * can be use alone
 */
class Xenmobile_RESTWS_Authentication extends Xenmobile_RESTWS_Abstract implements IXenmobile_RESTWS_Authentication
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
      var_dump($this->getAuthToken());
      if ($this->getAuthToken() != null)
        $this->logout();
      parent::__destruct();
    }

/*
    protected function _getImplementedClassnames()
    {
      $this->log('in', __METHOD__);
      $arClassname = array('certificates');

      return( $arClassname );
      //, 'licenses', 'ldap', 'netscaler', 'notificationserver', 'deliverygroups', 'user', 'localusergroups', 'groups', 'serverproperties','application')
    }
    */

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

      $szCurrentClassName = parent::_getClassName();
      parent::_setClassname( self::SZ_WS_CLASSNAME );
      $this->_szUsername = $szUsername;
      $arParams = array('login' => $this->_szUsername, 'password' => $szPassword);
      if ( $this->_doRequest('login', null, $arParams, 'post') )
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
      return ( false );
    }

    public function cwclogin($szContext, $szCustomerId, $szServiceKey)
    {
      parent::log('in',__METHOD__);
      $szCurrentClassName = parent::_getClassName();
      parent::_setClassname( self::SZ_WS_CLASSNAME );

      $arHeaders = array('Content-Type: application/json');
      $arHeaders[] = 'Authorization : CWSAuth service='.$szServiceKey ;

      $arParams = array('context'=>$szContext, 'customerId'=>$szCustomerId);

      if ( $this->_doRequest('cwclogin', null, $arParams, 'post', $arHeaders) )
      {
        if ( (int)self::getLastHttpReturnCode() == 200 )
          {
            parent::_setAuthToken( $this->getLastRequestResult()->auth_token );
            $this->log(' OK ', __METHOD__);

            parent::_setClassname( $szCurrentClassName );
            return ( true );
          }
      }
      print_r($this); //function to be verified
      exit;

      parent::_setClassname( $szCurrentClassName );
      return (true);
    }


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
     * set auth_token different data
     *
     * @param string $szAuthToken : set token obtained when login
     * @return void
     */
    protected function _setAuthToken( $szAuthToken )
    {
      $this->_szAuthToken = $szAuthToken ;
      return ;
    }

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
