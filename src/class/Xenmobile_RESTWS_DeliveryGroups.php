<?Php
namespace enoola_Citrix_Client;
require_once('./class/Xenmobile_RESTWS_Exception.php');
require_once('./class/Xenmobile_RESTWS_Authentication.php');

/*
 * This class to manage Delivery Groups
 *
*/
class Xenmobile_RESTWS_DeliveryGroups extends Xenmobile_RESTWS_Authentication
{
  const SZ_WS_CLASSNAME = 'deliverygroups';

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
   * Get Delivery Groups By Filter
   *
   * @param array $arQuery
   *
   * @return mixed see _handleResponse()
   */
  public function GetDeliveryGroupsByFilter( $arQuery )
  {
    $this->log('in', __METHOD__);

    $retValue = $this->_doRequest('filter', null, $arQuery, 'POST');

    return (parent::_handleResponse());
  }

  /*
   * Get Delivery Groups By Filter easter_days
   * Extra Method
   *
   * @param string $szUsername
   * @param integer $nLimit
   *
   * @return mixed see _handleResponse()
   */
  public function GetDeliveryGroupsByFilter_Easy($szSearch, $nLimit = 9 )
  {
    if ( !is_numeric($nLimit) )
      throw new Xenmobile_RESTWS_Exception( 'Second argument nLimit shall be a number',__METHOD__ );

    $arQuery = array('start' => 0, 'sortOrder' => 'DESC','deliveryGroupSortColumn'=>'id','search'=>$szSearch);
    if (!is_null($nLimit) )
      $arQuery['limit'] = $nLimit;

    return ( self::GetDeliveryGroupsByFilter($arQuery) );
  }

  /*
   * Get Delivery Group By Name
   *
   * @param string $szName
   *
   * @return mixed see _handleResponse()
   */
  public function GetDeliveryGroupByName( $szName )
  {
    $this->log('in', __METHOD__);

    $retValue = $this->_doRequest($szName, null, null, 'GET');

    return (parent::_handleResponse());
  }

  /*
   * Edit Delivery Group
   *
   {
       "name": "temp3",
       "description": "temp3 desc",
       "applications": [
   {
                      "name": "TESTAPP",
                       "priority": -1,
                       "required": false
                                   } ],
          "devicePolicies": [
              {
                      "name":"test terms conditions",
                      "priority":-1
               }
          ],
          "smartActions": [
              {
                  "name":"Smart Action Name 1",
                  "priority":-1
               }
          ],
       "groups": [
           {
      "uniqueName": "AC08EP61S75",
               "domainName": "local",
               "name": "AC08EP61S75",
               "objectSid": "AC08EP61S75",
   "uniqueId": "AC08EP61S75",
   "customProperties": {
           "gr1": "gr1",
           "gr2": "gr2"
       }
           }
       ],
       "users": [
           {
               "uniqueName": "testuser",
               "domainName": "local",
               "name": " testuser ",
               "objectId": " testuser "
           }
       ],
       "rules": "{\"AND\":[{\"eq\":{\"property\":{\"type\":\"USER_PROPERTY\",\"name\":\"mail\"},\"type\":\"STRING\",\"value\":\" testuser@citrix.com\"}}]}"
   }

   * @param string $szName
   *
   * @return mixed see _handleResponse()
   */
  public function EditDeliveryGroup   ( $arQuery )
  {
    $this->log('in', __METHOD__);

    $retValue = $this->_doRequest(null, null, $arQuery, 'PUT');

    return (parent::_handleResponse());
  }

  /*
  * Add Delevery Group
  *
  * @param array $arQuery
  *
  * @return mixed see _handleResponse()
  */
 public function AddOneDeliveryGroup   ( $arQuery )
 {
   $this->log('in', __METHOD__);

   $retValue = $this->_doRequest(null, null, $arQuery, 'POST');

   return (parent::_handleResponse());
 }

 /*
 * Delete Delevery Groups
 *
 * @param array $arSequentialIDs
 *
 * @return mixed see _handleResponse()
 */
public function DeleteDeliveryGroups ( $arSequentialIDs )
{
  $this->log('in', __METHOD__);

  $strQuery = '[ "'.implode('" "', $arSequentialIDs).'" ]';

  $retValue = $this->_doRequest(null, null, $strQuery, 'DELETE');

  return (parent::_handleResponse());
}

/*
* Enable/Disable One Delevery Group
* /!\ Not working
*
* @param array $arQuery
*
* @return mixed see _handleResponse()
*/
public function EnableOrDisableOneDeliveryGroup ( $szName, $szAction = 'enable' )
{
 $this->log('in', __METHOD__);

 //$strQuery = '[ "'.implode('" "', $arSequentialIDs).'" ]';
 $this->_doRequest($szName, $szAction, null, 'PUT');

 return ( parent::_handleResponse() );
}

}

?>
