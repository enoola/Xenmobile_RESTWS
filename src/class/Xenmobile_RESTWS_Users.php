<?Php
namespace enoola_Citrix_Client;
require_once('./class/Xenmobile_RESTWS_Exception.php');
require_once('./class/Xenmobile_RESTWS_Authentication.php');

/*
 * Users seems to be available only since xm 10.4
 * moreover it seems to target AD users only
 */
class Xenmobile_RESTWS_Users extends Xenmobile_RESTWS_Authentication
{
  const SZ_WS_CLASSNAME = 'users';

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
   * Add or update User Property,
   *
   * @param string $szUsername
   * @param string $szPropertyName
   * @param string $szPropertieValue
   *
   * @return mixed see _handleResponse()
   */
  public function AddOrUpdateUserProperty($szUsername, $szPropertyName, $szPropertieValue )
  {
    $this->log('in', __METHOD__);

    $arQuery = array('name'=>$szPropertyName, 'value'=>$szPropertieValue);
    $retValue = $this->_doRequest($szUsername, 'property', $arQuery, 'POST');

    return (parent::_handleResponse());
  }

  /*
  * Assign user to Local Groups
  *
  * @param string $szUsername
  * @param string $szPropertyName
  *
  * @return mixed see _handleResponse()
  */
  public function AssignUserToLocalGroups($szUsername, $arSequentialGroups)
  {
    $this->log('in', __METHOD__);

    $retValue = $this->_doRequest($szUsername, 'localgroups', $arSequentialGroups, 'POST');

    return (parent::_handleResponse());

  }


}

?>
