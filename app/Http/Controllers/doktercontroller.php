<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\dokter;
use Illuminate\Support\Facades\DB;

class doktercontroller extends Controller
{
    //
    public function index()
    {

        $polidokter = DB::table('dim_dokters')
                ->select('dim_dokters.nama_poli', DB::raw('COUNT(dim_dokters.id_dokter) AS jumlahdokter'))
                ->groupBy('dim_dokters.id_poli')
                ->get();

        


            $namapoli = [];
            $jumlahdokter = [];
            foreach($polidokter as $data){
                $namapoli[] = $data->nama_poli;
                $jumlahdokter[] = $data->jumlahdokter;
                }
            $jumlahdokter = array_map('intval', $jumlahdokter);
            $jumlahdokter = json_encode($jumlahdokter);
            $namapoli = json_encode($namapoli);

        $kerjadokter = DB::table('fakta_penjualans')
                ->join('dim_dokters', 'fakta_penjualans.sk_dokter', '=', 'dim_dokters.sk_dokter')
                ->select('dim_dokters.nama_dokter', DB::raw('COUNT(dim_dokters.sk_dokter) AS jumlahpasien'))
                ->groupBy('dim_dokters.sk_dokter')
                ->get();

            $nama_dokter = [];
            $jumlahpasien = [];
            foreach($kerjadokter as $data){
                $nama_dokter[] = $data->nama_dokter;
                $jumlahpasien[] = $data->jumlahpasien;
                }
            $jumlahpasien = array_map('intval', $jumlahpasien);
            $jumlahpasien = json_encode($jumlahpasien);
            $nama_dokter = json_encode($nama_dokter);

        // dd($jumlahpasien);

        $jumlahkecamatan = DB::table('fakta_penjualans')
            ->join('dim_pasiens', 'fakta_penjualans.sk_pasien', '=', 'dim_pasiens.sk_pasien')
            ->select('dim_pasiens.kecamatan_pasien', DB::raw('sum(dim_pasiens.sk_pasien) AS total_pasien'))
            ->groupBy('dim_pasiens.kecamatan_pasien')
            ->get();

        $piee = "";
        foreach ($jumlahkecamatan as $val) {
            $piee.="['".$val -> kecamatan_pasien."',".$val->total_pasien."],";

        }
        // dd($data);

        // $jumlahkecamatan = json_encode($jumlahkecamatan);

        // dd($jumlahkecamatan);


        $dokter = DB::table('dim_dokters')
                ->take(10)
                ->get();

        // dd($dokter);
        
            return view('dokter',compact('dokter','piee'),[
            'jumlahkecamatan' => $jumlahkecamatan,
            'jumlahdokter' => $jumlahdokter,
            'namapoli' => $namapoli, 
            'nama_dokter' => $nama_dokter,
            'jumlahpasien' => $jumlahpasien,
        
        ]);


    }
}