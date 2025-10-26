<?php
namespace App\Traits;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Encoders\AutoEncoder;
use Intervention\Image\ImageManager;

trait HandlesProductPhotos
{
    /**
     * Обработать загрузку фотографий для продукта.
     *
     * @param \Illuminate\Database\Eloquent\Model $product
     * @param \Illuminate\Http\Request $request
     */
    public function handlePhotos($product, Request $request, string $path = 'product')
    {
        if ($request->hasFile('photos')) {
            $photos = $request->file('photos');
            foreach ($photos as $index => $photo) {
                $isPrimary = $index === 0 && !$product->photos()->exists();
                $this->storePhoto($product, $photo, $path, $isPrimary);
            }
        }
    }

    /**
     * Обновить основное фото продукта.
     *
     * @param \Illuminate\Database\Eloquent\Model $product
     * @param UploadedFile $newPhoto
     */
    public function updatePrimaryPhoto($product, $newPhoto, string $path = 'product')
    {
        $product->photos()->update(['is_primary' => false]);

        $this->storePhoto($product, $newPhoto, $path, true);
    }

    /**
     * Удалить все фотографии продукта.
     *
     * @param \Illuminate\Database\Eloquent\Model $product
     */
    public function deletePhotos($product)
    {
        foreach ($product->photos as $photo) {
            Storage::disk('s3')->delete($photo->url);
            $photo->delete();
        }
    }

    /**
     * Сохранить фотографию.
     *
     * @param \Illuminate\Database\Eloquent\Model $product
     * @param UploadedFile $photo
     * @param bool $isPrimary
     */
    private function storePhoto($product, $photo, string $path, bool $isPrimary = false)
    {
        if ($photo instanceof UploadedFile) {
            $manager = ImageManager::gd();
            $image = $manager->read($photo->getPathname());

            $image->scale((int)env('PHOTO_WIDTH', 600));
            $imageData = $image->encode(new AutoEncoder(quality: (int)env('PHOTO_QUALITY', 100)));

            $filename = "$path/" . uniqid() . '.jpg';

            try {
                Storage::disk('s3')->put($filename, $imageData);
            } catch (Exception $e) {
                throw new Exception('Ошибка при загрузке на S3: ' . $e->getMessage());
            }

            $product->photos()->create([
                'url' => $filename,
                'is_primary' => $isPrimary,
            ]);
        } else {
            throw new \Exception('Невалидный файл');
        }
    }
}
