@extends('layouts.app')

@section('breadcrumb')
    <div class="row">
        <div class="col-lg-3 col-6">
            <!-- small card -->
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>44</h3>

                    <p>DropTime</p>
                </div>
                <div class="icon">
                    <i class="far fa-clock"></i>
                </div>
                
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="card">
    <div class="card-header">

    </div>
    <div class="card-body">
        <table>
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
