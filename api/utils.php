<?php

/**
 * Fetch the contents of a URL with cURL
 *
 * @param string $url The URL to fetch
 * @param string $userAgent The user agent to use
 * @param string|null $githubToken Optional GitHub token for authenticated requests
 * @return string|false The contents of the URL
 */
function curlGetContents($url, $userAgent, $githubToken = null)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_AUTOREFERER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_VERBOSE, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
    if ($githubToken) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: token $githubToken"]);
    }
    return curl_exec($ch);
}

/**
 * Normalize the GitHub contents API response to a list of image file entries.
 *
 * @param mixed $decodedResponse The decoded GitHub API response
 * @return array<int, array<string, mixed>> The valid image entries
 */
function parseImageEntries($decodedResponse)
{
    if (!is_array($decodedResponse) || array_is_list($decodedResponse) === false) {
        return [];
    }

    return array_values(array_filter($decodedResponse, function ($entry) {
        return is_array($entry)
            && ($entry["type"] ?? null) === "file"
            && is_string($entry["name"] ?? null)
            && $entry["name"] !== ""
            && is_string($entry["download_url"] ?? null)
            && $entry["download_url"] !== "";
    }));
}

/**
 * Build the gallery payload used by the page.
 *
 * @param array<int, array<string, mixed>> $images The valid image entries
 * @param string $imgproxyPrefix The thumbnail URL prefix
 * @return array<int, array<string, string>> The image data for rendering
 */
function buildGalleryImages($images, $imgproxyPrefix)
{
    return array_map(function ($image) use ($imgproxyPrefix) {
        $name = $image["name"];

        return [
            "name" => $name,
            "full" => $image["download_url"],
            "thumbnail" => $imgproxyPrefix . rawurlencode($name),
        ];
    }, $images);
}

/**
 * Fetch an image, if it is larger than 4.5MB, redirect to it
 * otherwise, return the image as content.
 *
 * @param string $url The URL to fetch
 * @param string $userAgent The user agent to use
 * @param bool $redirect Whether to force a redirect
 */
function displayImage($url, $userAgent, $redirect)
{
    // don't need to fetch the image if we're redirecting
    $contents = $redirect ? "" : curlGetContents($url, $userAgent);

    // Set headers to allow access from any origin
    header("Access-Control-Allow-Origin: *");

    // redirect if redirect is set or the image is larger than 4.5MB
    if ($redirect || strlen($contents) > 4500000) {
        header("Location: $url");
        exit;
    }

    // set content type
    if (preg_match("/\.(jpg|jpeg)$/", $url)) {
        header('Content-Type: image/jpeg');
    } elseif (preg_match("/\.(png)$/", $url)) {
        header('Content-Type: image/png');
    } elseif (preg_match("/\.(gif)$/", $url)) {
        header('Content-Type: image/gif');
    }
    // set default filename
    header('Content-Disposition: inline; filename="' . basename($url) . '"');
    // output the image
    exit($contents);
}

function updateImagesJson($githubToken = null)
{
    $REPO = "DenverCoder1/minimalistic-wallpaper-collection";
    $IMAGES_DIRECTORY = "images";

    // API url to get a listing of images in the directory on GitHub
    $GITHUB_API_URL = "https://api.github.com/repos/$REPO/contents/$IMAGES_DIRECTORY/";

    // fetch the list of images from the GitHub API
    $images_response = curlGetContents($GITHUB_API_URL, "Minimalistic Wallpaper Collection (update-images-json.php)", $githubToken);

    $images = json_decode($images_response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        exit("Error: images could not be parsed from GitHub API: " . print_r($images_response, true));
    }

    // write the images to a local JSON file
    file_put_contents(__DIR__ . '/generated/images.json', json_encode($images, JSON_PRETTY_PRINT));

    echo "Successfully updated generated/images.json with " . count($images) . " entries.\n";
}
