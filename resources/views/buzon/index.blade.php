@extends('layouts.app')


@section('breadcrumb')
<div class="content-header pb-0">
	<div class="container-fluid">
		<div class="row mb-4">
			<div class="col-sm-6">
				<h1 class="m-0"><i class="fas fa-stopwatch fa-fw"></i> Buzon</h1>
			</div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item">Buzon</li>
					<li class="breadcrumb-item active">SetDropTime</li>
				</ol>
			</div>
		</div>
        <div class="row mb-0">
            <div class="col-lg-3 col-6">
                <!-- small card -->
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>44</h3>
    
                        <p>Segundos</p>
                    </div>
                    <div class="icon">
                        <i class="far fa-clock"></i>
                    </div>
                    
                </div>
            </div>
        </div>
	</div>
</div>



@endsection


@section('content')
<div class="card">
    <div class="card-body table-responsive p-0">
        <table class="table table-sm">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Usuario</th>
                    <th>Segundos</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="10">
                        No hay información!
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>


@endsection
