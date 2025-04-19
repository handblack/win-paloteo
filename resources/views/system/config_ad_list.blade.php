@extends('layouts.app')

@push('header')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('breadcrumb')
    <div class="content-header pb-0">
        <div class="container-fluid">
            <div class="row mb-0">
                <div class="col-sm-6">
                    <h1 class="m-0"><i class="fas fa-server"></i> ConfigAD [Campañas]</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">Sistema</li>
                        <li class="breadcrumb-item active">ConfigAD</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-header pt-0">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-md-5 mt-2">
                    <form action="{{ route('groupad.index') }}" method="GET">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <div class="btn-toolbar" role="toolbar">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <a href="#" onclick="location.reload()" class="btn btn-secondary mr-2">
                                        <i class="fas fa-sync-alt fa-fw"></i>
                                    </a>
                                </div>
                                <input type="text" name="q" value="{{ $q }}"
                                    class="form-control float-right" placeholder="Buscar.." autofocus>
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-secondary">
                                        <i class="fas fa-search"></i>
                                        <span class="d-md-inline-block d-none">BUSCAR</span>
                                    </button>
                                    <a href="{{ route('groupad.create') }}" class="btn btn-success">
                                        <i class="far fa-plus-square fa-fw"></i>
                                        <span class="d-md-inline-block d-none">NUEVO</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-7 mt-2">
                    {{ $result->links('layouts.paginate') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="card">
    <div class="card-body table-responsive p-0">
        <table class="table table-hover table-sm table-hover">
            <tbody>
                @forelse ($result as $item)
                    <tr id="tr-{{ $item->id }}">
                        <td class="{{ $item->isactive == 'N' ? 'tachado' : '' }}"><strong>{{ $item->configname }}</strong></td>
                        <td class="text-right text-nowrap">
                            <a href="{{ route('groupadline.create_key', $item->token) }}">
                                <i class="fas fa-key fa-fw"></i> Nuevo KEY
                            </a> |
                            <a href="{{ route('groupad.edit', $item->token) }}">
                                <i class="far fa-edit fa-fw"></i>
                            </a> |
                            <a href="#" class="delete-record" data-id="{{ $item->id }}"
                                data-url="{{ route('groupad.destroy', $item->token) }}">
                                <i class="far fa-trash-alt fa-fw"></i>
                            </a>
                        </td>
                    </tr>
                    @if(count($item->lines))
                        @foreach ($item->lines as $it)
                            <tr id="tr-x{{ $it->id }}">
                                <td class="text-monospace pl-5">
                                    <span class="badge badge-secondary">{{ $it->orden }}</span>
                                    {{ $it->groupname }}
                                </td>
                                <td class="text-right text-nowrap">
                              

                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                        <i class="far fa-hand-point-right"></i>
                                    </a>

                                    <div class="dropdown-menu" style="">
                                        <a class="dropdown-item" href="{{ route('groupadline.edit', $it->token) }}"><i class="far fa-edit fa-fw"></i> Modificar</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item delete-record" href="#" data-id="x{{ $it->id }}" data-url="{{ route('groupadline.destroy', $it->token) }}"><i class="far fa-trash-alt fa-fw"></i> Eliminar</a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                @empty
                    <tr>
                        <td colspan="10">No hay registross!</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="row pb-3">
    <div class="col-md-5"></div>
    <div class="col-md-7">
        {{ $result->links('layouts.paginate') }}
    </div>
</div>
@endsection

