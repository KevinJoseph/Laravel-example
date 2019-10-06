@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header"> <h5>Reportes</h5></div>
                <div class="card-body">
                <report-component></report-component>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
