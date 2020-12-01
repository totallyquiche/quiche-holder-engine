<?php

declare(strict_types=1);

return function ($event) {
    $width = (int) $event['width'];
    $height = (int) $event['height'];

    $image = new Imagick('quiche.jpg');

    $ratio = $width / $height;

    // Original image dimensions
    $original_width = $image->getImageWidth();
    $original_height = $image->getImageHeight();
    $original_ratio = $original_width / $original_height;

    // Determine new image dimensions to scale to.
    // Also determine cropping coordinates.
    if ($ratio > $original_ratio) {
        $new_width = $width;
        $new_height = $width / $original_width * $original_height;
        $crop_x = 0;
        $crop_y = intval(($new_height - $height) / 2);
    } else {
        $new_width = $height / $original_height * $original_width;
        $new_height = $height;
        $crop_x = intval(($new_width - $width) / 2);
        $crop_y = 0;
    }

    // Scale image to fit minimal of provided dimensions.
    $image->scaleImage((int) $new_width, (int) $new_height, true);

    // Now crop image to exactly fit provided dimensions.
    $image->cropImage($width, $height, $crop_x, $crop_y);

    $image_blob = $image->getImageBlob();

    $image->clear();

    return base64_encode($image_blob);
};