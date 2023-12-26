<?php

// Include the MusicChaert class file
require_once 'MusicChaert.php';

// Create an instance of the MusicChaert class for a specific platform
$MUSIC_CHART = new MusicChaert('VIBE'); // You can change 'VIBE' to 'MELON' or 'BUGS'

// Fetch the music chart data
// You can optionally pass a date in the format 'YYYY-MM-DD' as an argument
$RESULT = $MUSIC_CHART->GET_CHAERT('2023-01-01'); // Example date

// Check the response and handle it accordingly
if ($RESULT['STATUS'] === 'OK') {
    // Success - Process and display the chart data
    echo "Music Chart Data:\n";
    print_r($RESULT['DATA']);
} else {
    // Error handling
    echo "Error: " . $RESULT['MSG'];
    if (isset($RESULT['CODE'])) {
        echo "\nError Code: " . $RESULT['CODE'];
    }
}
