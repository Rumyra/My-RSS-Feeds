<?php

//getting jason and converting to usable php
$json = file_get_contents('https://trello.com/board/to-do/5028dc7798f4497a6282cc0d/board.json');
$data = json_decode($json);

//Functions needed for processing data
function splitDate($actionDateTime) {
    $actionTimeInfo = array();

    $actionTimeInfo['year'] = substr($actionDateTime, 0, 4);
    $actionTimeInfo['month'] = substr($actionDateTime, 5, 2);
    $actionTimeInfo['day'] = substr($actionDateTime, 8, 2);
    $actionTimeInfo['time'] = substr($actionDateTime, 11, 5);
    $actionTimeInfo['pubDate'] = date('D, d M Y H:i:s O',mktime(0,0,0,$actionTimeInfo['month'],$actionTimeInfo['day'],$actionTimeInfo['year']));

    return $actionTimeInfo;

}

//begin RSS data output
$output = "<?xml version=\"1.0\"?>
            <rss version=\"2.0\">
                <channel>
                    <title>Completed Tasks</title>
                    <link>https://trello.com/board/to-do/5028dc7798f4497a6282cc0d</link>
                    <description>An RSS feed of tasks I have completed</description>
                    <language>en-gb</language>
            ";

//variables needed for output items
$taskName = '';
$actionTime = '';
$actionDate = '';
$pubDate = '';
$cardUrl = '';

foreach ($data->actions as $action) {
    //flag
    $addItem = false;

    //if card is moved to done set variables for item
    if (($action->type === 'updateCard') && (isset($action->data->listAfter)) && ($action->data->listAfter->name === 'Done')) {
            $taskName = $action->data->card->name;
            $actionTime = splitDate($action->date);
            foreach ($data->cards as $card) {
                if ($action->data->card->id === $card->id) {
                   $cardUrl = $card->url;
                }
            }
            $addItem = true;

            
        
    }

    //if checkbox is ticked set variables for item
    if (($action->type === 'updateCheckItemStateOnCard') && (isset($action->data->checkItem->state)) && ($action->data->checkItem->state === 'complete')) {
            $taskName = $action->data->checkItem->name;
            $actionTime = splitDate($action->date);
            foreach ($data->cards as $card) {
                if ($action->data->card->id === $card->id) {
                   $cardUrl = $card->url;
                }
            }
            $addItem = true;
    }
    //create items
    if ($addItem) {
        $output .= "<item>
            <title>Did a task</title>
            <link>".$cardUrl."</link>
            <description>Completed ".$taskName.", at ".$actionTime['time'].", on 
                ".$actionTime['day']."/".$actionTime['month']."/".$actionTime['year'].
                ".</description>
            <guid>".$cardUrl.$action->id."</guid>
            <pubDate>".$actionTime['pubDate']."</pubDate>
        </item>";
    }

}            
$output .= "</channel></rss>";
header("Content-Type: application/rss+xml");
echo $output;

?>

<!-- <!DOCTYPE html>
<html><head></head>
<body>
    <pre>
<?php
echo $output;
?>
    </pre>
</body>
</html> -->


