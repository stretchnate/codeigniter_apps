<?php

$songs = require('songs.php');
$songs = json_decode(json_encode($songs));

$songs_played = 0;
$iframe = "<h1>Tune in tomorrow for the next song.</h1>";
foreach($songs as $song) {
    if($song->played < 1) {
        if(strtotime($song->date) <= time()) {
            $song->played = 1;

            $iframe = $song->url;
            break;
        }
    } else {
        $songs_played++;
    }
}

if(strpos($iframe, '<h1>Tune') !== false && $songs_played == count($songs)) {
    $iframe = "<h1>It looks like you've played through the list</h1>";
}

$handle = fopen('songs.php', 'w');
fwrite($handle, "<?php\n\nreturn [");
foreach($songs as $song) {
    fwrite($handle, "\n\t[\n\t\t'title' => \"$song->title\",\n\t\t'url' => '$song->url',\n\t\t'date' => '$song->date',\n\t\t'played' => $song->played\n\t],");
}
fwrite($handle, "\n];");
fclose($handle);

?>
<html>
    <head>

    </head>
    <body>
        <?= $iframe ?>
    </body>
</html>
