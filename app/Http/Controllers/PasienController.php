<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\Pasien;
use Illuminate\Http\Request;



class PasienController extends Controller
{
    public function index()
    {

        $jumlahkecamatan = DB::table('fakta_penjualans')
            ->join('dim_pasiens', 'fakta_penjualans.sk_pasien', '=', 'dim_pasiens.sk_pasien')
            ->select('dim_pasiens.kecamatan_pasien', DB::raw('sum(dim_pasiens.sk_pasien) AS total_pasien'))
            ->groupBy('dim_pasiens.kecamatan_pasien')
            ->get();

        $jumlahkecamatan = json_encode($jumlahkecamatan);

    // dd($jumlahkecamatan);












        // $data = Fakta_penjualan::paginate(10);

        // $users = DB::table('fakta_penjualans')
        //     ->leftJoin('dim_pasiens', 'fakta_penjualans.sk_pasien', '=', 'dim_pasiens.sk_pasien')
        //     ->get();

        $users = DB::table('fakta_penjualans')
            ->join('dim_dokters', 'fakta_penjualans.sk_dokter', '=', 'dim_dokters.sk_dokter')
            ->select('fakta_penjualans.sk_dokter','dim_dokters.nama_dokter', DB::raw('SUM(fakta_penjualans.jumlah_pembayaran) AS total'))
            ->groupBy('fakta_penjualans.sk_dokter')
            ->get();

        $iddokter = [];
        $namadokter = [];
        $totalpendapatan = [];
        foreach($users as $data){
            $iddokter[] = $data->sk_dokter;
            $namadokter[] = $data->nama_dokter;
            $totalpendapatan[] = $data->total;
        }
        $totalpendapatan = array_map('intval', $totalpendapatan);
        $totalpendapatan = json_encode($totalpendapatan);
        $namadokter = json_encode($namadokter);


        $pengunjung = DB::table('fakta_penjualans')
            ->join('dim_dokters', 'fakta_penjualans.sk_dokter', '=', 'dim_dokters.sk_dokter')
            ->select('dim_dokters.nama_poli', DB::raw('COUNT(dim_dokters.id_poli) AS poli'))
            ->groupBy('dim_dokters.id_poli')
            ->get();

        $namapoli = [];
        $jumlahpoli = [];
        foreach($pengunjung as $data){
            $namapoli[] = $data->nama_poli;
            $jumlahpoli[] = $data->poli;
        }
        $jumlahpoli = array_map('intval', $jumlahpoli);
        $namapoli = json_encode($namapoli);
        $jumlahpoli = json_encode($jumlahpoli);


        // $userdata = DB::select(DB::raw("COUNT(*) as COUNT"))
        // ->whereYear();

        // $data = DB::table('fakta_penjualans')
        //     ->select('jumlah_pembayaran')
        //     ->whereYear('date', '=', 2015)
        //     ->orderby('noworkorder', 'desc')
        //     ->get();

        $pertahun = DB::table('fakta_penjualans')
            ->join('dim_waktus', 'fakta_penjualans.sk_waktu', '=', 'dim_waktus.sk_waktu')
            ->select('dim_waktus.Tahun','dim_waktus.nama_bulan', DB::raw('sum(fakta_penjualans.jumlah_pembayaran) AS pendapatanbulan'))
            ->where('Tahun', '=', 2022)
            ->groupBy('dim_waktus.nama_bulan')
            ->get();

            $bulantahun = [];
            $pendapatantahun = [];
            foreach($pertahun as $data){
                $bulantahun[] = $data->nama_bulan;
                $pendapatantahun[] = $data->pendapatanbulan;
            }
            $pendapatantahun = array_map('intval', $pendapatantahun);
            $bulantahun = json_encode($bulantahun);
            $pendapatantahun = json_encode($pendapatantahun);
    
    

        $kuartalpasien = DB::table('fakta_penjualans')
            ->join('dim_waktus', 'fakta_penjualans.sk_waktu', '=', 'dim_waktus.sk_waktu')
            ->select('dim_waktus.Tahun','dim_waktus.kuartal', DB::raw('count(fakta_penjualans.sk_pasien) AS totalpasienkuartal'))
            ->where('Tahun', '=', 2022)
            ->groupBy('dim_waktus.kuartal')
            ->get();


            $kuartal = [];
            $totalpasienkuartal = [];
            foreach($kuartalpasien as $data){
                $kuartal[] = $data->kuartal;
                $totalpasienkuartal[] = $data->totalpasienkuartal;
            }
            $totalpasienkuartal = array_map('intval', $totalpasienkuartal);
            $kuartal = json_encode($kuartal);
            $totalpasienkuartal = json_encode($totalpasienkuartal);
        
        $data_transaksi  = DB::table('fakta_penjualans')
            ->join('dim_waktus', 'fakta_penjualans.sk_waktu', '=', 'dim_waktus.sk_waktu')
            ->join('dim_pasiens', 'fakta_penjualans.sk_pasien', '=', 'dim_pasiens.sk_pasien')
            ->join('dim_dokters', 'fakta_penjualans.sk_dokter', '=', 'dim_dokters.sk_dokter')
            ->join('dim_ruangs', 'fakta_penjualans.sk_ruang', '=', 'dim_ruangs.sk_ruang')
            ->select('fakta_penjualans.jumlah_pembayaran','dim_waktus.Tanggal','dim_pasiens.nama_pasien','dim_dokters.nama_dokter','dim_dokters.nama_poli','dim_ruangs.id_ruangan')

            // ->select('fakta_penjualans.*','dim_waktus.*','dim_pasiens.*','dim_dokters.*','dim_dokters.*','dim_ruangs.*')
            // ->orderBy('dim_waktus.Tanggal', 'DESC')
            ->take(10)
            ->get();

        // dd($data_transaksi);


    

        
        return view('pasien',compact('data_transaksi'),[
        'jumlahkecamatan' => $jumlahkecamatan, 
        'jumlahkecamatan' => $jumlahkecamatan,
        'namapoli' => $namapoli, 
        'jumlahpoli' => $jumlahpoli,
        'bulantahun' => $bulantahun,
        'pendapatantahun' => $pendapatantahun,
        'kuartal' => $kuartal,
        'totalpasienkuartal' => $totalpasienkuartal
        
    
    
    
    ]);



        // echo $user->sk_dokter;
    }
}
