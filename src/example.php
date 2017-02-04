<?php
//require_once('./Xenmobile_RESTWS_Authentication.php');
namespace enoola_Citrix_Client;
require_once('./class/Xenmobile_RESTWS_Main.php');

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

/*
$arConfig['fqdn']
$arConfig['username']
$arConfig['password']
*/



$oCliXM_WS = new Xenmobile_RESTWS_Main( $arConfig['fqdn'], 4443, 'https',false );

if ( $oCliXM_WS->login($arConfig['username'], $arConfig['password'] ) == false )
{
  echo PHP_EOL.$oCliXM_WS->_getLastRequestResult()->message.'json code:'.$oCliXM_WS->_getLastRequestResult()->status.', ' .$oCliXM_WS->getLastHttpReturnCode().PHP_EOL;
  exit (1);
}

echo 'auth_token :' . $oCliXM_WS->getAuthToken() . PHP_EOL;

echo 'will logout' . PHP_EOL;
if ( $oCliXM_WS->logout() == true)
{
  echo 'status : ' . $oCliXM_WS->_getLastRequestResult()->Status . PHP_EOL;
}

?>
