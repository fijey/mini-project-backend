<?php

namespace App\Services;


class ImageService
{
    public function uploadImage($request)
    {
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);


            return $imageName;
        }
    }
}
