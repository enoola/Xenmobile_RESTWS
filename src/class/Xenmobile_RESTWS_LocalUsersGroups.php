<?php
namespace enoola_Citrix_Client;

require_once('./class/Xenmobile_RESTWS_Exception.php');
require_once('./class/Xenmobile_RESTWS_Authentication.php');

error_reporting(E_ALL);



class Xenmobile_RESTWS_LocalUsersGroups extends Xenmobile_RESTWS_Authentication
{
  const SZ_WS_CLASSNAME = 'localusersgroups';
  protected $_arMethodMatrix = array();

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
    if ($this->getAuthToken() != null)
      $this->logout();
    parent::__destruct();
  }

  /*
  * Get all local users
  *
  * @return mixed see _handleResponse
  */
  public function GetAllLocalUsers()
  {
    $this->log(__METHOD__);

    $retValue = $this->_doRequest(null, null, null, 'GET');

    return (parent::_handleResponse());
  }

  /*
  * Get one local users
  *
  * @param string szName
  *
  * @return mixed see _handleResponse
  */
  public function GetOneLocalUser( $szName )
  {
    $this->log(__METHOD__);

    $retValue = $this->_doRequest( $szName, null, null, 'GET');

    return ($this->_handleResponse());
  }


  /*
  * Add one local user
  *
  * @param array arQuery
  *
  * @return mixed see _handleResponser
  */
  public function AddOneLocalUser( $arQuery )
  {
    $this->log(__METHOD__);

    $this->_doRequest(null, null, $arQuery, 'POST');

    return ($this->_handleResponse());
  }

  /*
  * Add one local user easily
  * Extra function to easily leverage AddOneLocalUser
  *
  * @param string szUsername
  * @param string szPassword
  * @param string szRole
  * @param array arGroups
  * @param array arAttributes 'attributes'=>array('badpwdcount','asuseremail','company','mobile')
  *
  * @return mixed see _handleResponser
  *
  */
  public function AddOneLocalUser_Easy( $szUsername, $szPassword, $szRole = 'USER', $arGroups = array(), $arAttributes = null )
  {
    $this->log(__METHOD__);

    $arQuery = array('username' => $szUsername, 'password' => $szPassword);
    if (!is_null($szRole))
      $arQuery['role']= $szRole;
    if (!is_null($arGroups) && (is_array($arGroups) && (count($arGroups) > 0)  ) )
      $arQuery['groups']= $arGroups;
    if (!is_null($arAttributes) && (is_array($arAttributes) && (count($arAttributes) > 0)  ) )
      $arQuery['attributes'] = $arAttributes;
    else {
      $arQuery['attributes'] = new \StdClass;
    }

    $this->AddOneLocalUser( $arQuery );

    return ($this->_handleResponse());
  }

  /*
  * Update one local user
  *
  * @param array arQuery
  *
  * @return mixed see _handleResponser
  */
  public function UpdateOneLocalUser( $arQuery )
  {
    $this->log(__METHOD__);

    $this->_doRequest(null, null, $arQuery, 'PUT');

    return ($this->_handleResponse());
  }

  /*
  * Update one local user easily
  * Extra function to easily leverage UpdateOneLocalUser
  * BY DESIGN YOU NEED TO PUT BACK ALL PROPERTIES YOU WANT TO KEEP SO...

  * You Must define every value, if you do not want to change a value put null it won't be sent for modification.
  * @param string szUsername
  * @param string szPassword Mandatory null or empty string to not modify it
  * @param string szRole Mandatory need to match user main role :/
  * @param array arGroups Optionnal
  * @param array arAttributes Mandatory 'attributes'=>array('badpwdcount','asuseremail','company','mobile')
  *
  * @return mixed see _handleResponser
  *
  */
  public function UpdateOneLocalUser_Easy( $szUsername, $szPassword = null, $szRole = null, $arGroups = null, $arAttributes = null )
  {
    $this->log(__METHOD__);

    if (is_null($szPassword))
      $szPassword = '';

    $arQuery = array('username' => $szUsername, 'password' => $szPassword);
    if (!is_null($szRole))
      $arQuery['role']= $szRole;
    else {
      // Xenmobile will complain ... use other extra fonction to play around
      //2159 error occured while updating local user

      //shall I help with exceptions ?
    }
    if (!is_null($arGroups) && (is_array($arGroups)  ) )
      $arQuery['groups']= $arGroups;
    if (!is_null($arAttributes) && (is_array($arAttributes) ) )
      $arQuery['attributes'] = $arAttributes;
    else {
      // Xenmobile will complain ... use other extra fonction to play around
      //2159 error occured while updating local user
    }

    $this->UpdateOneLocalUser( $arQuery );

    return ($this->_handleResponse());
  }

  /*
  * Update one local user
  *
  * @param array szName
  * @param array szNewPassword
  *
  * @return mixed see _handleResponser
  */
  public function ChangeUserPassword( $szUsername, $szNewPassword )
  {
    $this->log(__METHOD__);

    $arQuery = array('username' => $szUsername, 'password' => $szNewPassword );
    $this->_doRequest('resetpassword', null, $arQuery, 'PUT');

    return ($this->_handleResponse());
  }

  /*
  * Delete Users
  * DOES NOT WORK get a user not foudn
  *
  * @param array arUsernames
  *
  * @return mixed see _handleResponser
  */
  public function DeleteUsers( $arUsernames )
  {
    $this->log(__METHOD__);

    $strQuery = '{ "'.implode('" "', $arUsernames).'" }';

    $this->_doRequest('resetpassword', null, $strQuery, 'DELETE');

    return ($this->_handleResponse());
  }

  /*
  * Delete One User
  * DOES NOT WORK get a user not foudn
  *
  * @param array arUsernames
  *
  * @return mixed see _handleResponser
  */
  public function DeleteOneUser( $szOneUser )
  {
    $this->log(__METHOD__);

    //$strQuery = '{ "'.$szOneUser.'" }';
    $this->_doRequest($szOneUser,null, null, 'DELETE');

    return ($this->_handleResponse());
  }

  /*
  * Import provisioning file
  *
  * @param string szCSVFilePath
  *
  * @return mixed see _handleResponser
  */
  public function ImportProvisioningFile( $szCSVFilePath )
  {
    $this->log(__METHOD__);

    $arQuery = array('importdata'=>array('fileType'=>'user'), 'uploadfile'=>$szCSVFilePath);

    $this->_doRequest('importprovisioningfile', null, $arQuery, 'POST');

    return ($this->_handleResponse());
  }

}
?>
