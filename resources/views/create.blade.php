@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
	<div class="col-sm-11">
		<div class="card">
			<div class="card-header">Dashboard</div>

			<div class="card-body">
				<form method="POST" action="{{ route('store') }}" autocomplete="off">
					@csrf
					@foreach($request['dapil_row'] as $dapil_row_key => $dapil_row)
					<?php
					for ($i=1; $i < ($dapil_row+1); $i++) {						
						?>
						<div class="form-group">
							<div class="row">
								<div class="col-sm-2">
									<label>Dapil</label>
									<input class="form-control" type="text" name="dapil[]" value="{{ $request['nama_dapil'][$dapil_row_key] }}" readonly>
								</div>
								<div class="col-sm-2">
									<label>No. Urut</label>
									<input class="form-control" type="number" value="{{ $i }}" name="no_urut[]" readonly>
								</div>
								<div class="col-sm-2">
									<label>Nama Caleg</label>
									<input class="form-control" type="text" name="nama[]" required>
								</div>
								<div class="col-sm-2">
									<label>Jenis Kelamin</label>
									<select class="form-control" name="jenkel[]" required>
										<option>L</option>
										<option>P</option>
									</select>
								</div>
								<div class="col-sm-2">
									<label>Alamat</label>
									<input class="form-control" type="text" name="alamat[]" required>
								</div>
								<div class="col-sm-2">
									<label>File</label>
									<input class="form-control" type="url" name="file_url[]" required>
								</div>
							</div>
						</div>
					<?php } ?>
					@endforeach					
					<input type="hidden" name="province" value="{{ $request['nama_provinsi'] }}">
					<button type="submit" class="btn btn-success btn-lg btn-block">Simpan</button>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection

@section('foot-content')
<script type="text/javascript">
	$(':input').keypress(function() {
		$(this).next(':input').focus();
	});
</script>
@endsection