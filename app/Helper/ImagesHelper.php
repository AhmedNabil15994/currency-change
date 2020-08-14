<?php

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;

class ImagesHelper {

    static function GetImagePath($strAction, $id, $filename) {

        $default = asset('/assets/images/not-available.jpg');

        if($filename == '') {
            return $default;
        }

        // $path = Config::get('app.IMAGE_BASE').'public/';
        $path = Config::get('app.IMAGE_BASE');

        $checkFile = public_path() . '/uploads';
        $checkFile = str_replace('frontend', 'engine', $checkFile);

        switch ($strAction) {
            case "users":
                $fullPath = $path . 'uploads' . '/users/' . $id . '/' . $filename;
                $checkFile = $checkFile . '/users/' . $id . '/' . $filename;
                return is_file($checkFile) ? $fullPath : $default;
                break;
            case "shops":
                $fullPath = $path . 'uploads' . '/shops/' . $id . '/' . $filename;
                $checkFile = $checkFile . '/shops/' . $id . '/' . $filename;
                return is_file($checkFile) ? $fullPath : $default;
                break;
            case "products":
                $fullPath = $path . 'uploads' . '/products/' . $id . '/' . $filename;
                $checkFile = $checkFile . '/products/' . $id . '/' . $filename;
                return is_file($checkFile) ? $fullPath : $default;
                break;
        }

        return $default;
    }

    static function uploadImage($strAction, $fieldInput, $id, $customPath = '', $inputFile = false) {

        if ($fieldInput == '') {
            return false;
        }

        if (is_object($fieldInput)) {
            $fileObj = $fieldInput;
        } else {
            if (!Input::hasFile($fieldInput)) {
                return false;
            }
            $fileObj = Input::file($fieldInput);
        }


        if ($fileObj->getClientSize() >= 20000000) {
            return false;
        }

        if ($inputFile == false) {
            $extensionExplode = explode('/', $fileObj->getMimeType()); // getting image extension
            unset($extensionExplode[0]);
            $extensionExplode = array_values($extensionExplode);
            $extension = $extensionExplode[0];
        } else {
            $extension = $fileObj->getClientOriginalExtension();
        }

        if (!in_array($extension, ['ppt','pptx','pdf','docx','doc','dotx','dot','dox','ppv','xlsx','xlsm','xml','txt','csv','xlc','jpg', 'jpeg', 'JPG', 'JPEG', 'png', 'PNG', 'gif', 'GIF'])) {
            return false;
        }

        $rand = rand() . date("YmdhisA");
        $fileName = 'melook' . '-' . $rand;
        $directory = '';

        $path = public_path() . '/uploads/';
        $path = str_replace('frontend', 'engine', $path);

        if ($strAction == 'users') {
            $directory = $path . 'users/' . $id;
        }

        if ($strAction == 'shops') {
            $directory = $path . 'shops/' . $id;
        }

        if ($strAction == 'products') {
            $directory = $path . 'products/' . $id;
        }

        $fileName_full = $fileName . '.' . $extension;

        if ($directory == '') {
            return false;
        }

        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        if ($fileObj->move($directory, $fileName_full)){
            return $fileName_full;
        }

        return false;
    }

    static function deleteDirectory($dir) {
        system('rm -r ' . escapeshellarg($dir), $retval);
        return $retval == 0; // UNIX commands return zero on success
    }

}
