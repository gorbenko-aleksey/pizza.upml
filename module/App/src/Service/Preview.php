<?php

namespace App\Service;

use Intervention\Image\Image;
use Intervention\Image\ImageManager;
use App\Service\Exception\PreviewException;

class Preview
{
    /**
     * @var string
     */
    private $original;

    /**
     * @var string
     */
    private $preview;

    /**
     * @var int|null
     */
    private $width;

    /**
     * @var int|null
     */
    private $height;

    /**
     * @param array $parts
     *
     * @return Image
     */
    public function generate(array $parts): Image
    {
        $this->prepare($parts);

        if (!file_exists($this->original)) {
            throw new PreviewException('File was not found');
        }

        $manager = new ImageManager(array('driver' => 'imagick'));
        $image   = $manager->make($this->original);

        $width  = $this->width;
        $height = $this->height;

        $image->resize($this->width, $this->height, function ($constraint) use ($width, $height) {
            if (!$width || !$height) {
                $constraint->aspectRatio();
            }
        });

        $image->save($this->preview);

        return $image;
    }

    /**
     * @param array $parts
     */
    protected function prepare(array $parts)
    {
        $this->preview = getcwd() . '/public/' . implode('/', $parts);

        $name = $parts[count($parts) - 1];

        $nameParts    = explode('_', $name);
        $this->width  = $nameParts[0] ? intval($nameParts[0]) : null;
        $this->height = $nameParts[1] ? intval($nameParts[1]) : null;

        $parts[count($parts) - 1] = $nameParts[2];

        $this->original = getcwd() . '/public/' . implode('/', $parts);
    }
}
