<?php

namespace App\Http\Controllers;

use App\Models\Kursus;
use App\Models\Program;
use App\Models\Rekod;
use App\Models\User;
use App\Models\Ptj;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = User::find(auth()->id());
        $isAdmin = $user->hasAnyRole(['Superadmin', 'Admin']);

        // Common (untuk semua)
        $myTotal = Rekod::where('user_id', $user->id)->count();
        $myThisMonth = Rekod::where('user_id', $user->id)
            ->whereYear('created_at', date('Y'))
            ->whereMonth('created_at', date('n'))
            ->count();

        $myLatest = Rekod::with(['program', 'kursus'])
            ->where('user_id', $user->id)
            ->latest()
            ->take(10)
            ->get();

        // Admin only
        $adminData = [];
        if ($isAdmin) {
            $totalRekod = Rekod::count();
            $thisMonth = Rekod::whereYear('created_at', date('Y'))
                ->whereMonth('created_at', date('n'))
                ->count();

            $totalUsers = User::count();

            $trashedRekod = Rekod::onlyTrashed()->count();

            $latestRekod = Rekod::with(['user.ptj', 'program', 'kursus'])
                ->latest()
                ->take(10)
                ->get();

            // Top PTJ by rekod (simple)
            $rekodByPtj = Rekod::join('users', 'rekods.user_id', '=', 'users.id')
                ->select('users.ptj_id', DB::raw('count(rekods.id) as total'))
                ->groupBy('users.ptj_id')
                ->orderByDesc('total')
                ->take(8)
                ->get();

            $ptjMap = Ptj::whereIn('id', $rekodByPtj->pluck('ptj_id'))->pluck('name', 'id');

            $byPtj = Rekod::join('users', 'rekods.user_id', '=', 'users.id')
                ->join('ptjs', 'users.ptj_id', '=', 'ptjs.id')
                ->select('ptjs.name as name', DB::raw('count(rekods.id) as total'))
                ->groupBy('ptjs.name')
                ->orderByDesc('total')
                ->take(10)
                ->get()
                ->toArray();

            $totalPrograms = Program::where('publish_status', 1)->count();
            $totalKursuses = Kursus::where('publish_status', 1)->count();

            $adminData = compact(
                'totalRekod',
                'thisMonth',
                'totalUsers',
                'totalPrograms',
                'totalKursuses',
                'trashedRekod',
                'latestRekod',
                'byPtj'
            );
        }

        return view('pages.dashboard.index', array_merge([
            'user' => $user,
            'isAdmin' => $isAdmin,
            'myTotal' => $myTotal,
            'myThisMonth' => $myThisMonth,
            'myLatest' => $myLatest,
        ], $adminData));
    }
}
