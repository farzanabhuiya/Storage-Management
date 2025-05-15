<?php

namespace App\Http\Controllers\Api;

use App\Models\Pdf;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PdfController extends Controller
{
    /**
     * ✅ Upload PDF to public storage
     */
    public function store(Request $request)
    {
        $request->validate([
            'pdf' => 'required|mimes:pdf|max:10240', // max 10MB
        ]);

        $file = $request->file('pdf');

        // ✅Store on public disk
         $pdfPath = $file->store('pdfs', 'public');
         $sizeInBytes = $file->getSize();
         $sizeInKB = round($sizeInBytes / 1024, 2);

        $pdf = Pdf::create([
            'user_id'     => Auth::id(),
            'path'        => $pdfPath,
            'filename'    => $file->getClientOriginalName(),
            'mimetype'    => $file->getClientMimeType(),
            'size_in_kb'  => ceil($file->getSize() / 1024),
        ]);

        return response()->json([
            'message' => 'PDF uploaded successfully',
            'pdf' => $pdf,
        ], 201);
    }




    
    /**
     * ✅ Get PDF stats (count and total size in GB)
     */
    public function index()
    {
        $totalItems  = Pdf::count();
        $totalSizeKB = Pdf::sum('size_in_kb');
        $totalSizeGB  = number_format($totalSizeKB / 1024 / 1024, 8); // Convert KB to GB

        return response()->json([
            'category'    => 'PDF',
            'total_items' => $totalItems,
            'storage'     => $totalSizeGB . ' GB',
        ]);
    }





    /**
     * ✅Get all uploaded PDFs
     */
    public function all()
    {
        $pdfs = Pdf::latest()->get();

        return response()->json([
            'pdfs' => $pdfs,
        ]);
    }
}
