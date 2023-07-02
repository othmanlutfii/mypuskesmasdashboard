<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ruang;
use Illuminate\Support\Facades\DB;

class ruangcontroller extends Controller
{
    //
    public function index()
    {

        $kelaspasien = DB::table('fakta_penjualans')
            ->join('dim_ruangs', 'fakta_penjualans.sk_ruang', '=', 'dim_ruangs.sk_ruang')
            ->select('dim_ruangs.kelas', DB::raw('count(fakta_penjualans.sk_pasien) AS totalpasin'))
            ->groupBy('dim_ruangs.kelas')
            ->get();

            $kelas = [];
            $totalpasin = [];
            foreach($kelaspasien as $data){
                $kelas[] = $data->kelas;
                $totalpasin[] = $data->totalpasin;
            }
            $totalpasin = array_map('intval', $totalpasin);
            $kelas = json_encode($kelas);
            $totalpasin = json_encode($totalpasin);

        $tabelruangananalisis = DB::table('fakta_penjualans')
            ->join('dim_ruangs', 'fakta_penjualans.sk_ruang', '=', 'dim_ruangs.sk_ruang')
            ->select('dim_ruangs.kelas AS kelass', DB::raw('sum(fakta_penjualans.jumlah_pembayaran) AS totalpendapatan'), DB::raw('count(fakta_penjualans.sk_pasien) AS totalpasien'))
            ->groupBy('dim_ruangs.kelas')
            ->get();

        // dd($tabelruangananalisis);

            // $kelas = [];
            // $totalpasin = [];
            // foreach($tabelruangananalisis as $data){
            //     $kelas[] = $data->kelas;
            //     $totalpasin[] = $data->totalpasin;
            // }
            // $totalpasin = array_map('intval', $totalpasin);
            // $kelas = json_encode($kelas);
            // $totalpasin = json_encode($totalpasin);


        $ruang = DB::table('dim_ruangs')
            ->take(10)
            ->get();


            return view('ruang',compact('ruang','tabelruangananalisis'),[
            'kelas' => $kelas,
            'totalpasin' => $totalpasin
            

        ]);
    }
}
