<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;


trait UploadImage
{

    function uploadImage($request, $folder, $image=null)
    {

        if ($request->hasFile('img'))
        {
            if (File::exists(str_replace(env('APP_URL'), "", $image))) {
                File::delete(str_replace(env('APP_URL'), "", $image));
            }
            $image = $request->file("img");
            $ext = strtolower($image->getClientOriginalExtension());
            $name = "$folder/" . time() . ".$ext";
            $image->move("$folder/", $name);
            return $name;
        }
        else{
            return null;
        }
    }
}


