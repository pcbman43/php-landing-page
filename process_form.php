<?php
require_once 'fetch_author.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the link from the form; use the link the scrape the author's name
    $link = isset($_POST["link"]) ? $_POST["link"] : "";

    // Regular expression pattern for a TypeRacer link
    $pattern = '/^https?:\/\/(www\.)?play\.typeracer\.com(\/[a-zA-Z0-9#?=&.\/_-]*)*(\?rt=[a-z0-9]+)*$/';

    // Check if the link matches the TypeRacer URL format
    if (preg_match($pattern, $link)) {
        // Use the user-provided link to scrape the author's name
        $authorName = getAuthorNameFromTypeRacer($link);

        if (strpos($authorName, "Error: ") !== false) {
            echo $authorName;
            exit;
        }

        // Get the current timestamp
        $timestamp = time();

        // Create an associative array with name, link, and timestamp
        $data = [
            "authorName" => $authorName,
            "link" => $link,
            "timestamp" => $timestamp,
        ];

        // Save data to a JSON file (you may need to adjust the file path)
        $filename = 'submitted_links.json';

        // Read existing data from the file or create a new array if the file doesn't exist
        $existingData = file_exists($filename) ? json_decode(file_get_contents($filename), true) : [];

        // Add new data to the existing data
        $existingData[] = $data;

        // Encode and save the updated data
        file_put_contents($filename, json_encode($existingData, JSON_PRETTY_PRINT));

        echo "Link submitted successfully!";
    } else {
        echo "The link is not a valid TypeRacer link.";
    }
} else {
    echo "Invalid request.";
}
?>