<?php

//$getFrom = date("Y-m-d");
//$getTo = date("Y-m-d", time()+(24*60*60));

//getting jason and converting to usable php
$json = file_get_contents('https://api.twitter.com/1/statuses/user_timeline.json?screen_name=rumyra');
$data = json_decode($json);

$output = "<?xml version=\"1.0\"?>
            <rss version=\"2.0\">
                <channel>
                    <title>Rumyra's Twitter status'</title>
                    <link>http://www.twitter.com/rumyra</link>
                    <description>A feed of Rumyras twitters</description>
                    <language>en-gb</language>
            ";
$output = 'ALL';
foreach ($data as $status) {
    //get start timestamp
    //$longDateLength = (strlen($route->StartDateLong))-7;
    //$driveDateTime = substr($route->StartDateLong, 0, $longDateLength);
    
    //set info
    //$driveDate = date("l jS F", $driveDateTime);
    //$driveStartTime = date("g:ia", $driveDateTime);
    //$pubDate = date('D, d M y H:i:s O', $driveDateTime);
    //$driveDate = htmlentities(strip_tags($route->StartDateLong));
    //$driveTime = $route->Duration;
    //$mapFrom = $route->StartTown;
    //$mapTo = $route->EndTown;
    //$avSpeed = $route->AvgSpeed.'mph';

    $output .= $status->text.'<br />';

    // $output .= "<item>
    //     <title>Rumyra went for a drive.</title>
    //     <link>https://drivetoimprove.co.uk/</link>
    //     <description>On ".$driveDate." at ".$driveStartTime." for ".$driveTime."&#60;br /&#62;
    //     from ".$mapFrom."
    //     to ".$mapTo."&#60;br /&#62;
    //     at an average speed of ".$avSpeed.".</description>
    //     <pubDate>".$pubDate."</pubDate>
    // </item>";
}
            
//$output .= "</channel></rss>";
//header("Content-Type: application/rss+xml");
//echo $output;
?>

<!DOCTYPE html>
<html>
<head></head>
<body>
    <h1>twitter status feed</h1>
    <pre>
<?php
echo $output;
var_dump($data);
?>
    </pre>
</body>
</html>


