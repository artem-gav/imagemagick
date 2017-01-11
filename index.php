<?php

function ration($width, $height) {
    $_ration = $width / $height;

    if($width > $height) {
        $ration = 1 / ($_ration);
    } else {
        $ration = $_ration;
    }

    return $ration;
}

// Added scale for image less than background
function scale_image($image_size, $background_size, $scale = 0.2) {
    if((1 - $image_size["width"] / $background_size["width"]) >= $scale &&
        (1 - $image_size["height"] / $background_size["height"]) >= $scale) {
        $image_size["width"] = $image_size["width"] * (1 + $scale);
        $image_size["height"] = $image_size["height"] * (1 + $scale);
    }

    return $image_size;
}

function compress_image($image_size, $background_size) {
    if($image_size["width"] <= $background_size["width"] && $image_size["height"] <= $background_size["height"]) {
        return $image_size;
    }

    $ration = ration($image_size["width"], $image_size["height"]);

    if($image_size['height'] > $background_size['height']) {
        $image_size["height"] = $background_size["height"];
        $image_size["width"] = $image_size["height"] * $ration;
    }

    if($image_size['width'] > $background_size['width']) {
        $image_size["width"] = $background_size["width"];
        $image_size["height"] = $image_size["width"] * $ration;
    }

    return $image_size;
}

function generate($path_to_image, $background_size = ['width' => 500, 'height' => 650], $background_color = 'red') {
    $image = new Imagick($path_to_image);
    $background = new Imagick();

    // crop background
    $image->trimImage(0);

    // Get `width` and `height`
    $image_size = $image->getImageGeometry();

    $image_size = scale_image($image_size, $background_size);
    $image_size = compress_image($image_size, $background_size);
    $image->scaleImage($image_size["width"], $image_size["height"]);

    // Create background
    $background->newImage($background_size["width"], $background_size["height"], new ImagickPixel($background_color));
    $background->setImageFormat('png');

    // Added image to background
    $background->compositeImage($image, Imagick::COMPOSITE_DEFAULT,
        $background_size["width"] / 2 - $image_size["width"] / 2,
        $background_size["height"] / 2 - $image_size["height"] / 2
    );

    header('Content-type: image/jpeg');

    echo $background;
}

$path = "assets/img/w2801n624.jpg";
//$path = "assets/img/ag265asd.jpg";
//$path = "assets/img/w2801n783p63.jpg";
//$path = "assets/img/w28000.jpg";
//$path = "assets/img/w1801n693.jpg";

generate($path);