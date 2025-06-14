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
        // \Log::info('Request diterima', $request->all());
        // \Log::info('Data bobot yang diterima:', $request->input('bobot'));
        $data = $request->input('bobot');
        $total = 0;

        foreach ($data as $id => $bobot) {
            $total += floatval($bobot);
        }

        // if (abs($total - 1.0) > 0.001) {
        //     return redirect()->back()->withErrors('Total bobot harus sama dengan 1. Saat ini: ' . $total);
        // }

        // \Log::info("Total bobot dihitung: $total");
        // \Log::info("Perbandingan dengan 1.0: " . abs($total - 1.0));

        foreach ($data as $id => $bobot) {
            BobotKriteriaModel::where('id', $id)->update([
                'bobot' => floatval($bobot)
            ]);
        }

        return redirect()->back()->with('success', 'Bobot berhasil diperbarui.');
    }
}
