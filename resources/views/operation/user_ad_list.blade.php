@extends('layouts.app')

@push('header')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('breadcrumb')
    <div class="content-header pb-0">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><i class="fas fa-cubes fa-fw"></i> Usuarios AD</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">Operaciones</li>
                        <li class="breadcrumb-item active">Usuarios AD</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-header pt-0">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-md-5 mt-2">
                    <form action="{{ route('alert.index') }}" method="GET">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <div class="btn-toolbar" role="toolbar">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <a href="#" onclick="location.reload()" class="btn btn-secondary">
                                        <i class="fas fa-sync-alt fa-fw"></i>
                                    </a>
                                </div>
                                <input type="text" name="q" value="{{ $q }}"
                                    class="form-control float-right" placeholder="Buscar.." autofocus>
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-secondary">
                                        <i class="fas fa-search fa-fw"></i>
                                    </button>
                                    
                                    <a href="{{ route('alert.create') }}" class="btn btn-success">
                                        <i class="far fa-plus-square fa-fw"></i>
                                    </a>
                                    <button type="button" class="btn btn-success dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu" role="menu" style="">
                                        <a class="dropdown-item" href="{{ route('alert.show','download') }}"><i class="fas fa-download fa-fw"></i> Descargar Reporte Alerta</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-upload fa-fw"></i> Subir dimensionado</a>
                                        <a class="dropdown-item" href="{{ route('alert.show','dimensionado') }}"><i class="fas fa-download fa-fw"></i> Descargar dimensionado</a>
                                    </div>
                                 
 
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-12 col-md-7 mt-2">
                    {{ $result->links('layouts.paginate') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <table>
                <thead>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($result as $item)
                        <tr>
                            <td></td>
                        </tr>
                    @empty
                        
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Modal PRICELIST - UPLOAD -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog  modal-lg" role="document">
            <form action="{{ route('user_upload_excel') }}" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />                
                <div class="modal-content">
                    <div class="card mb-0">
                        <div class="card-header">
                            <h3 class="card-title"><strong>Subir archivo de USUARIOS</strong></h3>
                        </div>
                        <div class="card-body bg-form">
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" name="file" class="custom-file-input" id="exampleInputFile" accept=".xls,.xlsx">
                                    <label class="custom-file-label" for="exampleInputFile">Selecciona el archivo</label>
                                    @error('file')
                                        <small class="text-danger">Verifique la Imagen</small>
                                    @enderror
                                </div>
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-success ml-1">
                                        <i class="fas fa-cloud-upload-alt fa-fw"></i>
                                        Subir Excel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </form>
        </div>
    </div>
@endsection

@push('script')
<script>
$(function () {
    bsCustomFileInput.init();
});
</script>
@endpush