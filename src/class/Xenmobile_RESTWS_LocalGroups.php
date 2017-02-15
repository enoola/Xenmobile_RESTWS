<?Php
namespace enoola_Citrix_Client;
require_once('./class/Xenmobile_RESTWS_Exception.php');
require_once('./class/Xenmobile_RESTWS_Authentication.php');

/**
 * Class LocalGroups leveraging /groups
 *
 */
class Xenmobile_RESTWS_LocalGroups extends Xenmobile_RESTWS_Authentication
{
  const SZ_WS_CLASSNAME = 'groups';

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

  /**
  * Get all Local Groups
  * @return mixed _handleResponse()
  */
  public function GetAllLocalGroups()
  {
    $this->_doRequest( null, null, null, 'GET');

    return ( parent::_handleResponse() );
  }

  /**
  * Search Local Group by Name
  *
  * @note not responding properly in 10.3
  *
  * @param string $szGroupName
  * @param string $szDomain
  * @return mixed _handleResponse()
  */
  public function SearchLocalGroupByName( $szGroupName, $szDomain = 'local' )
  {
    $arGET = array('searchKey' => $szGroupName, 'domain' => $szDomain );

    $this->_doRequest( 'search', null, $arGET, 'GET');

    return ( parent::_handleResponse() );
  }

  /**
  * Add local Group
  *
  * @param string $szName
  * @param string $szDomain
  *
  * @return mixed _handleResponse();
  */
  public function AddLocalGroup( $szName, $szDomain = 'local' )
  {
    $arQuery = array( 'name'=>$szName, 'domainName'=>$szDomain );

    $this->_doRequest( 'local', null, $arQuery, 'POST');

    return ( parent::_handleResponse() );
  }

  /*
  * Add local Group
  *
  * @param string $szName
  * @param string $szDomain
  *
  * @return mixed _handleRespon();
  */
  public function DeleteLocalGroup( $szName, $szDomain = 'local' )
  {
    $this->_doRequest( 'local', $szName, null, 'DELETE');

    return ( parent::_handleResponse() );
  }

}

?>
