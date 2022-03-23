<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class UploadFileController extends Controller
{

    public function upload_image(Request $request)
    {
        $image = $request->file('image');
        $hash_name = $image->hashName();
        Storage::disk('public')->putFileAs('images', $image, $hash_name);

        $url = Storage::url('images/'.$hash_name);
        return \response()->json([
            'success'=> true,
            'url'=>$url
        ]);
    }
}
