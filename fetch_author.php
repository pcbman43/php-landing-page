<?php

require 'vendor/autoload.php'; // Assuming you've installed Panther via Composer

use Symfony\Component\Panther\Client;

function getAuthorNameFromTypeRacer($url) {

    try {
        // Create a new Panther client
        $client = Client::createFirefoxClient();

        // Request the page
        $crawler = $client->request('GET', $url);

        // Wait for the '.room-title' element to be rendered
        $client->waitFor('.room-title');

        // Get the text content of the '.room-title' element
        $fullTitle = $crawler->filter('.room-title')->text();

        // Removing the 'Racetrack' part from the title
        $authorName = str_replace("'s Racetrack", "", $fullTitle);

        // Close the browser and return the author name
        $client->quit();
        return trim($authorName);
    } catch (Exception $e) {

        // Close the browser
        $client->quit();

        // Strip Dismissed user prompt dialog:
        $error = str_replace("Dismissed user prompt dialog: ", "", $e->getMessage());

        // Return an error message
        return "Error: " . $error;
    }
}