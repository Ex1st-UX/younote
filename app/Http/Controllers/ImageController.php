<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

//TODO: вынести в отдельный класс (не контроллер)
class ImageController extends Controller
{
    const TASK_PREVIEW_SIZE = [150, 150];
    const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png'];
    const PREVIEW_DIR_PATH = 'public/previews/';

    public static function isImageValid($image): bool
    {
        $imageExt = $image->getClientOriginalExtension();
        return in_array($imageExt, self::ALLOWED_EXTENSIONS);
    }

    /**
     * Create preview image or return exist
     *
     * @param string $path
     * @param array $sizes
     * @return string
     */
    public static function getPreview(string $path, array $sizes): string
    {
        $arFile = pathinfo($path);
        $storagePath = storage_path('app/');
        $namePreview = $arFile['filename'] . '_' . $sizes[0] . 'x' . $sizes[1] . '.' . $arFile['extension'];
        $pathPreview = $storagePath . self::PREVIEW_DIR_PATH . $namePreview;

        if (file_exists($pathPreview)) {
            return 'previews/' . $namePreview;
        } else {
            return self::resize($path, $sizes);
        }
    }

    /*TODO отрефакторить пути, использова константы класса*/
    public static function resize(string $path, array $sizes): string
    {
        $storagePath = storage_path('app/');
        $image = \Image::make($storagePath . $path);

        $dirPath = $storagePath . self::PREVIEW_DIR_PATH;
        $extension = '.' . $image->extension;
        $fileName = $image->filename . '_' . $sizes[0] . 'x' . $sizes[1] . $extension;

        $image->resize($sizes[0], $sizes[1]);
        $image->save($dirPath . $fileName, 80);

        return 'previews/' . $fileName;
    }

    public static function handleImages($images): array
    {
        $arPathes = [];
        foreach ($images as $item) {
            if (ImageController::isImageValid($item)) {
                $path = $item->store('public/images');
                $previewPath = self::getPreview($path, self::TASK_PREVIEW_SIZE);
                $imageName = basename($path);

                if ($path) {
                    $arPathes[$imageName] = [
                        'picture' => $imageName,
                        'preview' => $previewPath
                    ];
                }
            }
        }

        return $arPathes;
    }
}
