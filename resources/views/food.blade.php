@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">Platos</div>

                <div class="card-body">
                <food-component></food-component>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
