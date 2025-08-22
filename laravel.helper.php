<?php

use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

if (!function_exists('mediaUploader')) {
    function mediaUploader($images)
    {
        $fileDetails = [];

        if (!is_array($images)) {
            $images = [$images];
        }

        foreach ($images as $image) {
            $ext = $image->getClientOriginalExtension();
            $filename = Carbon::now()->format('YmdHis') . '_' . rand(00000, 99999) . '.' . $ext;
            $result = Storage::disk('public')->putFileAs('images', $image, $filename);

            if ($result) {
                $size = $image->getSize();
                $fileDetails[] = [
                    'name' => $filename,
                    'extension' => $ext,
                    'size' => $size,
                ];
            }
        }

        return $fileDetails;
    }
}

if (!function_exists('mediaRemover')) {
    function mediaRemover($media)
    {
        if (!is_array($media)) {
            $filenames = explode(',', $media);
        } else {
            $filenames = $media;
        }

        foreach ($filenames as $filename) {
            $path = 'images/' . $filename;

            if (Storage::disk('public')->exists($path)) {
                $result = Storage::disk('public')->delete($path);
                
                if ($result) {
                    Log::info("Deleted file: $filename");
                } else {
                    Log::warning("Failed to delete file: $filename");
                }
            }
        }
    }
}

if (!function_exists('formatData')) {
    function formatData($code,$message,$data = null)
    {
        if($data)
        {
            return [
                    'code'    => $code,
                    'message' => $message,
                    'data'    => $data
            ];
        }else
        {
        
            if(App::environment('production'))
            {
              
                $message = "Something went wrong!";
            }

            return [
                    'code'    => $code,
                    'message' => $message,
            ];
        }
       
    }
}



if(!function_exists('getImagePath'))
{
    function getImagePath($name)
    {
        return asset('storage/images/' . $name);
    }
} 

// if(!function_exists('createOTP'))
// {
//     function createOTP()
//     {
//         do { $otp = rand(1000, 9999); } while (Otp::where('otp', $otp)->count());
    
//         return $otp;
//     }
// } 


// if(!function_exists('getDistanceInKm'))
// {
//     function getDistanceInKm($lat1, $lon1, $lat2, $lon2) {
//         $earthRadius = 6371; 

//         $lat1 = deg2rad($lat1);
//         $lon1 = deg2rad($lon1);
//         $lat2 = deg2rad($lat2);
//         $lon2 = deg2rad($lon2);

//         $dLat = $lat2 - $lat1;
//         $dLon = $lon2 - $lon1;

//         $a = sin($dLat / 2) * sin($dLat / 2) +
//             cos($lat1) * cos($lat2) *
//             sin($dLon / 2) * sin($dLon / 2);

//         $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

//         $distance = $earthRadius * $c;

//         return $distance;
//     }
// } 

