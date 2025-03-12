<?php

namespace App\Http\Controllers;

use App\Models\Departemen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepartemenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Departemen::all();
        return view('pages.departemen.index', compact('data'));
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
                'nama_departemen' => 'required|string',
            ]);
    
            // Jika validasi gagal
            if ($validator->fails()) {
                return redirect()->back()->with('error', 'Gagal melakukan membuat departemen, pastikan kolom terisi.')->withInput();
            }
    
            // Simpan data ke dalam tabel users
            $Departemen = Departemen::create([
                'nama_departemen'    => $request->nama_departemen,
            ]);
    
            // Redirect dengan pesan sukses
            return redirect()->back()->with('success', 'Departemen berhasil ditambahkan.');
    
        } catch (\Exception $e) {
            // Jika ada error, kembalikan dengan pesan error
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Departemen $departemen)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = Departemen::findOrFail($id);
        return view('pages.departemen.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $departemen = Departemen::findOrFail($id);
    
            $validator = Validator::make($request->all(), [
                'nama_departemen' => 'required|string',
            ]);
    
            if ($validator->fails()) {
                return redirect()->back()->with('error', 'Gagal melakukan perubahan Departemen, pastikan kolom terisi.')->withInput();
            }
    
            $departemen->nama_departemen = $request->nama_departemen;
    
            $departemen->save();
    
            // Redirect dengan pesan sukses
            return redirect()->back()->with('success', 'Departemen berhasil diubah.');
    
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
            $departemen = Departemen::findOrFail($id);
            $departemen->delete();
    
            return response()->json(['success' => true, 'message' => 'Departemen berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
}
