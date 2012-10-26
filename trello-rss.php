<?php

//getting jason and converting to usable php
$json = file_get_contents('https://trello.com/board/to-do/5028dc7798f4497a6282cc0d/board.json');
$data = json_decode($json);

$output = "<?xml version=\"1.0\"?>
            <rss version=\"2.0\">
                <channel>
                    <title>Completed Tasks</title>
                    <link>https://trello.com/board/to-do/5028dc7798f4497a6282cc0d</link>
                    <description>An RSS feed of tasks I have completed</description>
                    <language>en-gb</language>
            ";
$taskName = '';
$actionTime = '';
$actionDate = '';
$pubDate = '';

function splitDate($actionDateTime) {
    $actionTimeInfo = array();

    $actionTimeInfo['year'] = substr($actionDateTime, 0, 4);
    $actionTimeInfo['month'] = substr($actionDateTime, 5, 2);
    $actionTimeInfo['day'] = substr($actionDateTime, 8, 2);
    $actionTimeInfo['time'] = substr($actionDateTime, 11, 5);

    return $actionTimeInfo;

}

//2012-10-10T09:14:49.348Z

foreach ($data->actions as $action) {

    //if card is moved to done add an item
    if (($action->type === 'updateCard') && (isset($action->data->listAfter))) {
        if ($action->data->listAfter->name === 'Done') {
            $taskName = $action->data->card->name;
            $actionTime = splitDate($action->date);
            $cardUrl = '';
            foreach ($data->cards as $card) {
                if ($action->card->id === $card->id) {
                    $cardUrl = $card->url;
                }
            }

            $output .= "<item>
                <title>Did a task</title>
                <link>".$cardUrl."</link>
                <description>Completed ".$taskName.", at ".$actionTime['time'].", on 
                    ".$actionTime['day']."/".$actionTime['month']."/".$actionTime['year'].
                    ".</description>
                <guid>".$action->id."</guid>
                <pubDate>Action date</pubDate>
            </item>";
        }
        
    }



    //if(stristr($output, $action->type) === FALSE) {
    //    $output .= $action->type.'<br />';
    //}
}
//foreach ($data->stdClass as $item) {
    //get start timestamp
    //$longDateLength = (strlen($route->StartDateLong))-7;
    //$driveDateTime = substr($route->StartDateLong, 0, $longDateLength);
    
    //set info
    //$driveDate = date("l jS F", $driveDateTime);
    //$driveStartTime = date("g:ia", $driveDateTime);
    //$pubDate = date('D, d M Y H:i:s T', $driveDateTime);
    //$driveDate = htmlentities(strip_tags($route->StartDateLong));
    //$driveTime = $route->Duration;
    //$mapFrom = $route->StartTown;
    //$mapTo = $route->EndTown;
    //$avSpeed = $route->AvgSpeed.'mph';

    $output .= "<item>
        <title>Did a task</title>
        <link></link>
        <description>Completed task name, at action time, on action date.</description>
        <guid>unique action id</guid>
        <pubDate>Action date</pubDate>
    </item>";
//}
            
$output .= "</channel></rss>";
//header("Content-Type: application/rss+xml");
//echo $output;


?>
<!DOCTYPE html>
<html>
<head></head>
<body>
    <h1>trello feed</h1>
<?php
echo $output;
?>
    <pre>
<?php
var_dump($data);
?>
    </pre>
</body>
</html>

