<?php

$getFrom = date("Y-m-d", time()-(7*24*60*60));
$getTo = date("Y-m-d", time()+(24*60*60));

//getting jason and converting to usable php
$json = file_get_contents('https://rumyra@gmail.com:riddick@drivetoimprove.co.uk/api/m/JourneyInfo/316?from='.$getFrom.'&to='.$getTo);
$data = json_decode($json);

$output = "<?xml version=\"1.0\"?>
            <rss version=\"2.0\">
                <channel>
                    <title>My car journeys</title>
                    <link>http://www.ruthjohn.com/rss.php</link>
                    <description>An RSS feed of my car journeys</description>
                    <language>en-gb</language>
            ";

foreach ($data->GetJourneyInfoResult as $route) {
    //get start timestamp
    $longDateLength = (strlen($route->StartDateLong))-7;
    $driveDateTime = substr($route->StartDateLong, 0, $longDateLength);
    
    //set info
    $driveDate = date("l jS F", $driveDateTime);
    $driveStartTime = date("g:ia", $driveDateTime);
    $pubDate = date('D, d M Y H:i:s O', $driveDateTime);
    $guid = date('His-jny', $driveDateTime);
    //$driveDate = htmlentities(strip_tags($route->StartDateLong));
    $driveTime = $route->Duration;
    $driveTime = round($driveTime/60).' mins';
    $mapFrom = $route->StartTown;
    $mapTo = $route->EndTown;
    $avSpeed = $route->AvgSpeed.'mph';

    $output .= "<item>
        <title>Drove (".$driveStartTime.")</title>
        <link>https://drivetoimprove.co.uk/</link>
        <description>Went for a drive on ".$driveDate." at ".$driveStartTime." for ".$driveTime.
        " from ".$mapFrom.
        " to ".$mapTo.
        " at an average speed of ".$avSpeed.".</description>
        <guid>http://www.ruthjohn.com/car-journeys/".$guid."</guid>
        <pubDate>".$pubDate."</pubDate>
    </item>";
}
            
$output .= "</channel></rss>";
header("Content-Type: application/rss+xml");
echo $output;


?>
