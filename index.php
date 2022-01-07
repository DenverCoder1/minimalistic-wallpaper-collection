<?php

/**
 * Create a CURL request to the specified URL
 * 
 * @param string $url The URL to request
 * 
 * @return string The response from the URL
 */
function curl_get_contents($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

// get base url of the site
$base_url = rtrim("https://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}", '/') . "/";

// use GitHub raw URL if the server is localhost since imgproxy won't find local files
if ($_SERVER['SERVER_NAME'] == 'localhost') {
    $base_url = "https://raw.githubusercontent.com/DenverCoder1/Minimalistic-Wallpaper-Collection/main/";
}

// directory where the images are stored
$img_dir = "images/";

// prefix for generating 175x105 thumbnails
$imgproxy_prefix = "https://dc1imgproxy.herokuapp.com/x/fill/175/105/sm/0/plain/" . urlencode($base_url . $img_dir);

// get a list of all the files in the images directory
$images = glob($img_dir . "*", GLOB_BRACE);

// if the random query string parameter is set, pick a random image
if (isset($_GET['random'])) {
    // get the image url
    $image_url = $base_url . $images[array_rand($images)];

    // set content type
    if (preg_match("/\.(jpg|jpeg)$/", $image_url)) {
        header('Content-Type: image/jpeg');
    } else if (preg_match("/\.(png)$/", $image_url)) {
        header('Content-Type: image/png');
    } else if (preg_match("/\.(gif)$/", $image_url)) {
        header('Content-Type: image/gif');
    }

    // return the contents of the image at the url
    exit(curl_get_contents($image_url));
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minimalistic Wallpaper Collection</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <!-- glightbox -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />
    <script src="https://cdn.jsdelivr.net/gh/mcstudios/glightbox/dist/js/glightbox.min.js"></script>
    <!-- Custom CSS -->
    <style>
        body {
            background: #1d1d1d;
            color: #fff;
            font-family: 'Poppins', 'Open Sans', Arial, Helvetica, sans-serif;
            text-align: center;
        }

        .title {
            margin-top: 2em;
        }

        .icons {
            margin-top: 2em;
            margin-bottom: 2em;
        }

        .icons svg:hover {
            cursor: pointer;
            filter: drop-shadow(0px 0px 2px rgb(255 255 255 / 0.4))
        }

        .gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            grid-gap: 1em;
            width: 95vw;
            max-width: 1200px;
            margin: auto;
        }

        .gallery img {
            width: 100%;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 130px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
            transition: 0.1s ease-in-out;
        }

        .gallery img:hover {
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
        }
    </style>
</head>

<body>
    <h1 class="title">Minimalistic Wallpaper Collection</h1>

    <div class="icons">
        <a href="https://github.com/DenverCoder1/Minimalistic-Wallpaper-Collection" target="_blank" rel="noopener noreferrer" title="GitHub">
            <svg stroke="white" fill="white" stroke-width="0" viewBox="0 0 1024 1024" height="2em" width="2em" xmlns="http://www.w3.org/2000/svg">
                <path d="M511.6 76.3C264.3 76.2 64 276.4 64 523.5 64 718.9 189.3 885 363.8 946c23.5 5.9 19.9-10.8 19.9-22.2v-77.5c-135.7 15.9-141.2-73.9-150.3-88.9C215 726 171.5 718 184.5 703c30.9-15.9 62.4 4 98.9 57.9 26.4 39.1 77.9 32.5 104 26 5.7-23.5 17.9-44.5 34.7-60.8-140.6-25.2-199.2-111-199.2-213 0-49.5 16.3-95 48.3-131.7-20.4-60.5 1.9-112.3 4.9-120 58.1-5.2 118.5 41.6 123.2 45.3 33-8.9 70.7-13.6 112.9-13.6 42.4 0 80.2 4.9 113.5 13.9 11.3-8.6 67.3-48.8 121.3-43.9 2.9 7.7 24.7 58.3 5.5 118 32.4 36.8 48.9 82.7 48.9 132.3 0 102.2-59 188.1-200 212.9a127.5 127.5 0 0 1 38.1 91v112.5c.8 9 0 17.9 15 17.9 177.1-59.7 304.6-227 304.6-424.1 0-247.2-200.4-447.3-447.5-447.3z"></path>
            </svg>
        </a>
    </div>

    <div class="gallery">
        <?php foreach ($images as $image) : ?>
            <a href="<?= $image; ?>" class="glightbox">
                <img src="<?= $imgproxy_prefix . basename($image); ?>" loading="lazy" alt="<?php echo basename($image); ?>" title="<?php echo basename($image); ?>">
            </a>
        <?php endforeach; ?>
    </div>

    <script type="text/javascript">
        const lightbox = GLightbox();
    </script>
</body>

</html>