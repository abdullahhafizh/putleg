<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Excel;
use Dapil;
use Province;
use Storage;
use Zipper;
use Data;
use DB;
use Report;
use Check;
use Auth;
use User;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {        
        // return view('test');            
        $dapil = Dapil::all();
        $filter = [];
        foreach ($dapil as $key => $value) {
            if (Data::where('dapil', $value->dapil_nama)->exists()) {
                unset($dapil[$key]);
            }
        }
        $data['semua_dapil'] = $dapil;
        $data['provinces'] = Province::all();
        return view('home')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {        
        $data['request'] = $request;        
        return view('create')->with($data);
    }

    public function select()
    {
        if (Auth::user()->id == 5) {
            $data['provinces'] = Province::where('auth_id', 1)->get();
        }
        else {
            $data['provinces'] = Province::where('auth_id', Auth::user()->id)->get();
        }        
        // $data['datas'] = Data::where('provinsi', 'ACEH')->orderBy('dapil', 'asc')->orderBy('no_urut', 'asc')->get();
        // $users = DB::table('data')->select('dapil')->where('provinsi', 'ACEH')->groupBy('dapil')->get();
        // dd($users);
        return view('select')->with($data);
    }

    public function allfile()
    {
        $data = null;
        $semua_provinsi = Province::all();
        foreach ($semua_provinsi as $provinsi_key => $provinsi) {
            $gkada = [];
            $size = [];
            $files = Storage::allFiles('caleg/'.$provinsi->provinsi_nama.'/file');
            foreach (Data::where('provinsi', ''.$provinsi->provinsi_nama.'')->whereNotNull('file_url')->get() as $key => $value) {
                if (Storage::exists('caleg/'.$provinsi->provinsi_nama.'/file/dprd2'.str_slug($value->dapil.$value->no_urut, null).'.png') == false) {
                    $gkada[] = $value->dapil.' - '.$value->no_urut;
                }
                foreach ($files as $key => $file) {
                    if (basename($files[$key], ".png") == 'dprd2'.str_slug($value->dapil.$value->no_urut, null)) {
                        unset($files[$key]);
                    }
                }
            }

            $files2 = Storage::allFiles('caleg/'.$provinsi->provinsi_nama.'/file');
            foreach ($files2 as $key => $file) {
                $size_num = Storage::size($files2[$key]);
                if ($size_num <= 0) {
                    $size[] = $files2[$key];
                }
            }

            if (count($files)>=1) {                
                $data[$provinsi->provinsi_nama]['nyasar'] = $files;
            }
            if (count($gkada)>=1) {
                $data[$provinsi->provinsi_nama]['kosong'] = $gkada;
            }
            if (count($size)>=1) {
                $data[$provinsi->provinsi_nama]['size'] = $size;
            }
        }
        dump($data);
    }

    public function file($provinsi)
    {
        $data = null;
        $gkada = [];
        $size = [];
        $files = Storage::allFiles('caleg/'.$provinsi.'/file');
        foreach (Data::where('provinsi', ''.$provinsi.'')->whereNotNull('file_url')->get() as $key => $value) {
            if (Storage::exists('caleg/'.$provinsi.'/file/dprd2'.str_slug($value->dapil.$value->no_urut, null).'.png') == false) {
                $gkada[] = $value->dapil.' - '.$value->no_urut;
            }
            foreach ($files as $key => $file) {
                if (basename($files[$key], ".png") == 'dprd2'.str_slug($value->dapil.$value->no_urut, null)) {
                    unset($files[$key]);
                }
            }
        }

        $files2 = Storage::allFiles('caleg/'.$provinsi.'/file');
        foreach ($files2 as $key => $file) {
            $size_num = Storage::size($files2[$key]);
            if ($size_num <= 0) {
                $size[] = $files2[$key];
            }
        }
        
        if (count($files)>=1) {
            $data['nyasar'] = $files;
        }        
        if (count($gkada)>=1) {
            $data['kosong'] = $gkada;
        }
        if (count($size)>=1) {
            $data['size'] = $size;
        }
        dump($data);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = [];
        foreach ($request->dapil as $key => $value) {
            $table = new Data;
            $table->provinsi = $request->province;
            $table->dapil = $request->dapil[$key];
            $table->no_urut = $request->no_urut[$key];
            $table->nama_caleg = $request->nama[$key];
            $table->jenkel = $request->jenkel[$key];
            $table->alamat = $request->alamat[$key];
            $table->created_at = now();
            $table->updated_at = now();
            if ($request->file_url[$key] != null || $request->file_url[$key] != '') {
                $table->file_url = $request->file_url[$key];
            }
            $data[] = $table->attributesToArray();
        }

        Data::insert($data);
        
        stream_context_set_default( [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ]);

        foreach ($request->dapil as $key => $value) {
            if ($request->file_url[$key] != null || $request->file_url[$key] != '') {
                $url = $request->file_url[$key];
                $file_headers = get_headers($url);

                if(strpos($file_headers[0], '404') !== false || strpos($file_headers[0], '403') !== false) {
                } else {
                    $contents = file_get_contents($url);
                    $fileSize = strlen($contents);
                    if ($fileSize >= 1) {
                        $name = 'caleg/'.$request->province."/file/dprd2".str_slug($request->dapil[$key].$request->no_urut[$key], null).".png";
                        Storage::put($name, $contents);
                    }
                }
            }
        }

        return redirect('/');
    }

    public function export(Request $request, $provinsi)
    {        
        $request = Data::where('provinsi', $provinsi)->orderBy('dapil', 'asc')->orderBy('no_urut', 'asc')->get();        
        Excel::create('Filename', function($excel) use($request, $provinsi) {
            $excel->setFilename('DCT DPRD2 '.$provinsi);
            $excel->sheet('Sheetname', function($sheet) use($request, $provinsi) {
                $sheet->row(1, array(
                    'dapil', 'no urut', 'nama', 'jenis kelamin', 'alamat', 'nama file'
                ));
                foreach ($request as $key => $value) {
                    if ($value->file_url == null || Storage::exists('caleg/'.$provinsi.'/file/dprd2'.str_slug($value->dapil.$value->no_urut, null).'.png') == false) {
                        $file = null;
                    }                    
                    else {
                        $file = "dprd2".str_slug($value->dapil.$value->no_urut, null).".png";
                    }
                    if ($value->alamat == 'kosong' || $value->alamat == 'KOSONG' || $value->alamat == '-') {
                        $value->alamat = null;
                    }                    
                    $sheet->row(($key+2), array(
                        $value->dapil, $value->no_urut, $value->nama_caleg, $value->jenkel, $value->alamat, $file
                    ));                                    
                }
            });
        })->store('xlsx', storage_path('app/caleg/'.$provinsi));

        $files = glob(storage_path('app/caleg/'.$provinsi.'/'));
        $filepath = 'caleg/'.$provinsi.'/'.$provinsi.'_'.uniqid().'.zip';
        Zipper::make(public_path($filepath))->add($files)->close();

        $table = Province::where('provinsi_nama', $provinsi)->first();
        $table->export = 1;
        $table->save();

        return response()->download(public_path($filepath));        
    }

    public function lapor()
    {
        $data['semua_dapil'] = Dapil::all();
        $data['provinces'] = Province::all();
        return view('lapor')->with($data);
    }

    public function laporPost(Request $request)
    {        
        foreach($request->dapil as $key => $value) {
            $table = new Report;
            $table->provinsi_nama = $request->provinsi;
            $table->kabupaten_nama = $request->kabupaten;
            $table->dapil = $value;
            $table->save();
        }        
        return redirect('/');
    }

    public function donasi()
    {        
        $null = Data::whereNull('file_url')->inRandomOrder()->first();
        $cari = '%'.preg_replace('/[0-9]+/', '', $null->dapil).'%';
        $data['semua_caleg'] = Data::where('dapil', 'like', $cari)->whereNull('file_url')->orderBy('dapil', 'asc')->orderBy('no_urut', 'asc')->get();
        $data['kabupaten'] = preg_replace('/[0-9]+/', '', $null->dapil);
        return view('donasi')->with($data);
    }

    public function donasiPost(Request $request)
    {        
        stream_context_set_default( [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ]);
        
        foreach ($request->id as $key => $value) {
            $table = Data::find($request->id[$key]);            
            if ($table->file_url == null) {
                $table->file_url = $request->url_file[$key];
                $table->save();

                $url = $request->url_file[$key];
                $contents = file_get_contents($url);
                $fileSize = strlen($contents);
                if ($fileSize >= 1) {
                    $name = 'caleg/'.$table->provinsi."/file/dprd2".str_slug($table->dapil.$table->no_urut, null).".png";
                    Storage::put($name, $contents);
                }
            }
        }            

        return redirect('donasi');
    }

    public function check(Request $request)
    {
        $table = Check::where('dapil_nama', $request->dapil)->first();
        $table->status = $request->status;
        $table->auth_id = Auth::user()->id;
        $table->save();
    }

    public function total()
    {
        $i = 0;
        foreach (Dapil::all() as $key => $dapil) {
            if (!Data::where('dapil', $dapil->dapil_nama)->exists() && !Report::where('dapil', $dapil->dapil_nama)->exists()) {
                $i++;
            }
        }
        $persen = ((($i/2206)*100)*0)+$i;
        die($persen);
    }

    public function report()
    {        
        $i = 0;
        $data['un_dapil'] = array();
        foreach (Dapil::all() as $key => $dapil) {
            if (!Data::where('dapil', $dapil->dapil_nama)->exists() && !Report::where('dapil', $dapil->dapil_nama)->exists()) {
                $i++;
                $data['un_dapil'][] = $dapil->dapil_nama;
            }
        }
        $data['total'] = $i;
        $data['progress'] = ($i/2206)*100;
        $data['selesai'] = ((2206-$i)/2206)*100;
        return view('report')->with($data);
    }

    public function progress()
    {
        $i = 0;
        foreach (Dapil::all() as $key => $dapil) {
            if (!Data::where('dapil', $dapil->dapil_nama)->exists() && !Report::where('dapil', $dapil->dapil_nama)->exists()) {
                $i++;
            }
        }        
        $persen = ($i/2206)*100;
        die($persen);
    }

    public function selesai()
    {
        $i = 0;
        foreach (Dapil::all() as $key => $dapil) {
            if (!Data::where('dapil', $dapil->dapil_nama)->exists() && !Report::where('dapil', $dapil->dapil_nama)->exists()) {
                $i++;
            }
        }
        $persen = ((2206-$i)/2206)*100;
        die($persen);
    }

    public function pecahan()
    {
        $data['users'] = User::all();        
        return view('pecahan')->with($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
