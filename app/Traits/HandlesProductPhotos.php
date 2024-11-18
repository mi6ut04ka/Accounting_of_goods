<?php
namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait HandlesProductPhotos
{
    /**
     * Обработать загрузку фотографий для продукта.
     *
     * @param \Illuminate\Database\Eloquent\Model $product
     * @param \Illuminate\Http\Request $request
     */
    public function handlePhotos($product, Request $request)
    {
        if ($request->hasFile('photo')) {
                $this->storePhoto($product, $request->file('photo'));
        }
    }

    /**
     * Изменить фотографию продукта: удалить старую и добавить новую.
     *
     * @param \Illuminate\Database\Eloquent\Model $product
     * @param UploadedFile $newPhoto
     */
    public function updatePhoto($product, $newPhoto)
    {
        // Удалить старую фотографию
        $this->deletePhotos($product);

        // Сохранить новую фотографию
        $this->storePhoto($product, $newPhoto);
    }

    /**
     * Удалить все фотографии продукта.
     *
     * @param \Illuminate\Database\Eloquent\Model $product
     */
    public function deletePhotos($product)
    {
        foreach ($product->photos as $photo) {
            Storage::disk('public')->delete($photo->url);
            $photo->delete();
        }
    }

    /**
     * Сохранить фотографию.
     *
     * @param \Illuminate\Database\Eloquent\Model $product
     * @param UploadedFile $photo
     */
    private function storePhoto($product, $photo)
    {
        if ($photo instanceof UploadedFile) {
            $filename = 'products/' . uniqid() . '.' . $photo->getClientOriginalExtension();

            Storage::disk('public')->put($filename, file_get_contents($photo));

            $product->photos()->create([
                'url' => $filename,
            ]);
        } else {
            throw new \Exception('Невалидный файл');
        }
    }
}
