@extends('layouts.app')

@section('content')
<div class="form-group">
    <div class="row justify-content-center">
        <div class="col-sm-11">
            <div class="card">
                <div class="card-header">Dashboard</div>
                <div class="card-body">
                    <div class="row justify-content-center">
                        <div class="col-sm-12">
                            <div>
                                <div class="form-group">
                                    <div class="row justify-content-center">
                                        <div class="col-sm-12">
                                            <div class="table-responsive responsive-table">
                                                <table class="table table-bordered table-hover table-striped table-condensed">
                                                    <thead>
                                                        <tr>
                                                            <th><center>No</center></th>
                                                            <th>Nama</th>
                                                            <th>Email</th>
                                                            <th><center>Total Check</center></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody><?php $i = 1; ?>                             
                                                    @foreach($users as $user)
                                                    <?php
                                                    $total = 0;
                                                    foreach (Province::where('auth_id', $user->id)->get() as $province) {
                                                        $plus = Data::where('provinsi', $province->provinsi_nama)->groupBy('dapil')->get();
                                                        $total = $total + count($plus);
                                                    }
                                                    $check = Check::where('auth_id', $user->id)->count();
                                                    $persen = ($check/$total)*100;
                                                    ?>
                                                    <tr class="{{ $persen == 100 ? 'table-success' : null }}">
                                                        <th><center>{{ $i++ }}</center></th>
                                                        <td>{{ $user->name }}</td>
                                                        <td>{{ $user->email }}</td>
                                                        <th><center>{{ $check }}/{{ $total }} ({{ str_limit($persen, 4, null) }}%)</center></th>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
