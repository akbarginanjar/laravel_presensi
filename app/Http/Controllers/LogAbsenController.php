<?php

namespace App\Http\Controllers;

use App\Models\LogAbsen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class LogAbsenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::all();
        $logAbsen = LogAbsen::orderBy('id', 'desc')->get();
        return view('pages.log-absen.index', compact('logAbsen','user'));
    }

    public function izinKaryawan($id)
    {
        $data = LogAbsen::where('user_id', $id)
                ->where('status_absen', 'izin')
                ->orderBy('created_at', 'desc')
                ->get();
        return view('pages.pengajuan-izin.index', compact('data','id'));
    }

    public function detailIzinKaryawan($id)
    {
        $data = LogAbsen::where('id', $id)->first();
        return view('pages.pengajuan-izin.detail', compact('data'));
    }

    public function show(string $id)
    {
        $logAbsen = LogAbsen::findOrFail($id);
        return view('pages.log-absen.detail', compact('logAbsen'));
    }

    public function getLocation(string $id)
    {
        $logAbsen = LogAbsen::findOrFail($id);
        return response()->json([
            'latitude' => $logAbsen->latitude,
            'longitude' => $logAbsen->longitude
        ]);
    }

    public function goAbsen(Request $request)
    {
        try {
            $user = Auth::user();
            // Simpan data absen ke database
            $now = Carbon::now();

            // Tentukan batas waktu terlambat (jam 9 pagi)
            $cutoffTime = Carbon::today()->setHour(9)->setMinute(0)->setSecond(0);

            // Tentukan status absensi
            $statusAbsen = $now->greaterThan($cutoffTime) ? 'telat' : 'tepat waktu';

            // Simpan data absen ke database
            $logAbsen = LogAbsen::create([
                'user_id' => $user->id,
                'latitude' => $request->input('latitude', null),
                'longitude' => $request->input('longitude', null),
                'status_absen' => $statusAbsen, // Status berdasarkan waktu absen
                'type' => $user->type,
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

    public function createIzin(Request $request)
    {
        try {
            $user = Auth::user();
            if ($request->hasFile('bukti_izin')) {
                $validator = Validator::make($request->all(), [
                    'user_id' => 'required|string',
                    'jenis_izin' => 'required|string',
                    'alasan_izin' => 'required|string',
                    'bukti_izin' => 'required|file|mimes:png,jpeg,jpg', 
                ]);
            }else{
                $validator = Validator::make($request->all(), [
                    'user_id' => 'required|string',
                    'jenis_izin' => 'required|string',
                    'alasan_izin' => 'required|string',
                ]);
            }


            if ($validator->fails()) {
                return redirect()->back()->with('error', 'Gagal melakukan membuat izin, pastikan kolom terisi.')->withInput();
            }

            // Simpan data absen ke database
            if ($request->hasFile('bukti_izin')) {
                $timestamp = now()->timestamp;
                $fileName = $timestamp . '_' . str_replace(' ', '_', $user->name) . '.' . $request->bukti_izin->extension();
            
                $path = $request->file('bukti_izin')->move(public_path('storage'), $fileName);
            
                $logAbsen = LogAbsen::create([
                    'user_id' => $request->user_id,
                    'type' => $user->type,
                    'status_absen' => 'izin',
                    'jenis_izin' => $request->jenis_izin,
                    'alasan_izin' => $request->alasan_izin,
                    'bukti_izin' => $fileName,
                ]);
            } else {
                $logAbsen = LogAbsen::create([
                    'user_id' => $request->user_id,
                    'type' => $user->type,
                    'status_absen' => 'izin',
                    'jenis_izin' => $request->jenis_izin,
                    'alasan_izin' => $request->alasan_izin,
                ]);
            }
            
            return redirect()->back()->with('success', 'Izin berhasil ditambahkan.');
    
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function printLaporan(Request $request)
    {

        $startOfWeek = Carbon::now()->startOfWeek()->toDateString();
        $endOfWeek = Carbon::now()->endOfWeek()->toDateString();   

        if ($request->tanggal_eksport != null && $request->tanggal_eksport != '') {
            $tanggalRange = explode(' to ', $request->tanggal_eksport);
            $startDate = $tanggalRange[0];
            $endDate = $tanggalRange[1];
            $data = LogAbsen::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('user_id, DATE(created_at) as tanggal, MAX(clock_in) as clock_in, MAX(clock_out) as clock_out, MAX(status_absen) as status')
            ->groupBy('user_id', 'tanggal')
            ->with('user')
            ->get();
        } else {
            $data = LogAbsen::whereBetween('created_at', [
                Carbon::parse($startOfWeek)->startOfDay(),
                Carbon::parse($endOfWeek)->endOfDay(),
            ])
            ->selectRaw('user_id, DATE(created_at) as tanggal, MAX(clock_in) as clock_in, MAX(clock_out) as clock_out, MAX(status_absen) as status')
            ->groupBy('user_id', 'tanggal')
            ->with('user')
            ->get();
        }


        // Buat rekap berdasarkan user_id
        $rekap = $data->groupBy('user_id')->map(function ($userLogs, $userId) {
            return [
                'user_id' => $userId,
                'nama_karyawan' => optional($userLogs->first()->user)->name ?? 'Tidak Diketahui',
                'tipe_karyawan' => optional($userLogs->first()->user)->type ?? 'Tidak Diketahui',
                'absen' => $userLogs->whereIn('status',  ['tepat waktu', 'telat'])->count(),
                'izin' => $userLogs->where('status', 'izin')->count(),
                // 'alpha' => 7 - ($userLogs->where('status', ['tepat waktu', 'telat'])->count() + $userLogs->where('status', 'izin')->count()),
            ];
        })->values()->toArray();

        $html = view('pages/log-absen.print', compact('rekap'))->render();

        return response()->json([
            'success' => true,
            'html' => $html
        ]);
    }

}
