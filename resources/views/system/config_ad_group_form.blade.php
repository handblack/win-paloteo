@extends('layouts.app')
@extends('layouts.app')

@section('breadcrumb')
    <div class="content-header pb-0">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><i class="fas fa-server"></i> ConfigAD [Campañas] KEY</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">Sistema</li>
                        <li class="breadcrumb-item active">Perfiles</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <form action="{{ $url }}" method="POST">
        <input type="hidden" name="_mode" value="{{ $mode }}" />
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        <input type="hidden" name="_method" value="{{ $mode == 'edit' ? 'PUT' : '' }}" />
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <strong>{{ $row->header->configname }}</strong>
                </h3>
            </div>
            <div class="card-body bg-form">
				<div class="row mb-2">
					<div class="col-md-1">
						<label class="mb-0">Orden</label>
						<input type="number" name="orden" value="{{ old('orden',$row->orden) }}" class="form-control">
					</div>
					<div class="col-md-4">
						<label class="mb-0">Tipo de registro</label>
						<select name="istype" id="" class="form-control">
							<option value="P" {{ $row->istype == 'P' ? 'selected' : '' }}>PROFILE</option>
							<option value="M" {{ $row->istype == 'M' ? 'selected' : '' }}>MEMBER</option>
						</select>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<label class="mb-0">Path LDAP</label>
						<input type="text" name="groupname" value="{{ old('groupname', $row->groupname) }}" class="form-control">
					</div>
				</div>
            </div>
            <div class="card-footer">
                <a href="{{ route('groupad.index') }}" class="btn btn-danger "><i class="fas fa-times fa-fw"></i>
                    CANCELAR</a>
                <button type="submit" class="btn btn-primary ml-1"><i class="fas fa-save fa-fw"></i>
                    {{ $mode == 'new' ? 'CREAR' : 'MODIFICAR' }} </button>
            </div>
        </div>

    </form>
    <br>
	<div class="card">
		<div class="card-body">
			<p class="lead">			
			Para registrar una clave se tiene mantener la siguiente estructura en la clave:
			</p>
			<pre>
				OU=OPERACIONES,OU=CONTACT,DC=contact,DC=com
			</pre>
		</div>
	</div>
@endsection
