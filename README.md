# This is a PhP Client for Xenmobile REST Webservice
###### Bear in mind that you do have descriptions on each folder (through README.md)

- Aim : Provide a PhP client for Citrix Xenmobile Rest Webservices (for version superior or equal to 10.3)
- Means : php mainly maybe other later like ( composer, pear, phpDocumentor, github, community I hope :) )
Constraints :
- provider webservices responses can be disturbing (with a status containing a message without a status..),
  so singularity have been added to classes in order to handle that.

##Pre-requisites
Citrix Xenmobile (version >10.3), if you have equal to 10.3 it will work with most of the functions implemented but not all.
Do not hesitate to reach out if you need help in implementing workaround methods or whatever.
 . https://docs.citrix.com/content/dam/docs/en-us/xenmobile/10-2/Downloads/XenMobile-Public-API.pdf
 . https://docs.citrix.com/en-us/xenmobile/10-3/xenmobile-rest-api-reference-main.html

PhP >= 5.6
 . https://secure.php.net/manual/en/


##Usage Example
```php
/*
 * Consume authentication webservice
 * login, logout
 */
./require_once('./class/Xenmobile_RESTWS_Authentication.php');
$szFQDN = 'xenmobile.contoso.com';

$oCliXM_WS = new Xenmobile_RESTWS_Main( $szFQDN, 4443, 'https',false );

if ( $oCliXM_WS->login('username', 'password' ) == false )
{
  echo PHP_EOL.$oCliXM_WS->_getLastRequestResult()->message.'json code:'.
        $oCliXM_WS->_getLastRequestResult()->status.', ' .
        $oCliXM_WS->getLastHttpReturnCode().
        PHP_EOL;
  exit (1);
}

echo 'auth_token :' . $oCliXM_WS->getAuthToken() . PHP_EOL;

if ( $oCliXM_WS->logout() == true)
{
  echo 'status : ' . $oCliXM_WS->_getLastRequestResult()->Status . PHP_EOL;
}
exit (0);
```
