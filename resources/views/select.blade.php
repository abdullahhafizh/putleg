@extends('layouts.app')

@section('content')
<style type="text/css">
.py-4 {
    padding: 0px!important;
}
</style>
<div class="card">
    <div class="card-body" style="padding: 0px!important;">
        <div class="responsive-table table-responsive">
            <table class="table table-striped table-hover table-bordered" style="font-size: 13px!important;">
                <thead>
                    <tr>
                        <th>Provinsi</th>
                        <th>Dapil</th>
                        <th>Status</th>
                        <!-- <th>Check out</th> -->
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($provinces as $province)
                    <tr>
                        <td>
                            {{ $province->provinsi_nama }}
                        </td>
                        <td>
                            <ul>
                                <?php
                                $semua_dapil = DB::table('data')->select('dapil')->where('provinsi', $province->provinsi_nama)->groupBy('dapil')->get();
                                $total_caleg = Data::where('provinsi', $province->provinsi_nama)->count();
                                ?>
                                @foreach($semua_dapil as $dapil)
                                <li style="font-size: 13px!important;">
                                    <a id="{{ str_slug($dapil->dapil, '_') }}" style="{{ Check::where('dapil_nama', $dapil->dapil)->value('status') == "1" ? 'text-decoration: line-through;' : null }}">
                                        <b style="font-size: 13px!important;">{{ $dapil->dapil }}</b> | <b style="font-size: 13px!important;">({{ Data::where('dapil', $dapil->dapil)->count() }}</b> Caleg) | <b style="font-size: 13px!important;">({{ Data::where('dapil', $dapil->dapil)->where('jenkel', 'L')->count() }}</b> Caleg Laki-laki) | <b style="font-size: 13px!important;">({{ Data::where('dapil', $dapil->dapil)->where('jenkel', 'P')->count() }}</b> Caleg Perempuan) | (<b>{{ Data::where('dapil', $dapil->dapil)->whereNull('file_url')->count() }}</b> Foto Kosong)</a> &nbsp; @if(Check::where('dapil_nama', $dapil->dapil)->value('status') == 0 && Province::where('provinsi_nama', $province->provinsi_nama)->value('export') == 0)<input type="checkbox" id="{{ $dapil->dapil }}" name="{{ $dapil->dapil }}" {{ Check::where('dapil_nama', $dapil->dapil)->value('status') == "1" ? 'checked' : null }}>@endif
                                    <hr>
                                </li>
                                @endforeach
                            </ul>
                        </td>
                        <td>
                            @if($semua_dapil->count() >= 1)
                            Jumlah Caleg : <b>{{ $total_caleg }}</b> Caleg
                            <br>
                            Jumlah Dapil : <b>{{ $semua_dapil->count() }}</b> Dapil
                            @endif
                        </td>
                        <!-- <td>
                            <input type="checkbox" name="wow" class="form-control">
                        </td> -->
                        <td>
                            <div class="row justify-content-center">
                                <a href="{{ route('export', $province->provinsi_nama) }}" style="color: white;" class="btn btn-success {{ $semua_dapil->count() <= 0 ? 'disabled' : null }}">Export</a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('foot-content')
<script type="text/javascript">
    $("input").on("click", function(){
        var id = this.id;
        var text = this.id.replace(/ /g,"_");
        text = text.toLowerCase();
        if($(this).is(":checked")) {
            $.get("{{ url('check') }}?dapil=" + id + "&status=1",function(data) {
            });
            $(this).hide();            
            $('#' + text + '').css('text-decoration', 'line-through');
            console.log('#' + text + '');
        } else {
            $.get("{{ url('check') }}?dapil=" + id + "&status=0",function(data) {
            });
        }
    });
</script>
@endsection
