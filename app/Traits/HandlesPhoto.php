<?php
namespace App\Traits;

use App\Models\Raw;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Encoders\AutoEncoder;
use Intervention\Image\ImageManager;

trait HandlesPhoto
{
    public function handlePhoto(Model $model, Request $request, string $path = 'product'): void
    {
        if ($request->hasFile('photo')) {
                $this->storePhoto($model, $request->file('photo'), $path);
        }
    }

    public function updatePhoto(Model $model, $newPhoto, string $path = 'product'): void
    {
        $this->deletePhoto($model);

        $this->storePhoto($model, $newPhoto, $path);
    }

    public function deletePhoto(Model $model): void
    {
            if($model->photo && $model->photo->url){
                Storage::disk('public')->delete($model->photo->url);
                $model->photo->delete();
            }
    }

    /**
     * @throws Exception
     */
    private function storePhoto(Model $model, $photo, string $path): void
    {
        if ($photo instanceof UploadedFile) {
            $manager = ImageManager::gd();
            $image = $manager->read($photo->getPathname());

            $image->scale(env('PHOTO_WIDTH', 600));

            $imageData = $image->encode(new AutoEncoder(quality: env('PHOTO_QUALITY', 100)));

            $filename = "$path/" . uniqid() . '.jpg';

            Storage::disk('public')->put($filename, $imageData);

            $model->photo()->create([
                'url' => $filename,
            ]);
        } else {
            throw new Exception('Невалидный файл');
        }
    }
}
