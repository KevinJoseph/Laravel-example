@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"> <h5>Agregar Nueva Inversión</h5></div>

                <div class="card-body">
                <investment-component></investment-component>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
