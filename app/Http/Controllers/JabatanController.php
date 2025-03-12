<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JabatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Jabatan::all();
        return view('pages.jabatan.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validasi input
            $validator = Validator::make($request->all(), [
                'nama_jabatan' => 'required|string',
            ]);
    
            // Jika validasi gagal
            if ($validator->fails()) {
                return redirect()->back()->with('error', 'Gagal membuat jabatan, pastikan kolom terisi.')->withInput();
            }
    
            // Simpan data ke dalam tabel users
            $Jabatan = Jabatan::create([
                'nama_jabatan'    => $request->nama_jabatan,
            ]);
    
            // Redirect dengan pesan sukses
            return redirect()->back()->with('success', 'Jabatan berhasil ditambahkan.');
    
        } catch (\Exception $e) {
            // Jika ada error, kembalikan dengan pesan error
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Jabatan $jabatan)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = Jabatan::findOrFail($id);
        return view('pages.jabatan.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $jabatan = Jabatan::findOrFail($id);
    
            $validator = Validator::make($request->all(), [
                'nama_jabatan' => 'required|string',
            ]);
    
            if ($validator->fails()) {
                return redirect()->back()->with('error', 'Gagal melakukan perubahan Jabatan, pastikan kolom terisi.')->withInput();
            }
    
            $jabatan->nama_jabatan = $request->nama_jabatan;
    
            $jabatan->save();
    
            // Redirect dengan pesan sukses
            return redirect()->back()->with('success', 'Jabatan berhasil diubah.');
    
        } catch (\Exception $e) {
            // Jika ada error, kembalikan dengan pesan error
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $jabatan = Jabatan::findOrFail($id);
            $jabatan->delete();
    
            return response()->json(['success' => true, 'message' => 'Jabatan berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
}
