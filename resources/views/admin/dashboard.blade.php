@extends('admin.layouts.app')

@section('htmlheader_title') Reports Urich @endsection

@section('content')

<div class="row">
    <div class="col-xs-12">        
        @include('admin.templates.action_notifi')
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        
    </div>
</div>
      
@endsection

@push('styles')    

@endpush

@push('scripts')            
    <script>        
        $(function () { 
            $( document ).ready(function() {
                                
            });
        });
    </script>
@endpush

