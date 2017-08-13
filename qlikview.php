<?php
/**
 * Created by PhpStorm.
 * User: bs
 * Date: 04/08/2017
 * Time: 07:13
 */


$user = "yaron_h";
$server = "54.210.52.142";
$groups = "[groups supplied by authentication solution]";
$GroupIsSID = "[Yes if groups are SID’s]";

$tempGroup = explode(";", $groups);
$groupList = "";

$xmlRequest = new DOMDocument();


//Create XML request
$root = $xmlRequest->appendChild($xmlRequest->createElement("Global"));
$root->appendChild($xmlRequest->createAttribute("method"))->appendChild($xmlRequest->createTextNode("GetWebTicket"));
$root->appendChild($xmlRequest->CreateElement("UserId", $user));

//Append group information to XML request

if ($groups != "") {
    $groupTag = $root->appendChild($xmlRequest->createElement("GroupList"));
    for ($i = 0; $i < sizeof($tempGroup); $i++) {
        $groupTag->appendChild($xmlRequest->createElement("string", "$tempGroup[$i]"));
    } //Defines if groups are SID:s or names

    if ($GroupIsSID == 'Yes') {
        $root->appendChild($xmlRequest->createElement("GroupIsNames", "false"));
    } else {
        $root->appendChild($xmlRequest->createElement("GroupIsNames", "true"));
    }
}

$txtRequest = $xmlRequest->saveXML();
define('xmldata', $xmlRequest->saveXML());
define('URL', 'http://' . $server . ':80/QvAJAXZfc/GetWebTicket.aspx');


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 4);
curl_setopt($ch, CURLOPT_POSTFIELDS, xmldata);

//Request ticket
$result = curl_exec($ch);
$info = curl_getinfo($ch);
$xmlResponse = new DOMDocument();
$xmlResponse->loadXML($result);
//Get ticket
$ticket = $xmlResponse->getElementsByTagName("_retval_")->item(0);
//Create redirect URL with ticket
$redirectURL = "http://$server/QvAJAXZfc/Authenticate.aspx?type=html&webticket=$ticket->nodeValue&try=[Try URL]&back=[Back URL]";
header('Location: ' . $redirectURL);

echo '<pre>' . print_r(get_defined_vars(), true) . '</pre>';
echo "============================================";