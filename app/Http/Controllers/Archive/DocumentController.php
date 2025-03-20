<?php

namespace App\Http\Controllers\Archive;

use App\Http\Controllers\Controller;
use App\Models\Aju;
use App\Models\Archive;
use App\Models\MDepartment;
use App\Models\MSubdepartment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DocumentController extends Controller
{
    public function index(Request $request)
    {

        $search = $request->input('search');
        $perPage = $request->input('per_page', 5);

        // Ambil data dari model Aju dengan relasi ke Archive & Department
        $ajus = Aju::with(['archives', 'department'])
            ->where('active_y_n', 'Y');

        // Jika ada pencarian
        if ($search) {
            $ajus->where(function ($query) use ($search) {
                $query->whereRaw('LOWER(no_docs) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(tipe_docs) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereHas('department', function ($query) use ($search) {
                        $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($search) . '%']);
                    });
            });
        }

        $ajus = $ajus->paginate($perPage);

        $deps = MDepartment::all();
        $subDeps = MSubdepartment::all();

        return view('pages.archive.document.index_list', compact('ajus', 'deps', 'subDeps', 'perPage'));
    }
    // NEW
    public function indexNewDocument(Request $request)
    {

        $search = $request->input('search');
        $perPage = $request->input('per_page', 5);

        // Ambil data dari model Aju dengan relasi ke Archive & Department
        $ajus = Aju::with(['archives', 'department'])
            ->where('active_y_n', 'Y');

        // Jika ada pencarian
        if ($search) {
            $ajus->where(function ($query) use ($search) {
                $query->whereRaw('LOWER(no_docs) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(tipe_docs) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereHas('department', function ($query) use ($search) {
                        $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($search) . '%']);
                    });
            });
        }

        $ajus = $ajus->paginate($perPage);

        $deps = MDepartment::all();
        $subDeps = MSubdepartment::all();

        return view('pages.archive.document.index_new', compact('ajus', 'deps', 'subDeps', 'perPage'));
    }

    public function checkDocumentNumber(Request $request)
    {
        $documentNumber = $request->query('id_aju');
        $exists = Archive::where('no_archive', $documentNumber)->exists();

        return response()->json(['exists' => $exists]);
    }

    public function suggestDocumentNumber(Request $request)
    {

        $date = $request->input('date');
        if (!$date) {
            $date = now()->format('Y-m-d');
        }

        $year = date('Y', strtotime($date));
        $monthDay = date('md', strtotime($date));

        $lastDocument = Archive::orderBy('idrec', 'desc')->first();
        $lastNumber = $lastDocument ? intval(substr($lastDocument->no_archive, -3)) : 0;

        $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        $suggestedDocumentNumber = 'DOCS-' . $year . '-' . $monthDay . '-' . $newNumber;

        return response()->json(['suggested_id_document' => $suggestedDocumentNumber]);
    }

    public function indexForm($id_aju)
    {

        $archive = Archive::where('id_aju', $id_aju)
            ->where('active_y_n', 'Y')
            ->get();
        $ajuDocs = Aju::where('id_aju', $id_aju)->first();


        return view('pages.archive.document.input.form', compact('archive', 'ajuDocs'));
    }

    public function store(Request $request)
    {

        // Validasi input
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'id_aju' => 'required|string|max:255',
            'files' => 'required|array',
            'files.*' => 'file|mimes:pdf,jpg,jpeg|max:25000', // 25MB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        // Mengambil file dari request
        $files = $request->file('files');
        $fileNames = [];
        $base64Data = [];

        foreach ($files as $file) {
            // Mendapatkan nama file
            $fileName = $file->getClientOriginalName();
            $fileNames[] = $fileName;

            // Membaca file sebagai string dan mengubahnya menjadi Base64
            $fileData = file_get_contents($file->getRealPath());
            $base64EncodedData = base64_encode($fileData);
            $base64Data[] = $base64EncodedData;
        }

        $pdfJpgData = implode(',', $base64Data);

        // Menyimpan data ke database
        try {
            DB::table('t_archive')->insert([
                'date' => $request->input('date'),
                'id_aju' => $request->input('id_aju'),
                'no_archive' => $request->input('no_archive'),
                'pdf_jpg' => $pdfJpgData,
                'file_name' => implode(',', $fileNames),
                'active_y_n' => 'Y',
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->back()->with('success', 'Archive added successfully!');
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to upload document: ' . $e->getMessage()
            ], 500);
        }
    }

    // EDIT
    public function indexEditDocument(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 5);

        // Ambil data dari model Aju dengan relasi ke Archive & Department
        $ajus = Aju::with(['archives', 'department'])
            ->where('active_y_n', 'Y')
            ->whereHas('archives', function ($query) {
                $query->whereNotNull('pdf_jpg') // Hanya ambil data jika pdf_jpg tidak kosong
                    ->where('pdf_jpg', '!=', ''); // Menghindari nilai kosong
            });

        // Jika ada pencarian
        if ($search) {
            $ajus->where(function ($query) use ($search) {
                $query->whereRaw('LOWER(no_docs) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(tipe_docs) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereHas('department', function ($query) use ($search) {
                        $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($search) . '%']);
                    });
            });
        }

        $ajus = $ajus->paginate($perPage);

        $deps = MDepartment::all();
        $subDeps = MSubdepartment::all();

        return view('pages.archive.document.index_edit', compact('ajus', 'deps', 'subDeps', 'perPage'));
    }

    public function indexFormEdit($id_aju)
    {
        $archive = Archive::where('id_aju', $id_aju)
            ->where('active_y_n', 'Y')
            ->get();

        $ajuDocs = Aju::where('id_aju', $id_aju)->first();

        return view('pages.archive.document.input.edit', compact('archive', 'ajuDocs'));
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'id_aju' => 'required|string|max:255',
            'files' => 'nullable|array',
            'files.*' => 'file|mimes:pdf,jpg,jpeg|max:25000', // 25MB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        // Ambil data arsip lama
        $archive = DB::table('t_archive')->where('idrec', $id)->first();
        if (!$archive) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data not found!'
            ], 404);
        }

        $fileNames = [];
        $base64Data = [];

        // Cek apakah ada file yang diunggah
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $fileName = $file->getClientOriginalName();
                $fileData = file_get_contents($file->getRealPath());
                $base64EncodedData = base64_encode($fileData);

                $fileNames[] = $fileName;
                $base64Data[] = $base64EncodedData;
            }
        } else {
            // Gunakan data lama jika tidak ada file baru yang diunggah
            $fileNames = explode(',', $archive->file_name);
            $base64Data = explode(',', $archive->pdf_jpg);
        }

        // Gabungkan base64 jika ada lebih dari satu file
        $pdfJpgData = implode(',', $base64Data);

        // Update data di database
        try {
            DB::table('t_archive')
                ->where('idrec', $id)
                ->update([
                    'date' => $request->input('date'),
                    'id_aju' => $request->input('id_aju'),
                    'no_archive' => $request->input('no_archive'),
                    'pdf_jpg' => $pdfJpgData,
                    'file_name' => implode(',', $fileNames),
                    'active_y_n' => 'Y',
                    'updated_by' => Auth::id(),
                    'updated_at' => now(),
                ]);

            return redirect()->back()->with('success', 'Archive updated successfully!');
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update document: ' . $e->getMessage()
            ], 500);
        }
    }


    // DELETE
    public function indexDeleteDocument(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 5);

        $ajus = Aju::with(['archives', 'department'])
            ->where('active_y_n', 'Y')
            ->whereHas('archives', function ($query) {
                $query->whereNotNull('pdf_jpg')
                    ->where('pdf_jpg', '!=', '');
            });

        if ($search) {
            $ajus->where(function ($query) use ($search) {
                $query->whereRaw('LOWER(no_docs) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(tipe_docs) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereHas('department', function ($query) use ($search) {
                        $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($search) . '%']);
                    });
            });
        }

        $ajus = $ajus->paginate($perPage);

        $deps = MDepartment::all();
        $subDeps = MSubdepartment::all();

        return view('pages.archive.document.index_delete', compact('ajus', 'deps', 'subDeps', 'perPage'));
    }

    public function indexFormDelete($id_aju)
    {
        $archive = Archive::where('id_aju', $id_aju)
            ->where('active_y_n', 'Y')
            ->get();

        $ajuDocs = Aju::where('id_aju', $id_aju)->first();

        return view('pages.archive.document.input.delete', compact('archive', 'ajuDocs'));
    }

    public function destroy($id)
    {
        $document = Archive::find($id);


        if (!$document) {
            return response()->json(['message' => 'Document not found.'], 404);
        }


        $document->active_y_n = 'N';
        $document->updated_by = Auth::id();
        $document->updated_at = now();
        $document->save();


        return redirect()->route('index.DeleteDocument')->with('success', 'Deleted successfully.');
    }
}
