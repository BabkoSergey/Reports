@extends('layouts.appAuth')

@section('empty_auth') {{true}} @endsection

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('Ошибка') }}!</div>

                    <div class="card-body">
                        <p>
                            {!! $exception->getMessage() !!}
                        </p>                        
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
