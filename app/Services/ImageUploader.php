<?php

namespace App\Services;

/**
 * Saves an uploaded image under public/<folder>.
 *
 * The file keeps the name sent by the client. A file with the same name is
 * overwritten (defect D-18, kept). A missing file (null) ends the request
 * with 500, as before (D-10, D-12, D-21).
 */
class ImageUploader
{
    /**
     * @param  \Illuminate\Http\UploadedFile|null  $file
     * @param  string  $folder  folder relative to public/, for example 'images/products'
     * @return string  the saved file name
     */
    public function store($file, $folder)
    {
        $name = $file->getClientOriginalName();
        $file->move(public_path($folder), $name);

        return $name;
    }
}
