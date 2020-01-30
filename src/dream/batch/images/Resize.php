<?php
namespace Dream\Batch\Images;

/**
 *
 */
class Resize
{
    protected $images = [];

    protected $source;

    protected $mimeTypes = ['image/jpeg','image/png','image/webp'];

    protected $webpSupported = true;

    protected $useImageScale = true;

    protected $invalid = [];

    protected $outputSizes = [];

    protected $useLongerDimension;

    protected $jpegQuality = 75;

    protected $pngCompression = 0;

    protected $resample;

    protected $destination;

    protected $generated = [];

    public function __construct(array $images,$sourceDirectory)
    {
        if (!is_null($sourceDirectory) && !is_dir($sourceDirectory)) {
            throw new InvalidArgumentException($sourceDirectory . "is not a directory");
        }
        $this->images = $images;
        $this->source = $sourceDirectory;
        if (PHP_VERSION_ID < 7000) {
            throw new \Exception("This version of php is not supported. use 7.0 or higher", 1);
        }
        $this->checkImages();
    }

    protected function checkImages()
    {
        foreach ($this->images as $i => $image) {
            $this->images[$i] = [];
            if ($this->source) {
                $this->images[$i]['file'] = $this->source . DS . $image;
            }
            else {
                $this->images[$i]['file'] = $image;
            }
            if (file_exists($this->images[$i]['file']) && is_readable($this->images[$i]['file'])) {
                $size = getimagesize($this->images[$i]['file']);
                if ($size === false && $this->webpSupported && mime_content_type == 'image/webp') {
                    $this->images[$i] = $this->getWebpDetails($this->images[$i]['file']);
                }
                elseif ($size[0] === 0 || !in_array($size['mime'],$this->mimeTypes)) {
                    $this->invalid[] = $this->images[$i]['file'];
                }
                else {
                    if ($size['mime'] === 'image/jpeg') {
                        $results = $this->checkJpegOrientation($this->images[$i]['file'],$size);
                        $this->images[$i]['file'] = $results['file'];
                        $size = $results['size'];
                    }
                    $this->images[$i]['h'] = $size[0];
                    $this->images[$i]['w'] = $size[1];
                    $this->images[$i]['type'] = $size['mime'];
                }
            }
            else {
                $this->invalid[] = $this->images[$i]['file'];
            }
        }
    }

    public function setOutputSize(array $sizes,$useLongerDimension = true)
    {
        foreach ($sizes as $size) {
            if (!is_numeric($size) || $size <= 0) {
                throw new \InvalidArgumentException("Sizes must be an array of positive numbers");
            }
        }
        $this->useLongerDimension = $useLongerDimension;
    }

    public function setJpegQuality($number)
    {
        if (!is_numeric($number) || $number < 0 || $number > 100) {
            throw new \Exception("JPG quality should be between 1 and 100", 1);
        }
        $this->jpegQuality = $number;
    }

    public function setPngCompression($number)
    {
        if (!is_numeric($number) || $number < 0 || $number > 9) {
            throw new \Exception("PNG compression should be between 0 (no compression) and 9", 1);
        }
        $this->pngCompression = $number;
    }

    public function setResamplingMethod($value)
    {
        switch (strtolower($value)) {
            case 'bicubic':
                $this->resample = IMG_BICUBIC;
                break;
            case 'bicubic-fixed':
                $this->resample = IMG_BICUBIC_FIXED;
                break;
            case 'nearest-neighbour':
            case 'nearest-neighbor':
                $this->resample = IMG_NEAREST_NEIGHBOUR;
                break;
            default:
                $this->resample = IMG_BILINEAR_FIXED;
                break;
        }
    }

    public function outPutImages($destination)
    {

        $this->destination = $destination;
        foreach ($this->images as $i => $value) {
            //skip files that are invalid
            if (in_array($this->images[$i]['file'],$this->invalid)) {
                continue;
            }
            //create an image resource for the current image
            $resource = $this->createImageResource($this->images[$i]['file'],$this->images[$i]['type']);
            //watermark

            //delegate the output generation to another method
            $this->generateOutput($this->images[$i],$resource);
            imagedestroy($resource);
        }
        return ['output' => $this->generated, 'invalid' => $this->invalid];
    }

    protected function getWebpDetails($file)
    {
        $detail = [];
        $resource = imagecreatefromwebp($file);
        $detail['file'] = $image;
        $detail['w'] = imagesx($resource);
        $detail['h'] = imagesy($resource);
        $images['type'] = 'image/webp';
        imagedestroy($resource);
        return $detail;
    }

    protected function checkJpegOrientation($image,$size)
    {
        $outputFile = $image;
        return ['file' => $outputFile,'size' => $size];

        $exif = exif_read_data($image);
        //calculate the angle of rotation
        if (!empty($exif['Orientation'])) {
            switch ($exif['Orientation']) {
                case 3:
                    $angle = 180;
                    break;
                case 6:
                    $angle = -90;
                    break;
                case 8:
                    $angle = 90;
                    break;
                default:
                    $angle = null;
                    break;
            }
            //rotate the image if neccesary
            if (!is_null($angle)) {
                $original = imagecreatefromjpeg($image);
                $rotated = imagerotate($original,$angle,0);
                //save the rotated imag
                $extension = pathinfo($image,PATH_INFO_EXTENSION);
                $outputFile = str_replace($extension,'_rotated.jpg',$image);
                imagejpeg($rotated,$outputFile,100);

                //get the dimensions of the new image
                $size = getimagesize($outputFile);
                imagedestroy($original);
                imagedestroy($rotated);
            }
        }
        return ['file' => $outputFile,'size' => $size];
    }

    protected function generateOutput($image,$resource)
    {
        $storedSizes = $this->ouptputSizes;
        $nameParts = pathinfo($image['file']);
        if ($this->useImageScale) {
            if ($this->useLongerDimension && imagesy($resource) > imagesx($resource)) {
                $this->recalculateSize($resource);
            }
            foreach ($this->outputSizes as $outputSize) {
                if ($outputSize >= $image['w']) {
                    continue;
                }
                $scaled = imagescale($resource,$outputSize,-1);
                $filename = $nameParts['filename'] . '_' . $outputSize . '.' . $nameParts['extension'];
                $this->outputFile($scaled,$image['type'],$filename);
            }
        }
        $this->outputSizes = $storedSizes;
    }

    protected function recalculateSize($resource)
    {
        $h = imagesy($resource);
        $w = imagesx($resource);
        foreach ($this->ouptputSizes as &$size) {
            $size = round($size * $w /$h -1);
        }
    }

    protected function outputFile($scaled,$type,$name)
    {
        $success = false;
        $outputFile = $this->destination . $name;
        switch ($type) {
            case 'image/jpeg':
                $success = imagejpeg($scaled,$outputFile,$this->jpegQuality);
                break;
            case 'image/png':
                $success = imagepng($scaled,$outputFile,$this->pngCompression);
                break;
            case 'image/gif':
                $success = imagegif($scaled,$outputFile);
                break;
            case 'image/webp':
                $success = imagewebp($scaled,$outputFile);
        }
        imagedestroy($scaled);
        if ($success) {
            $this->generated[] = $outputFile;
        }
    }

    protected function createImageResource($filename,$type)
    {
        $success = false;
        switch ($type) {
            case 'image/jpeg':
                $success = imagecreatefromjpeg($filename);
                break;
            case 'image/png':
                $success = imagecreatefrompng($filename);
                break;
            case 'image/gif':
                $success = imagecreatefromgif($filename);
                break;
            case 'image/webp':
                $success = imagecreatefromwebp($scaled,$outputFile);
        }
        return $success;
    }
}
