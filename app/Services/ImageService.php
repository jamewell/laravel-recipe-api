<?php

namespace App\Services;

use App\Constants\ImageConstants;
use App\Enums\ImageMimeType;
use App\Enums\ImageSize;
use App\Exceptions\ImageDimensionsException;
use App\Exceptions\ImageProcessingException;
use App\Exceptions\ImageTooLargeException;
use App\Exceptions\ImageTooSmallException;
use App\Exceptions\InvalidImageTypeException;
use App\Exceptions\InvalidImageUploadException;
use App\Models\Recipe;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\GifEncoder;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\Encoders\PngEncoder;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\EncoderInterface;
use Intervention\Image\Interfaces\ImageInterface;

class ImageService
{
    /**
     * @param  array<string, int>  $sizes
     */
    public function __construct(
        private ImageManager $imageManager,
        protected ?string $disk = null,
        protected array $sizes = [],
    ) {
        $this->disk = $disk ?? config('filesystems.default');
        $this->sizes = ImageSize::getSizes();
        $this->imageManager = new ImageManager(new Driver);
    }

    /**
     * @return array<string, string>
     */
    public function uploadImage(
        UploadedFile $file,
        string $directory,
        string $type = 'main'
    ): array {
        $this->validateImage($file);

        try {
            $fileName = $this->generateFileName($file, $type);
            // $directory = ImageConstants::RECIPE_IMAGE_DIRECTORY."/{$recipe->id}";

            $uploadedImages = [];

            $image = $this->imageManager->read($file->getRealPath());

            foreach ($this->sizes as $sizeName => $width) {
                $processedImage = $this->processImageForSize($image, $width);

                $quality = $sizeName === ImageSize::THUMBNAIL->getName()
                    ? ImageConstants::THUMBNAIL_QUALITY
                    : ImageConstants::DEFAULT_QUALITY;

                $path = "{$directory}/{$sizeName}/{$fileName}";
                $contents = $this->encodeImage($processedImage, $file->getClientOriginalExtension(), $quality);

                Storage::disk($this->disk)->put($path, $contents);

                $uploadedImages[$sizeName] = $path;
            }

            return $uploadedImages;
        } catch (\Exception $e) {
            throw new ImageProcessingException(
                'Failed to process image: '.$e->getMessage()
            );
        }
    }

    public function deleteImage(string $directory, ?string $imagePath = null): void
    {
        // $directory = ImageConstants::RECIPE_IMAGE_DIRECTORY."/{$recipe->id}";

        if ($imagePath) {
            foreach (array_keys($this->sizes) as $size) {
                $path = dirname($imagePath)."/{$size}/".basename($imagePath);
                if (Storage::disk($this->disk)->exists($path)) {
                    Storage::disk($this->disk)->delete($path);
                }
            }
        } else {
            if (Storage::disk($this->disk)->exists($directory)) {
                Storage::disk($this->disk)->deleteDirectory($directory);
            }
        }
    }

    public function getImageUrl(
        string $path,
        ?string $size = null
    ): string {
        $size = $size ?? ImageSize::ORIGINAL->getName();
        $sizePath = dirname($path)."/{$size}/".basename($path);

        return Storage::disk($this->disk)->exists($sizePath)
            ? Storage::url($sizePath)
            : $this->getFallbackImage($size);
    }

    protected function processImageForSize(ImageInterface $image, int $targetWidth): ImageInterface
    {
        if ($targetWidth > 0 && $image->width() > $targetWidth) {
            return $image->scaleDown($targetWidth);
        }

        return $image;
    }

    protected function encodeImage(
        ImageInterface $image,
        string $extension,
        int $quality,
    ): string {
        $encoder = $this->getEncoderForExtension($extension, $quality);

        return (string) $image->encode($encoder);
    }

    protected function getEncoderForExtension(string $extension, int $quality): EncoderInterface
    {
        return match (strtolower($extension)) {
            'jpg', 'jpeg' => new JpegEncoder($quality),
            'png' => new PngEncoder,
            'webp' => new WebpEncoder($quality),
            'gif' => new GifEncoder,
            default => new JpegEncoder($quality),
        };
    }

    protected function validateImage(UploadedFile $file): void
    {
        if (! $file->isValid()) {
            throw new InvalidImageUploadException;
        }

        if (! in_array($file->getMimeType(), ImageMimeType::allowedTypes())) {
            throw new InvalidImageTypeException;
        }

        if ($file->getSize() > ImageConstants::MAX_FILE_SIZE_BYTES) {
            throw new ImageTooLargeException;
        }

        try {
            $image = $this->imageManager->read($file->getRealPath());

            if ($image->width() < ImageConstants::MIN_WIDTH ||
                $image->height() < ImageConstants::MIN_HEIGHT) {
                throw new ImageTooSmallException;
            }

            if ($image->width() > ImageConstants::MAX_WIDTH ||
                $image->height() > ImageConstants::MAX_HEIGHT) {
                throw new ImageTooLargeException;
            }
        } catch (ImageDimensionsException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new ImageProcessingException(
                'Failed to validate image dimensions: '.$e->getMessage()
            );
        }
    }

    protected function generateFileName(UploadedFile $file, string $type): string
    {
        $extension = $file->getClientOriginalExtension();
        $timestamp = now()->timestamp;
        $random = Str::random(8);

        return "{$type}_{$timestamp}_{$random}.{$extension}";
    }

    protected function getFallbackImage(string $size): string
    {
        $fallbackImages = [
            'original' => ImageConstants::FALLBACK_IMAGE_DIRECTORY.'/recipe_original.jpg',
            'large' => ImageConstants::FALLBACK_IMAGE_DIRECTORY.'/recipe_large.jpg',
            'medium' => ImageConstants::FALLBACK_IMAGE_DIRECTORY.'/recipe_medium.jpg',
            'small' => ImageConstants::FALLBACK_IMAGE_DIRECTORY.'/recipe_small.jpg',
            'thumbnail' => ImageConstants::FALLBACK_IMAGE_DIRECTORY.'/recipe_thumbnail.jpg',
        ];

        return asset($fallbackImages[$size] ?? $fallbackImages['original']);
    }
}
