<?Php
namespace enoola_Citrix_Client;
require_once('./class/Xenmobile_RESTWS_Exception.php');
require_once('./class/Xenmobile_RESTWS_LocalUsersGroups.php');

/*
*
* Sample to use Xenmobile_RESTWS_Device
*
*/

$szPathConfigFile = 'config_file.ini';
if (!file_exists( $szPathConfigFile ) || is_link( $szPathConfigFile ) )
{
  die ('Config file not found : ' . $szPathConfigFile . PHP_EOL);
}

$arConfig = parse_ini_file( $szPathConfigFile );
if ($arConfig === false)
{
  die ('Error reading config file :' . $szPathConfigFile . PHP_EOL);
}

if ( (!array_key_exists('fqdn', $arConfig) ) || (!array_key_exists('username', $arConfig) ) || (!array_key_exists('password', $arConfig) ) )
  die ('Missing one or more mandatory field (fqdn, username, password).'.PHP_EOL);

$oCliXM_WS = new Xenmobile_RESTWS_LocalUsersGroups( $arConfig['fqdn'], 4443, 'https',false );

if ( $oCliXM_WS->login($arConfig['username'], $arConfig['password'] ) == false )
{
  echo PHP_EOL.$oCliXM_WS->getLastRequestResult()->message.'json code:'.$oCliXM_WS->getLastRequestResult()->status.', ' .$oCliXM_WS->getLastHttpReturnCode().PHP_EOL;
  exit (1);
}
echo 'auth_token :' . $oCliXM_WS->getAuthToken() . PHP_EOL;

//$oCliXM_WS->DisplayAvailableFilterIds();

print_r( $oCliXM_WS->GetAllLocalUsers());

//print_r( $oCliXM_WS->GetOneLocalUser('user2_wsadd'));

/*
{
      "attributes": {
              "badpwdcount": "4",
              "asuseremail": "justa.name@example.com",
              "company": "example",
              "mobile": "4695557854"
              },
      "groups": [
              "MSP"
              ],
      "role": "USER",
      "username": "justaname_XX",
      "password": "password"
}
*/
//$arQueryUser = array('attributes'=>array('company' => 'mobilutils','mobile' => '+3346762733'),
//                      'groups'=>array(),'role'=>'USER','username'=>'user4_wsadd','password'=>'passwd');
//$arQueryUser = array( 'attributes'=>new \StdClass,
//                      'role'=>'USER','username'=>'user1_wsadd','password'=>'passwd');

//print_r( $oCliXM_WS->AddOneLocalUser($arQueryUser) );

//print_r( $oCliXM_WS->AddOneLocalUser_Easy('user2_wsadd','password','USER',null));
//print_r( $oCliXM_WS->getLastJsonRequest());

//$arQueryUser = array( 'username'=>'user2_wsadd', 'password'=>'','role'=>'USER','groups'=>array(),'attributes'=>new \StdClass);
//print_r( $oCliXM_WS->UpdateOneLocalUser($arQueryUser));

//print_r( $oCliXM_WS->UpdateOneLocalUser_Easy('user2_wsadd','newspassword'));

//!!
//print_r( $oCliXM_WS->DeleteUsers(array('user2_wsadd')));

//print_r( $oCliXM_WS->DeleteOneUser('user2_wsadd'));

//print_r( $oCliXM_WS->getLastJsonRequest());



//var_dump( $oCliXM_WS->getLastRequestCurlError());

echo 'will logout' . PHP_EOL;
if ( $oCliXM_WS->logout() == true)
{
  echo 'status : ' . $oCliXM_WS->getLastRequestResult()->Status . PHP_EOL;
}

?>
