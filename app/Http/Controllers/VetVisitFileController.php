<?php

namespace App\Http\Controllers;

use App\Models\VetVisitFile;
use Illuminate\Support\Facades\Storage;

class VetVisitFileController extends Controller
{
    public function download(VetVisitFile $file)
    {
        return Storage::download($file->path, $file->original_name);
    }

    public function destroy(VetVisitFile $file)
    {
        Storage::delete($file->path);
        $file->delete();
        return back();
    }
}