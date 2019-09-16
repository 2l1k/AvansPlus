<?php

namespace App\Helpers;

use Image;
use File;
use Illuminate\Support\Facades\Storage;

class FileHelper
{

    /**
     * Uploading documents
     *
     * @param $file
     * @param string $folder
     * @return string
     */
    public static function uploadFile($file, $folder = "")
    {
        $images_path = "images/uploads" . $folder;
        if ($file) {
            $image_name = time() . rand(1, 10000) . "_" . md5($file->getClientOriginalName()) . "." . File::extension($file->getClientOriginalName());
            $file->move(storage_path($images_path), $image_name);

            return $images_path . "/" . $image_name;
        }
        return null;
    }

    /**
     * Deleting documents
     *
     * @param $file_path
     * @return bool
     */
    public static function deleteFile($file_path)
    {
        if (!empty($file_path) && file_exists(storage_path($file_path))) {
            return unlink(storage_path($file_path));
        }
    }

}
