<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 14/04/2017
 * Time: 04:47
 */

namespace App\Core\Services;

use App\Core\Exceptions\InvalidImageException;
use Illuminate\Filesystem\FilesystemAdapter;
use Storage;

class ImageService
{

    /**
     * @var FilesystemAdapter
     */
    private $fs;


    public function __construct(FilesystemAdapter $fs)
    {
        $this->fs = $fs;
    }

    /**
     * Upload an image and return url
     *
     * @param string $path
     * @param string $data
     * @throws InvalidImageException
     * @return string
     */
    public function upload(string $path, string $data) : string
    {
        if (strpos($data, 'data:image/png') === false) {
            throw new InvalidImageException(trans('validation.png_image'));
        }
        $image = str_replace('data:image/png;base64,', '', $data);
        $this->fs->put($path, base64_decode($image), 'public');
        return $this->fs->url($path);
    }

}