<?php
header('Content-Type: application/json');

$filename = 'submitted_links.json';

if (file_exists($filename)) {
    // Read data from the file
    $jsonData = file_get_contents($filename);
    $links = json_decode($jsonData, true);

    // Filter out links older than 24 hours and format timestamps
    $currentTime = time();
    $links = array_filter($links, function ($item) use ($currentTime) {
        return ($currentTime - $item['timestamp']) <= 24 * 3600;
    });

    // Convert timestamps to human-readable format
    foreach ($links as $key => $link) {
        $links[$key]['timestamp'] = date("Y-m-d H:i:s", $link['timestamp']);
    }

    echo json_encode($links);
} else {
    // Return an empty array if the file doesn't exist
    echo json_encode([]);
}
?>
