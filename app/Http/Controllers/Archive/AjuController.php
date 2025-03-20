<?php

namespace App\Http\Controllers\Archive;

use App\Http\Controllers\Controller;
use App\Models\Aju;
use App\Models\Archive;
use App\Models\MDepartment;
use App\Models\MSubdepartment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AjuController extends Controller
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

        return view('pages.archive.aju.index_list', compact('ajus', 'deps', 'subDeps', 'perPage'));
    }


    // NEW
    public function indexNewAju(Request $request)
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

        return view('pages.archive.aju.index_new', compact('ajus', 'deps', 'subDeps', 'perPage'));
    }

    public function getSubDepartments($departmentId)
    {
        $subDepartments = MSubDepartment::where('p_id_dept', $departmentId)->get();
        return response()->json($subDepartments);
    }

    public function checkAjuNumber(Request $request)
    {
        $ajuNumber = $request->query('id_aju');
        $exists = Aju::where('no_docs', $ajuNumber)->exists();

        return response()->json(['exists' => $exists]);
    }

    public function suggestAjuNumber(Request $request)
    {

        $date = $request->input('date');
        // dd($date);
        if (!$date) {
            $date = now()->format('Y-m-d');
        }

        $year = date('Y', strtotime($date));
        $monthDay = date('md', strtotime($date));

        $lastAju = Aju::orderBy('id_aju', 'desc')->first();
        $lastNumber = $lastAju ? intval(substr($lastAju->no_docs, -3)) : 0;

        $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        $suggestedAjuNumber = 'AJU-' . $year . '-' . $monthDay . '-' . $newNumber;

        return response()->json(['suggested_id_aju' => $suggestedAjuNumber]);
    }

    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'id_aju' => 'required|string|max:255',
            'dep' => 'required|exists:m_department,id',
            'type_docs_modal' => 'required|string|max:255',
            'description' => 'required|string|max:255',
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
            DB::table('t_aju')->insert([
                'date' => $request->input('date'),
                'id_department' => $request->input('dep'),
                'tipe_docs' => $request->input('type_docs_modal'),
                'no_docs' => $request->input('id_aju'),
                'description' => $request->input('description'),
                'pdf_jpg' => $pdfJpgData,
                'file_name' => implode(',', $fileNames),
                'active_y_n' => 'Y', // Atau 'N' sesuai kebutuhan
                'created_by' => Auth::id(), // Jika Anda menggunakan autentikasi
                'updated_by' => Auth::id(), // Jika Anda menggunakan autentikasi
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
    public function indexEditAju(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 5);

        // Ambil data dari model Aju dengan relasi ke Archive & Department
        $ajus = Aju::with(['archives', 'department'])
            ->where('active_y_n', 'Y')
            ->where('pdf_jpg', '!=', '');

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

        return view('pages.archive.aju.index_edit', compact('ajus', 'deps', 'subDeps', 'perPage'));
    }

    public function update(Request $request, $id_aju)
    {
        
        // Validasi input
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'id_aju' => 'required|string|max:255',
            'dep' => 'required|exists:m_department,id',
            'type_docs_modal' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'files' => 'nullable|array',
            'files.*' => 'file|mimes:pdf,jpg,jpeg|max:25000', // Maksimal 25MB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            // Ambil data yang akan diperbarui
            $aju = DB::table('t_aju')->where('id_aju', $id_aju)->first();

            if (!$aju) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data not found!'
                ], 404);
            }

            // Inisialisasi variabel untuk file baru
            $fileNames = [];
            $base64Data = [];

            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    // Mendapatkan nama file
                    $fileName = $file->getClientOriginalName();
                    $fileNames[] = $fileName;

                    // Mengubah file ke Base64
                    $fileData = file_get_contents($file->getRealPath());
                    $base64EncodedData = base64_encode($fileData);
                    $base64Data[] = $base64EncodedData;
                }
            }

            // Menyiapkan data untuk update
            $updateData = [
                'date' => $request->input('date'),
                'id_department' => $request->input('dep'),
                'tipe_docs' => $request->input('type_docs_modal'),
                'no_docs' => $request->input('id_aju'),
                'description' => $request->input('description'),
                'updated_by' => Auth::id(), // Jika menggunakan autentikasi
                'updated_at' => now(),
            ];

            // Jika ada file baru, update kolom `pdf_jpg` dan `file_name`
            if (!empty($fileNames) && !empty($base64Data)) {
                $updateData['pdf_jpg'] = implode(',', $base64Data);
                $updateData['file_name'] = implode(',', $fileNames);
            }

            // Eksekusi update
            DB::table('t_aju')->where('id_aju', $id_aju)->update($updateData);

            return redirect()->back()->with('success', 'Archive updated successfully!');
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update document: ' . $e->getMessage()
            ], 500);
        }
    }


    // DELETE
    public function indexDeleteAju(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 5);

        // Ambil data dari model Aju dengan relasi ke Archive & Department
        $ajus = Aju::with(['archives', 'department'])
            ->where('active_y_n', 'Y')
            ->where('pdf_jpg', '!=', '');
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

        return view('pages.archive.aju.index_delete', compact('ajus', 'deps', 'subDeps', 'perPage'));
    }

    public function softDelete($id)
    {
        $aju = Aju::findOrFail($id);
        $aju->update(['active_y_n' => 'n']);

        return redirect()->route('index.deleteaju')->with('success', 'Deleted successfully.');
    }
}
