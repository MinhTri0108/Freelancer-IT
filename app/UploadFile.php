<?php

namespace App;

use Illuminate\Support\Facades\File;

class UploadFile
{
    // Upload file
    public function uploadFile($request, $input, $url)
    {
        $fileName = null;
        if ($request->hasFile($input)) {
            $originalFileName = $request->file($input)->getClientOriginalName();
            $extensionFileName = $request->file($input)->getClientOriginalExtension();
            $fileName = $this->createUniqueFileName($originalFileName, $extensionFileName, $url);
            $request->file($input)->move($url, $fileName);
        }
        return $fileName;
    }
    // Xoa file
    public function removeFile($originalFileName, $url)
    {
        if ($this->isExistedFile($originalFileName, $url)) File::delete($url . '/' . $originalFileName);
    }

    // Lay filename cua file khong chua phan dinh dang file
    private function getFileNamewithoutExtension($originalFileName, $extensionFileName)
    {
        return substr($originalFileName, 0, strlen($originalFileName) - (strlen($extensionFileName) + 1));
    }
    //Kiem tra file ton tai hay khong
    private function isExistedFile($originalFileName, $url)
    {
        return File::exists($url . "/" . $originalFileName);
    }
    //Tao ra file name khong trung voi cac file khac
    private function createUniqueFileName($originalFileName, $extensionFileName, $url)
    {
        $fileName = '';
        if ($this->isExistedFile($originalFileName, $url)) {
            $fileNamewithoutExtension = $this->getFileNamewithoutExtension($originalFileName, $extensionFileName);
            $fileName = $fileNamewithoutExtension . uniqid('_') . '.' . $extensionFileName;
        } else $fileName = $originalFileName;
        return $fileName;
    }
}
