@extends('layouts.app')

@section('empty_auth') {{true}} @endsection

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('Error') }}!</div>

                    <div class="card-body">
                        <p>{{ __('Sorry, the page you are looking for was not found') }}</p>                        
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
