<?php

namespace App\Http\Controllers\Archive;

use App\Http\Controllers\Controller;
use App\Models\Aju;
use App\Models\Archive;
use App\Models\MDepartment;
use App\Models\MSubdepartment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AjuController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 5);
        $archives = Archive::with('department')->where('active_y_n', 'y');

        if ($search) {
            $archives->where(function ($query) use ($search) {
                $query->whereRaw('LOWER(no_docs) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(tipe_docs) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereHas('department', function ($query) use ($search) {
                        $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($search) . '%']);
                    });
            });
        }
        $archives = $archives->paginate($perPage);

        $deps = MDepartment::all();
        $subDeps = MSubdepartment::all();

        return view('pages.archive.aju.index_list', compact('archives', 'deps', 'subDeps', 'perPage'));
    }

    public function indexNewAju(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 5);
        $archives = Archive::with('department')->where('active_y_n', 'y');

        if ($search) {
            $archives->where(function ($query) use ($search) {
                $query->whereRaw('LOWER(no_docs) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(tipe_docs) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereHas('department', function ($query) use ($search) {
                        $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($search) . '%']);
                    });
            });
        }

        $archives = $archives->paginate($perPage);

        $deps = MDepartment::all();
        $subDeps = MSubdepartment::all();

        return view('pages.archive.aju.index_new', compact('archives', 'deps', 'subDeps', 'perPage'));
    }

    public function indexEditAju(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 5);
        $archives = Archive::with('department')->where('active_y_n', 'y');

        if ($search) {
            $archives->where(function ($query) use ($search) {
                $query->whereRaw('LOWER(no_docs) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(tipe_docs) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereHas('department', function ($query) use ($search) {
                        $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($search) . '%']);
                    });
            });
        }

        $archives = $archives->paginate($perPage);

        $deps = MDepartment::all();
        $subDeps = MSubdepartment::all();

        return view('pages.archive.aju.index_edit', compact('archives', 'deps', 'subDeps', 'perPage'));
    }

    public function indexDeleteAju(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 5);
        $archives = Archive::with('department')->where('active_y_n', 'y');

        if ($search) {
            $archives->where(function ($query) use ($search) {
                $query->whereRaw('LOWER(no_docs) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(tipe_docs) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereHas('department', function ($query) use ($search) {
                        $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($search) . '%']);
                    });
            });
        }

        $archives = $archives->paginate($perPage);

        $deps = MDepartment::all();
        $subDeps = MSubdepartment::all();

        return view('pages.archive.aju.index_delete', compact('archives', 'deps', 'subDeps', 'perPage'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'id_archive' => 'required|exists:t_archive,id_archive',
            'date' => 'required|date',
            'id_aju' => 'required|string|max:50',
            'created_by' => 'required|exists:users,id',
            'files.*' => 'required|file|mimes:pdf|max:25600',
        ]);

        try {
            DB::beginTransaction();
            foreach ($request->file('files') as $file) {
                $encodedFile = base64_encode(file_get_contents($file->getRealPath()));
                DB::table('t_aju')->insert([
                    'date' => $request->date,
                    'no_aju' => $request->id_aju,
                    'id_archive' => $request->id_archive,
                    'pdf_jpg' => $encodedFile,
                    'active_y_n' => 'Y',
                    'created_by' => $request->created_by,
                    'updated_by' => $request->created_by,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();

            return redirect()->route('index.newaju')->with('success', 'Dokumen berhasil diunggah!');
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to upload files: ' . $e->getMessage()], 500);
        }
    }
}
