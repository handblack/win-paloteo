@extends('layouts.app')


@section('breadcrumb')
    <div class="content-header pb-0">
        <div class="container-fluid">
            <div class="row mb-3">
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
                            <h3>{{ $row->drop_call_seconds }}</h3>

                            <p>Segundos</p>
                        </div>
                        <div class="icon">
                            <i class="far fa-clock"></i>
                        </div>

                    </div>
                </div>
                <div class="col-lg-9 col-6">
                    <form action="{{ route('buzon.store') }}" method="POST">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <div class="card">
                            <div class="card-body">
                                <label class="mb-0" for="exampleInputRounded0">Ingrese el valor segundos <code>de 5 a
                                        360</code></label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group ">
                                            <input class="form-control form-control-lg text-right" type="number"
                                                name="drop_call_seconds" placeholder="" min="5" step="1"
                                                max="360" requireds>
                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-primary"> Modificar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="float-right">
                {{ $result->links('layouts.paginate') }}
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body table-responsive p-0">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th width="190">Fecha</th>
                        <th>Usuario</th>
                        <th>Segundos</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($result as $item)
                        <tr>
                            <td>{{ $item->created_at }}</td>
                            <td>{{ $item->lastname }}</td>
                            <td><span class="badge badge-success">{{ $item->droptime }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10">
                                No hay información!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="float-right">
                {{ $result->links('layouts.paginate') }}
            </div>
        </div>
    </div>
@endsection
