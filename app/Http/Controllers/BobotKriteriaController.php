<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BobotKriteriaModel;

class BobotKriteriaController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Data Rekomendasi Lomba',
            'list' => ['Lomba', 'Rekomendasi']
        ];

        $page = (object) [
            'title' => 'Daftar lomba dengan skor kecocokan'
        ];

        $activeMenu = 'rekomendasiLomba';

        $kriterias = BobotKriteriaModel::all();
        return view('admin.bobot.index', compact('kriterias', 'breadcrumb', 'page', 'activeMenu'));
    }

    public function update(Request $request)
    {
        $data = $request->input('bobot');
        $total = 0;

        foreach ($data as $id => $bobot) {
            $total += floatval($bobot);
        }

        if (round($total, 4) !== 1.0) {
            return redirect()->back()->withErrors('Total bobot harus sama dengan 1. Saat ini: ' . $total);
        }

        foreach ($data as $id => $bobot) {
            BobotKriteriaModel::where('id', $id)->update([
                'bobot' => floatval($bobot)
            ]);
        }

        return redirect()->back()->with('success', 'Bobot berhasil diperbarui.');
    }
}
