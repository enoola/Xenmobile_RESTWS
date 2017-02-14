<?Php
namespace enoola_Citrix_Client;
require_once('./class/Xenmobile_RESTWS_Exception.php');
require_once('./class/Xenmobile_RESTWS_ServerProperties.php');

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

$oCliXM_WS = new Xenmobile_RESTWS_ServerProperties( $arConfig['fqdn'], 4443, 'https',false );

if ( $oCliXM_WS->login($arConfig['username'], $arConfig['password'] ) == false )
{
  echo PHP_EOL.$oCliXM_WS->getLastRequestResult()->message.'json code:'.$oCliXM_WS->getLastRequestResult()->status.', ' .$oCliXM_WS->getLastHttpReturnCode().PHP_EOL;
  exit (1);
}
echo 'result :' .PHP_EOL;

/*
* We display ldap properties
*/

//print_r( $oCliXM_WS->GetServerPropertiesByFilter_Easy('ldap') );
//print_r( $oCliXM_WS->GetAllServerProperties() );
echo sprintf('------------------------------------------------------'."\n");
echo sprintf("%'. 3s | %-30.30s | %-5.5s | %-5.5s |\n", 'ID', 'Display Name', 'Name', 'Value');
echo sprintf('------------------------------------------------------'."\n");
$arResult = $oCliXM_WS->GetServerPropertiesByFilter_Easy('ldap')->allEwProperties;

foreach( $arResult as $key => $oValue )
{
  echo sprintf("%'. 3d | %-30.30s | %-5.5s | %-5.5s |\n", $oValue->id, $oValue->displayName, $oValue->name, $oValue->value);
  echo sprintf('----+--------------------------------+-------+-------|'."\n");
}
//print_r($oCliXM_WS->AddServerProperty_Easy('myserverprop','myvalueof','My Server Prop','My description'));

//print_r( $oCliXM_WS->ResetServerProperties(array('myserverprop')) );


echo 'will logout' . PHP_EOL;
if ( $oCliXM_WS->logout() == true)
{
  echo 'status : ' . $oCliXM_WS->getLastRequestResult()->Status . PHP_EOL;
}

?>
