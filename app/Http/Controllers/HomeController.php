<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Paslon;
use App\Models\Hasil;
use App\Models\Pemilih;
class HomeController extends Controller
{


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // chek jadwal
        $jadwal = \DB::table('jadwal_pemilihans')->find(1);
        $tanggal_sekarang = date('Y-m-d');
        $jam_sekarang = date('H:i:s');

        if($jadwal->tanggal==$tanggal_sekarang)
        {
            $date_a = new \DateTime(date('Y-m-d H:i:s'));
            $date_b = new \DateTime($jadwal->tanggal.' '.$jadwal->jam_akhir);
            $diff = $date_a->getTimestamp() - $date_b->getTimestamp();
            if($diff>0)
            {
                $data['message'] = "Jadwal Pemilihan Sudah Ditutup";
                $data['displayButton'] = false;
            }else
            {
                $data['message'] = "Klik tombol mulai untuk melakukan pemilihan";
                $data['displayButton']=true;
            }
        }else
        {
            $data['message'] = "Jadwal Pemilihan Belum Dibuka";
            $data['displayButton'] = false;
        }

        $paslon = Paslon::all();
        return view('welcome', $data);
    }

    public function voting()
    {

        if(session('nik')==null)
        {
            return redirect('/');
        }else
        {
            $data['paslons'] = Paslon::all();
            return view('pemilih.index', $data);
        }
    }

    public function voting_save(Request $request)
    {
        
        //return $request->all();
        $pemilih = Pemilih::where('nik',\Session('nik'))->first();
        $hasil = Hasil::where('paslon_id',$request->paslon_id)->where('tps_id',$pemilih->tps_id)->first();
        if($hasil==null)
        {
            // insert new
            Hasil::create(['paslon_id'=>$request->paslon_id,'tps_id'=>$pemilih->tps_id,'jumlah'=>1]);
            

        }else{ 
           $hasil->jumlah = $hasil->jumlah+1;
           $hasil->update();
        }


        
        $pemilih->status='sudah memilih';
        $pemilih->update();
        \Session::forget('nik');
        \Session::forget('user_name');
        return redirect('/');
    }
}
