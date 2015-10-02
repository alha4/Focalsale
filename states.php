
<?php
$country= 'RU'; //$_GET["country"];
$request_url = "http://ws.geonames.org/countryInfo?username=demo&country=" . $country;
$ch = curl_init($request_url);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$xml_raw = curl_exec($ch);

$countryxml = simplexml_load_string($xml_raw);
echo '<select name="statename">' . "\n";

echo '<option selected="selected" value="">Select a state</option>' . "\n";
foreach ($countryxml->country as $link)  {
    $geonameid = $link->geonameId;
    $stateurl = "http://ws.geonames.org/children?username=demo&geonameId=" . $geonameid;
    $statexml = simplexml_load_file($stateurl);
        foreach ($statexml->geoname as $link)  {
            $statename = $link->name;
            echo '<option value="' . $statename . '">' . $statename . '</option>' . "\n";
        }
    }
echo '</select>';
?>