<?php

namespace App\Http\Controllers\Archive;

use App\Http\Controllers\Controller;
use App\Models\TAju;
use App\Models\TArchive;
use App\Models\MDepartment;
use App\Models\MSubdepartment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DocumentController extends Controller
{
    public function index(Request $request)
    {

        $search = $request->input('search');
        $perPage = $request->input('per_page', 5);

        // Query untuk TArchive dengan relasi ajuDetails dan aju
        $archives = TArchive::with(['ajuDetails', 'aju'])
            ->where('active_y_n', 'Y');

        // Jika ada pencarian
        if ($search) {
            $archives->where(function ($query) use ($search) {
                $query->whereRaw('LOWER(doc_type) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(description) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(file_name) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereHas('aju', function ($query) use ($search) {
                        $query->whereRaw('LOWER(no_docs) LIKE ?', ['%' . strtolower($search) . '%']);
                    });
            });
        }

        $archives = $archives->paginate($perPage);

        $deps = MDepartment::getDepartments();
        $subDeps = MDepartment::getSubDepartments();

        return view('pages.archive.document.index_list', compact('archives', 'deps', 'subDeps', 'perPage'));
    }
    // NEW
    public function indexNewDocument(Request $request)
    {

        $search = $request->input('search');
        $perPage = $request->input('per_page', 5);

        // Query untuk TArchive dengan relasi ajuDetails dan aju
        $archives = TArchive::with(['ajuDetails', 'aju'])
            ->where('active_y_n', 'Y');

        // Jika ada pencarian
        if ($search) {
            $archives->where(function ($query) use ($search) {
                $query->whereRaw('LOWER(doc_type) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(description) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(file_name) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereHas('aju', function ($query) use ($search) {
                        $query->whereRaw('LOWER(no_docs) LIKE ?', ['%' . strtolower($search) . '%']);
                    });
            });
        }

        $archives = $archives->paginate($perPage);

        $deps = MDepartment::getDepartments();
        $subDeps = MDepartment::getSubDepartments();

        return view('pages.archive.document.index_new', compact('archives', 'deps', 'subDeps', 'perPage'));
    }

    public function checkDocumentNumber(Request $request)
    {
        $documentNumber = $request->query('id_aju');
        $exists = TArchive::where('no_archive', $documentNumber)->exists();

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

        $lastDocument = TArchive::orderBy('idrec', 'desc')->first();
        $lastNumber = $lastDocument ? intval(substr($lastDocument->no_archive, -3)) : 0;

        $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        $suggestedDocumentNumber = 'DOCS-' . $year . '-' . $monthDay . '-' . $newNumber;

        return response()->json(['suggested_id_document' => $suggestedDocumentNumber]);
    }

    public function indexForm($id_aju)
    {

        $archive = TArchive::where('id_aju', $id_aju)
            ->where('active_y_n', 'Y')
            ->get();
        $ajuDocs = TAju::where('id_aju', $id_aju)->first();


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

            return redirect()->back()->with('success', 'TArchive added successfully!');
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

        // Query untuk TArchive dengan relasi ajuDetails dan aju
        $archives = TArchive::with(['ajuDetails', 'aju'])
            ->where('active_y_n', 'Y');

        // Jika ada pencarian
        if ($search) {
            $archives->where(function ($query) use ($search) {
                $query->whereRaw('LOWER(doc_type) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(description) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(file_name) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereHas('aju', function ($query) use ($search) {
                        $query->whereRaw('LOWER(no_docs) LIKE ?', ['%' . strtolower($search) . '%']);
                    });
            });
        }

        $archives = $archives->paginate($perPage);

        $deps = MDepartment::getDepartments();
        $subDeps = MDepartment::getSubDepartments();


        return view('pages.archive.document.index_edit', compact('archives', 'deps', 'subDeps', 'perPage'));
    }

    public function indexFormEdit($id_aju)
    {
        $archive = TArchive::where('id_aju', $id_aju)
            ->where('active_y_n', 'Y')
            ->get();

        $ajuDocs = TAju::where('id_aju', $id_aju)->first();

        return view('pages.archive.document.input.edit', compact('archive', 'ajuDocs'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'date' => 'required|date',
            'type_docs_modal' => 'required|string',
            'description' => 'nullable|string',
            'files' => 'nullable|array',
            'files.*' => 'file|mimes:pdf,jpg,jpeg|max:25000', // 25MB max
        ]);

        $archive = TArchive::findOrFail($id);

        // Update the archive data
        $archive->date = $validatedData['date'];
        $archive->doc_type = $validatedData['type_docs_modal'];
        $archive->description = $validatedData['description'];

        // Handle file upload if a new file is provided
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                // Convert file to base64
                $filePath = $file->store('uploads', 'public');
                $fileContent = Storage::disk('public')->get($filePath);
                $base64 = base64_encode($fileContent);

                // Save base64 string to the database
                $archive->pdf_jpg = $base64;
                $archive->file_name = $file->getClientOriginalName();
            }
        }

        $archive->save();

        return redirect()->route('index.editDocument')->with('success', 'Archive updated successfully!');
    }



    // DELETE
    public function indexDeleteDocument(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 5);

        // Query untuk TArchive dengan relasi ajuDetails dan aju
        $archives = TArchive::with(['ajuDetails', 'aju'])
            ->where('active_y_n', 'Y');

        // Jika ada pencarian
        if ($search) {
            $archives->where(function ($query) use ($search) {
                $query->whereRaw('LOWER(doc_type) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(description) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(file_name) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereHas('aju', function ($query) use ($search) {
                        $query->whereRaw('LOWER(no_docs) LIKE ?', ['%' . strtolower($search) . '%']);
                    });
            });
        }

        $archives = $archives->paginate($perPage);

        $deps = MDepartment::getDepartments();
        $subDeps = MDepartment::getSubDepartments();


        return view('pages.archive.document.index_delete', compact('archives', 'deps', 'subDeps', 'perPage'));
    }

    public function indexFormDelete($id)
    {

        $archive = TArchive::findOrFail($id);
        $archive->update(['active_y_n' => 'N']);
        $archive->updated_by = Auth::id();
        $archive->updated_at = now();

        return redirect()->route('index.DeleteDocument')
            ->with('success', 'Document has been deleted successfully.');
    }
}
