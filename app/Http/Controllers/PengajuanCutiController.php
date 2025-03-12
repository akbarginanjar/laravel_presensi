<?php

namespace App\Http\Controllers;

use App\Models\PengajuanCuti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PengajuanCutiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $data = PengajuanCuti::where('user_id', $id)->get();
        return view('pages.pengajuan-cuti.index', compact('data','id'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function indexAdmin()
    {
        $data = PengajuanCuti::all();
        return view('pages.pengajuan-cuti.index-admin', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        try {
            // Validasi input
            $validator = Validator::make($request->all(), [
                'tanggal_cuti' => 'required|date|after_or_equal:today',
                'tanggal_selesai_cuti' => 'required|date|after_or_equal:today',
                'alasan_cuti' => 'required|string',
            ]);
    
            // Jika validasi gagal
            if ($validator->fails()) {
                return redirect()->back()->with('error', 'Gagal melakukan permintaan cuti, pastikan kolom terisi.')->withInput();
            }
    
            // Simpan data ke dalam tabel users
            $PengajuanCuti = PengajuanCuti::create([
                'user_id'    => $user->id,
                'tanggal_cuti'    => $request->tanggal_cuti,
                'tanggal_cuti_selesai'    => $request->tanggal_selesai_cuti,
                'alasan_cuti'    => $request->alasan_cuti,
            ]);
    
            // Redirect dengan pesan sukses
            return redirect()->back()->with('success', 'Permintaan Cuti berhasil ditambahkan.');
    
        } catch (\Exception $e) {
            // Jika ada error, kembalikan dengan pesan error
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PengajuanCuti $pengajuanCuti)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = PengajuanCuti::findOrFail($id);
        return view('pages.pengajuan-cuti.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            // Cari user berdasarkan ID
            $pengajuanCuti = PengajuanCuti::findOrFail($id);
    
            // Validasi input
            $validator = Validator::make($request->all(), [
                'tanggal_cuti' => 'required|date|after_or_equal:today',
                'tanggal_selesai_cuti' => 'required|date|after_or_equal:today',
                'alasan_cuti' => 'required|string',
            ]);
    
            // Jika validasi gagal
            if ($validator->fails()) {
                return redirect()->back()->with('error', 'Gagal melakukan perubahan permintaan cuti, pastikan kolom terisi.')->withInput();
            }
    
            // Update data user
            $pengajuanCuti->tanggal_cuti = $request->tanggal_cuti;
            $pengajuanCuti->tanggal_cuti_selesai = $request->tanggal_selesai_cuti;
            $pengajuanCuti->alasan_cuti = $request->alasan_cuti;
    
            $pengajuanCuti->save(); // Simpan perubahan
    
            // Redirect dengan pesan sukses
            return redirect()->back()->with('success', 'Permintaan Cuti berhasil diubah.');
    
        } catch (\Exception $e) {
            // Jika ada error, kembalikan dengan pesan error
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function approval(Request $request, string $id)
    {
        try {
            // Cari user berdasarkan ID
            $pengajuanCuti = PengajuanCuti::findOrFail($id);
    
            if ($request->action == "approve") {
                $pengajuanCuti->status_permohonan = true;
                $message = "Permohonan cuti telah disetujui!";
            } elseif ($request->action == "reject") {
                $pengajuanCuti->status_permohonan = false;
                $message = "Permohonan cuti telah ditolak!";
            } else {
                return response()->json(["message" => "Aksi tidak valid!"], 400);
            }
        
            $pengajuanCuti->save();
    
            // Redirect dengan pesan sukses
            return response()->json(["message" => $message]);
    
        } catch (\Exception $e) {
            // Jika ada error, kembalikan dengan pesan error
            return response()->json(["message" => `Terjadi kesalahan:` . $e->getMessage()], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $pengajuanCuti = PengajuanCuti::findOrFail($id);
            $pengajuanCuti->delete();
    
            return response()->json(['success' => true, 'message' => 'Permintaan Cuti berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
}
