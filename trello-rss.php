<?php

$getFrom = date("Y-m-d");
$getTo = date("Y-m-d", time()+(24*60*60));

//getting jason and converting to usable php
$json = file_get_contents('https://trello.com/board/to-do/5028dc7798f4497a6282cc0d/board.json');
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
    $pubDate = date('D, d M Y H:i:s T', $driveDateTime);
    //$driveDate = htmlentities(strip_tags($route->StartDateLong));
    $driveTime = $route->Duration;
    $mapFrom = $route->StartTown;
    $mapTo = $route->EndTown;
    $avSpeed = $route->AvgSpeed.'mph';

    $output .= "<item>
        <title>Rumyra went for a drive.</title>
        <link>https://drivetoimprove.co.uk/</link>
        <description>On ".$driveDate." at ".$driveStartTime." for ".$driveTime."&#60;br /&#62;
        from ".$mapFrom."
        to ".$mapTo."&#60;br /&#62;
        at an average speed of ".$avSpeed.".</description>
        <pubDate>".$pubDate."</pubDate>
    </item>";
}
            
$output .= "</channel></rss>";
//header("Content-Type: application/rss+xml");
//echo $output;


?>
<!DOCTYPE html>
<html>
<head></head>
<body>
    <h1>trello feed</h1>
    <pre>
<?php
var_dump($data);
?>
    </pre>
</body>
</html>

