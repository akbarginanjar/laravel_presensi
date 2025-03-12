<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\LogAbsen;
use App\Models\user;
use App\Models\PengajuanCuti;
use Illuminate\Http\Request;


class DashboardController extends Controller
{
    public function index()
    {
        addVendors(['amcharts', 'amcharts-maps', 'amcharts-stock']);

        $user = Auth::user();
        $dataCuti = PengajuanCuti::where('user_id', $user->id)
                        ->latest()
                        ->take(5)
                        ->get();
        $cekAbsen = LogAbsen::where('user_id', $user->id)
                        ->whereDate('created_at', Carbon::today())
                        ->exists();

    return view('pages/dashboard.dashboard', compact('dataCuti','cekAbsen'));
    }

    public function admin()
    {
        $totalKaryawan = User::where('type', 'Karyawan')->count();
        $totalAbsenHariIni = LogAbsen::whereDate('clock_in', Carbon::today())->distinct('user_id')->count('user_id');

        //pengajuan cuti perminggu
        $startOfWeek = Carbon::now()->startOfWeek()->toDateString();
        $endOfWeek = Carbon::now()->endOfWeek()->toDateString();    
        $pengajuanCuti = PengajuanCuti::whereBetween('created_at', [$startOfWeek, $endOfWeek])
                              ->get();

        return view('pages/dashboard.dashboard-admin', compact('totalKaryawan', 'totalAbsenHariIni','pengajuanCuti'));
    }

    public function printCuti(Request $request)
    {
        $startOfWeek = Carbon::now()->startOfWeek()->toDateString();
        $endOfWeek = Carbon::now()->endOfWeek()->toDateString();
        if ($request->tanggal_eksport != null && $request->tanggal_eksport != '') {
            $tanggalRange = explode(' to ', $request->tanggal_eksport);
            $startDate = $tanggalRange[0];
            $endDate = $tanggalRange[1];
            $data = PengajuanCuti::whereBetween('created_at', [$startDate, $endDate])->get();
        } else {
            $data = PengajuanCuti::whereBetween('created_at', [$startOfWeek, $endOfWeek])->get();
        }

        $html = view('pages/dashboard.print-cuti', compact('data'))->render();

        return response()->json([
            'success' => true,
            'html' => $html
        ]);
    }
}
