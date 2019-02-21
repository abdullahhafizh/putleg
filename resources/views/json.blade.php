@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-sm-11">
        <div class="card">
            <div class="card-header">Dashboard</div>

            <div class="card-body">
                <textarea class="form-control" style="height: auto;">
                    {{ $json }}
                </textarea>
            </div>
        </div>
    </div>
</div>
@endsection