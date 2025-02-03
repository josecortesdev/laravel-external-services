<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class S3Controller extends Controller
{
    public function showUploadForm()
    {
        return view('s3.upload');
    }

    public function uploadFile(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,png,pdf,docx|max:2048',
        ]);

        try {
            $file = $request->file('file');
            $filePath = 'uploads/' . $file->getClientOriginalName();

            // Subir el archivo a S3 con el nombre original y hacerlo público
            Storage::disk('s3')->put($filePath, file_get_contents($file));
            Storage::disk('s3')->setVisibility($filePath, 'public');

            $url = Storage::disk('s3')->url($filePath);

            return back()->with('success', 'File uploaded successfully')->with('file_url', $url);
        } catch (\Exception $e) {
            return back()->withErrors(['file' => 'Failed to upload file: ' . $e->getMessage()]);
        }
    }

    public function listFiles()
    {
        $originalFiles = Storage::disk('s3')->files('uploads');
        $files = [];

        foreach ($originalFiles as $file) {
            $files[] = [
                'name' => basename($file),
                'path' => $file,
                'url' => Storage::disk('s3')->url($file)
            ];
        }

        return view('s3.list', compact('files'));
    }

    public function deleteFile(Request $request)
    {
        $filePath = $request->input('file');

        try {
            Storage::disk('s3')->delete($filePath);
            return back()->with('success', 'File deleted successfully');
        } catch (\Exception $e) {
            return back()->withErrors(['file' => 'Failed to delete file: ' . $e->getMessage()]);
        }
    }
}
?>
