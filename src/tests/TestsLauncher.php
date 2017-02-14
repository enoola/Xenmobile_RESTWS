<?php
require_once('./extlibs/simpletest/autorun.php');
//require_once('tests/Xenmobile_RESTWS_Authentication.php');

$test = new TestSuite('Xenmobile_RESTWS_Classes');
$test->addFile('tests/Xenmobile_RESTWS_AuthenticationTest.php');
$test->addFile('tests/Xenmobile_RESTWS_DeviceTest.php');
$test->addFile('tests/Xenmobile_RESTWS_DeliveryGroupsTest.php');
$test->addFile('tests/Xenmobile_RESTWS_ServerPropertiesTest.php');
$test->addFile('tests/Xenmobile_RESTWS_LocalUsersTest.php');
$test->addFile('tests/Xenmobile_RESTWS_LocalGroupsTest.php');
//$test->run( new TextReporter() );
?>
