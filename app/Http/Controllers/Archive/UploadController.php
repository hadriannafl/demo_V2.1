<?php

namespace App\Http\Controllers\Archive;

use App\Http\Controllers\Controller;
use App\Models\MDepartment;
use App\Models\MSubdepartment;
use App\Models\Archive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

use function Laravel\Prompts\alert;

class UploadController extends Controller
{
    public function index(Request $request)
    {

        $search = $request->input('search');
        $perPage = $request->input('per_page', 5); // Default to 5 items per page

        // Start the query
        $archives = Archive::with('department')->where('active_y_n', 'y');

        // Apply search if there is a search term
        if ($search) {
            $archives->where(function ($query) use ($search) {
                $query->whereRaw('LOWER(no_docs) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(tipe_docs) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereHas('department', function ($query) use ($search) {
                        $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($search) . '%']);
                    });
            });
        }

        // Paginate the results
        $archives = $archives->paginate($perPage);

        // Get departments and sub-departments for the view
        $deps = MDepartment::all();
        $subDeps = MSubdepartment::all();

        return view('pages.archive.document.index_list', compact('archives', 'deps', 'subDeps', 'perPage'));
    }

    public function indexNewArchive(Request $request)
    {
        // Get the search query from the request
        $search = $request->input('search');
        $perPage = $request->input('per_page', 5); // Default to 5 items per page

        // Start the query
        $archives = Archive::with('department')->where('active_y_n', 'y');

        // Apply search if there is a search term
        if ($search) {
            $archives->where(function ($query) use ($search) {
                $query->whereRaw('LOWER(no_docs) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(tipe_docs) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereHas('department', function ($query) use ($search) {
                        $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($search) . '%']);
                    });
            });
        }

        // Paginate the results
        $archives = $archives->paginate($perPage);

        // Get departments and sub-departments for the view
        $deps = MDepartment::all();
        $subDeps = MSubdepartment::all();

        return view('pages.archive.document.index_new', compact('archives', 'deps', 'subDeps', 'perPage'));
    }

    public function indexEditArchive(Request $request)
    {
        // Get the search query from the request
        $search = $request->input('search');
        $perPage = $request->input('per_page', 5); // Default to 5 items per page

        // Start the query
        $archives = Archive::with('department')->where('active_y_n', 'y');

        // Apply search if there is a search term
        if ($search) {
            $archives->where(function ($query) use ($search) {
                $query->whereRaw('LOWER(no_docs) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(tipe_docs) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereHas('department', function ($query) use ($search) {
                        $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($search) . '%']);
                    });
            });
        }

        // Paginate the results
        $archives = $archives->paginate($perPage);

        // Get departments and sub-departments for the view
        $deps = MDepartment::all();
        $subDeps = MSubdepartment::all();

        return view('pages.archive.document.index_edit', compact('archives', 'deps', 'subDeps', 'perPage'));
    }

    public function indexDeleteArchive(Request $request)
    {
        // Get the search query from the request
        $search = $request->input('search');
        $perPage = $request->input('per_page', 5); // Default to 5 items per page

        // Start the query
        $archives = Archive::with('department')->where('active_y_n', 'y');

        // Apply search if there is a search term
        if ($search) {
            $archives->where(function ($query) use ($search) {
                $query->whereRaw('LOWER(no_docs) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(tipe_docs) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereHas('department', function ($query) use ($search) {
                        $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($search) . '%']);
                    });
            });
        }

        // Paginate the results
        $archives = $archives->paginate($perPage);

        // Get departments and sub-departments for the view
        $deps = MDepartment::all();
        $subDeps = MSubdepartment::all();

        return view('pages.archive.document.index_delete', compact('archives', 'deps', 'subDeps', 'perPage'));
    }

    public function destroy($id)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $archive = Archive::find($id);

        if (!$archive) {
            return response()->json(['success' => false, 'message' => 'archive item not found'], 404);
        }

        $archive->active_y_n = "N";
        $archive->save();

        return response()->json(['success' => true, 'message' => 'archive item deactivated successfully']);
    }



    public function store(Request $request)
    {
        // Validate the input data
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'id_docs' => 'required|unique:t_archive,no_docs',
            'dep' => 'required|exists:m_department,id',
            'type_docs_modal' => 'required|string',
            'description' => 'required|string',
            'files' => 'required|array|max:1',
            'files.*' => 'file|mimes:pdf,jpg,jpeg|max:25000', // 25MB
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if (Archive::where('no_docs', $request->id_docs)->exists()) {
            return redirect()->back()->withErrors(['id_docs' => 'Nomor dokumen sudah ada!'])->withInput();
        }

        if ($request->hasFile('files')) {
            $file = $request->file('files')[0];

            if ($file->getSize() === 0) {
                return redirect()->back()->withErrors(['files' => 'The uploaded file cannot be empty.'])->withInput();
            }

            // Encode file to base64
            $fileContent = base64_encode(file_get_contents($file));
            $fileName = $file->getClientOriginalName();
        } else {
            return redirect()->back()->withErrors(['files' => 'File is required.'])->withInput();
        }

        // Save the archive data to the database
        $archive = new Archive();
        $archive->date = $request->date;
        $archive->no_docs = $request->id_docs;
        $archive->id_department = $request->dep;
        $archive->tipe_docs = $request->type_docs_modal;
        $archive->description = $request->description;
        $archive->pdf_jpg = $fileContent; // Store as base64
        $archive->file_name = $fileName;
        $archive->active_y_n = 'Y'; // Assuming active by default
        $archive->created_by = Auth::id();
        $archive->updated_by = Auth::id();

        $archive->save();

        return redirect()->back()->with('success', 'Archive added successfully!');
    }


    public function generateDocumentNumber(Request $request)
    {
        $type = $request->input('type');
        $today = now()->format('Ymd');

        $latestDocument = Archive::where('no_docs', 'like', "$type-$today%")
            ->orderBy('no_docs', 'desc')
            ->first();

        if ($latestDocument) {
            $latestNumber = (int) substr($latestDocument->no_docs, -2);
            $nextNumber = str_pad($latestNumber + 1, 2, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '01';
        }

        $newDocumentNumber = "$type-$today$nextNumber";

        return response()->json(['document_number' => $newDocumentNumber]);
    }

    public function showPdf($id)
    {
        $archive = Archive::find($id);

        if (is_null($archive) || empty($archive->pdf_jpg)) {
            abort(404, 'File not found');
        }
        $filename = $archive->file_name;
        $fileCost = base64_decode($archive->pdf_jpg);

        if (!$fileCost) {
            return response()->json(['error' => 'Gagal mendecode file PDF.'], 400);
        }

        return Response::make($fileCost, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"'
        ]);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'date' => 'required|date',
            'id_department' => 'required|exists:m_department,id',
            'tipe_docs' => 'required|string',
            'no_docs' => 'required|string',
            'description' => 'nullable|string',
            'files' => 'nullable|array',
            'files.*' => 'file|mimes:pdf|max:25000',
        ]);

        $archive = Archive::findOrFail($id);
        $archive->update([
            'date' => $validatedData['date'],
            'id_department' => $validatedData['id_department'],
            'tipe_docs' => $validatedData['tipe_docs'],
            'no_docs' => $validatedData['no_docs'],
            'description' => $validatedData['description'] ?? $archive->description,
        ]);

        if ($request->hasFile('files')) {
            $file = $request->file('files')[0];

            if ($file->getSize() === 0) {
                return redirect()->back()->withErrors(['files' => 'The uploaded file cannot be empty.'])->withInput();
            }

            $filePath = $file->store('archives', 'public');
            $archive->update(['pdf_jpg' => $filePath]);
        }

        return redirect()->route('index.editarchive')->with('success', 'Archive updated successfully.');
    }
}
