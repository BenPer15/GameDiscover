<?php

namespace App\Traits;

use Error;

trait ManagesGameImages
{
    protected const IMAGE_BASE_PATH = '//images.igdb.com/igdb/image/upload';

    protected function getImageUrl($image): string
    {
        if($image === null) {
            return 'https://via.placeholder.com/264x352?text=No+Cover';
        }
        $id = $image->getAttribute('image_id');
        if ($id === null) {
            throw new Error('Property [image_id] is missing from the response. Make sure you specify `image_id` inside the fields attribute.');
        }

        return static::IMAGE_BASE_PATH . "/t_original/$id.jpg";
    }

    protected function getFilteredImages($images, $minHeight = 0)
    {
        return $images->filter(function ($image) use ($minHeight) {
            return $image['height'] >= $minHeight;
        });
    }

    private function getRandomImageUrl($images, $minHeight = 0)
    {
        $filteredImages = $this->getFilteredImages($images, $minHeight);

        if ($filteredImages->isNotEmpty()) {
            $randomImage = $filteredImages->random();
            return static::IMAGE_BASE_PATH . "/t_original/{$randomImage['id']}.jpg";
        }
        return null;
    }

}
