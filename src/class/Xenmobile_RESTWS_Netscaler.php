<?Php
namespace enoola_Citrix_Client;
require_once('./class/Xenmobile_RESTWS_Exception.php');
require_once('./class/Xenmobile_RESTWS_Authentication.php');

class Xenmobile_RESTWS_Netscaler extends Xenmobile_RESTWS_Authentication
{
  const SZ_WS_CLASSNAME = 'netscaler';

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
  public function __construct($szFQDN, $nPort = parent::PORT_DEFAULT_HTTPS, $szProtocol = parent::PROTOCOL_HTTPS, $bVerifySSL = false)
  {
    $this->log('in', __METHOD__);
    parent::__construct( $szFQDN, $nPort = parent::PORT_DEFAULT_HTTPS, $szProtocol = parent::PROTOCOL_HTTPS, $bVerifySSL = false);
    parent::_setClassname( self::SZ_WS_CLASSNAME );
  }

  public function __destruct()
  {
    $this->log('in', __METHOD__);
    parent::__destruct();
  }

  /*
   * listConfigurations
   *
   * @return array $adList
   */
  public function listConfigurations()
  {
    $this->log('in', __METHOD__);
    $retValue = $this->_doRequest(null, null, null, 'get');

    return (parent::_handleResponse());
  }

}

?>
