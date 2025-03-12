<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Departemen;
use App\Models\Jabatan;
use App\Models\LogAbsen;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */



    public function login_mobile(Request $request){
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
        $user = User::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'message' => 'Berhasil Login',
            'token' => $token,
            'data' => $user,

        ]);
    }

    public function act_absensi(Request $request){
        try {
            $id_user_login = $request->input('user_login', null);
            $now = Carbon::now();
        
            // Tentukan batas waktu terlambat (jam 9 pagi)
            $cutoffTime = Carbon::today()->setHour(9)->setMinute(0)->setSecond(0);
            $statusAbsen = $now->greaterThan($cutoffTime) ? 'telat' : 'tepat waktu';
        
            // Cek apakah sudah ada absen hari ini
            $existingLog = LogAbsen::where('user_id', $id_user_login)
                                    ->whereDate('clock_in', Carbon::today())
                                    ->first();
        
            if ($existingLog) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anda sudah absen hari ini!',
                ], 400); // Return an error response if the user already clocked in today
            }
        
            // Jika belum ada absen, buat log absen baru
            $logAbsen = LogAbsen::create([
                'user_id' => $id_user_login,
                'latitude' => $request->input('latitude', null),
                'longitude' => $request->input('longitude', null),
                'status_absen' => $statusAbsen, // Status berdasarkan waktu absen
                'type' => 'karyawan',
                'clock_in' => $now,
                'clock_out' => null
            ]);
        
            return response()->json([
                'status' => 'success',
                'message' => 'Absen berhasil!',
                'data' => $logAbsen
            ], 200);
        
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat absen!',
                'error' => $e->getMessage()
            ], 500);
        }
        
    }

    public function index()
    {
        $user = User::all();
        return view('pages.user.index', compact('user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departemen = Departemen::all();   
        $jabatan = Jabatan::all();   
        return view('pages.user.create', compact('departemen','jabatan'));        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validasi input
            $validator = Validator::make($request->all(), [
                'email'    => 'required|email|unique:users,email',
                'name'     => 'required|string|max:255',
                'password' => 'required|string',
                'type'     => 'required|in:Admin,Karyawan',
                'departemen'     => 'required|string',
                'jabatan'     => 'required|string',
            ]);
    
            // Jika validasi gagal
            if ($validator->fails()) {
                return redirect()->back()->with('error', 'Data karyawan gagal ditambahkan, pastikan semua kolon terisi.')->withInput();
            }
    
            // Simpan data ke dalam tabel users
            $user = User::create([
                'email'    => $request->email,
                'name'     => $request->name,
                'password' => Hash::make($request->password),
                'type'     => $request->type,
                'id_departemen'     => $request->departemen,
                'id_jabatan'     => $request->jabatan,
            ]);
    
            // Redirect dengan pesan sukses
            return redirect()->route('user-management.index')->with('success', 'Data karyawan berhasil ditambahkan.');
    
        } catch (\Exception $e) {
            // Jika ada error, kembalikan dengan pesan error
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        $departemen = Departemen::all();   
        $jabatan = Jabatan::all();   
        return view('pages.user.edit', compact('user','departemen','jabatan'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            // Cari user berdasarkan ID
            $user = User::findOrFail($id);
    
            // Validasi input
            $validator = Validator::make($request->all(), [
                'email'    => 'required|email|unique:users,email,' . $id,
                'name'     => 'required|string|max:255',
                'type'     => 'required|in:Admin,Karyawan',
                'password' => 'nullable|string',
                'departemen'     => 'required|string',
                'jabatan'     => 'required|string',
            ]);
    
            // Jika validasi gagal
            if ($validator->fails()) {
                return redirect()->back()->with('error', 'Data karyawan gagal diubah, pastikan semua kolon terisi.')->withInput();
            }
    
            // Update data user
            $user->email = $request->email;
            $user->name = $request->name;
            $user->type = $request->type;
            $user->id_departemen = $request->departemen;
            $user->id_jabatan = $request->jabatan;
    
            // Update password jika diisi
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
    
            $user->save(); // Simpan perubahan
    
            // Redirect dengan pesan sukses
            return redirect()->route('user-management.index')->with('success', 'Data karyawan berhasil diperbarui.');
    
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
            $user = User::findOrFail($id);
            $user->logAbsen()->delete();
            $user->PengajuanCuti()->delete();
            $user->delete();
    
            return response()->json(['success' => true, 'message' => 'User berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
}
