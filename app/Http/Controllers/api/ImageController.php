<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Image;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class ImageController extends Controller
{
    /**
     *  Upload Image to Cloudinary
     */
   
 function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'filename' => 'required|image|mimes:jpeg,png,jpg,gif|max:7048',
            
        ], [

            'filename' => [

                'required' => 'The image field is required.',
                'image' => 'The file must be an image.',
                'mimes' => 'Only jpeg, png, jpg, and gif formats are allowed.',
                'max' => 'The image size must not exceed 2MB.',
                'url' => 'The image URL must be a valid URL.',
                'required' => 'The image alt text is required.',
                'tring' => 'The image alt text must be a string.',
                'max' => 'The image alt text must not exceed 255 characters.',

            ],
           
        ]);


        // dd($request->all());


        if ($validator->fails()) {
            $errors = $validator->errors();

            return response()->json([
                'status'=>'validation erorrs',
                 'errors' => $validator->errors()
            ]);
        }

        if ($request->hasFile('filename')) {
            $ext = $request->filename->extension();

            $image_name = 'logo' . '-' . Carbon::now()->format('d-m-y-h-m-s') . '.' . $ext;

            $request->filename->storeAS('asset/image', $image_name, 'public');
        }

    $file = $request->filename;
    $sizeInBytes = $file->getSize();
    $sizeInKB = round($sizeInBytes / 1024, 2);


        $image = new Image();
        $image->filename = $image_name;
        $image->User_id = Auth::id();
       
        $image->mimetype = $ext;
        $image->size_in_kb = $sizeInKB;

        $image->save();

        return response()->json([
            // 'status'=>true,
        'message'=>'file  added successfully',
        'filename'=>$image,
        ]);
    }


    /**
     *  Get all images with total count and total size in GB
     */
    public function index()
    {
        $totalItems   = Image::count();
        $totalSizeKB  = Image::sum('size_in_kb');
        $totalSizeGB  = number_format($totalSizeKB / 1024 / 1024, 8); // Convert KB to GB

        // Return image 
        return response()->json([
            'category'    => 'Image',
            'total_items' => $totalItems,
            'storage'     => $totalSizeGB . ' GB',
        ]);
    }

    
    
            //    all image upload
   public function all()
    {
        $images = Image::latest()->get();
        return response()->json(['images' => $images]);
    }

}
