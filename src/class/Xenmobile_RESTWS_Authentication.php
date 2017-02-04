<?Php
namespace enoola_Citrix_Client;

require_once('./class/Xenmobile_RESTWS_Exception.php');
require_once('./class/Xenmobile_RESTWS_Abstract.php');

abstract class Xenmobile_RESTWS_Authentication extends Xenmobile_RESTWS_Abstract
{
  //const SZ_WS_CLASSNAME = '';
  private $_szUsername = null;
  protected $_oMainApi = null;
    //const CLASSNAME =
    function __construct( $szClassName, $szFQDN, $nPort = parent::PORT_DEFAULT_HTTPS, $szProtocol = parent::PROTOCOL_HTTPS, $bVerifySSL = false)
    {
      //echo "( ".self::_WS_CLASSNAME.", $szFQDN, $nPort, $szProtocol, $bVerifySSL);".PHP_EOL;
      parent::log(__METHOD__);
      parent::log( "classname:".$szClassName.', FQDN:'. $szFQDN.', Port:'.$nPort.', Protocol:'.$szProtocol.', verifySSL'.$bVerifySSL);

      parent::__construct( $szClassName, $szFQDN, $nPort, $szProtocol, $bVerifySSL);
      $this->_oMainApi = new Xenmobile_RESTWS_Main($szFQDN, $nPort, $szProtocol, $bVerifySSL);
    }

    function __destruct()
    {
      parent::log('in',__METHOD__);
      parent::__destruct();
      $this->log('end', __METHOD__);
    }

    function login($szUsername, $szPassword)
    {
      parent::log('in',__METHOD__);

      $this->_szUsername = $szUsername;
      if ( $this->_oMainApi->login($szUsername, $szPassword) == true )
      {
        $this->_setAuthToken($this->_oMainApi->getAuthToken());
        return ( true );
      }

      return ( false );
    }

    function cwclogin($szContext, $szCustomerId, $szServiceKey)
    {
      parent::log('into', __METHOD__);

      return ( $this->_oMainApi->cwclogin($szContext, $szCustomerId, $szServiceKey) );
    }

    function logout()
    {
      parent::log('into', __METHOD__);

      return ( $this->_oMainApi->logout($this->_szUsername) );
    }

    function getAuthtoken()
    {
      parent::log('into', __METHOD__);
      return ( $this->_oMainApi->_getAuthtoken() );
    }

  /*
    protected function _doRequest($szMethod, $szPath, $arParams = array(), $httpMethod = 'get', $arNewHeaders = null )
    {
      parent::_doRequest($szMethod, $szPath, $arParams, $httpMethod, $arNewHeaders);
    }
  *
   protected function GetImplementedMethods()
   {
     //not implemented
     $this->_arImplementedMethod = array();
     $this->_arImplementedMethod[0] = new \StdClass;
     $this->_arImplementedMethod[0]->classname = 'authentication';
     $this->_arImplementedMethod[0]->arMethods = array();
     $this->_arImplementedMethod[0]->arMethods[] = array('login', '/login');
     $this->_arImplementedMethod[0]->arMethods[] = array('logout', '/logout');
     $this->_arImplementedMethod[0]->arMethods[] = array('cwclogin', '/cwclogin');
   }
   */
}

?>
