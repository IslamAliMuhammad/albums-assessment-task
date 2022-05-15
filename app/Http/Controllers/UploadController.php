<?php

namespace App\Http\Controllers;

use App\Models\TemporaryPicture;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    //

    public function store(Request $request)
    {
        if($request->hasFile('picture')) {
            $file = $request->file('picture');
            $fileName = $file->getClientOriginalName();
            $randomfolderName = uniqid() . '-' . now()->timestamp;

            $file->storeAs('pictures/tmp/' . $randomfolderName, $fileName);

            TemporaryPicture::create([
                'folder' => $randomfolderName,
                'pictureName'=> $fileName
            ]);
            return $randomfolderName;
        }

        return '';

    }
}
