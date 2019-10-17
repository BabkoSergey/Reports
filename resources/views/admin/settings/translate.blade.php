@extends('admin.layouts.app')

@section('htmlheader_title') {{ __('Переводы') }} @endsection

@section('content')

<div class="row">
    <div class="col-xs-12">
        
        @include('admin.templates.action_notifi')
        
        <div class="box">
            <div class="box-header">
                <form method="POST" action="{{ route('translations.create') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <label>Key:</label>
                            <input type="text" name="key" class="form-control" placeholder="Enter Key...">
                        </div>
                        <div class="col-md-4">
                            <label>Value:</label>
                            <input type="text" name="value" class="form-control" placeholder="Enter Value...">
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-success">{{ __('Добавить') }}</button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <table class="table table-hover table-bordered">
                    <thead>
                    <tr>
                        <th>Key</th>    
                            @foreach($columns as $language => $colValue)
                                @if($language != 'key')                                    
                                    <th>
                                        <img src="{{asset('/img/blank_flag.gif')}}" class="flag flag-{{$language}}" alt="en" />
                                        <span class="text-capitalize">{{ $language }}</span>
                                    </th>
                                @endif
                            @endforeach
                            
                        <th width="80px;">{{ __('Actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>                        
                            @foreach($columns['key'] as $columnKey => $columnValue)
                                <tr>
                                    <td><a href="#" class="translate-key" data-title="Enter Key" data-type="text" data-pk="{{ $columnKey }}" data-url="{{ route('translation.update.json.key') }}">{{ $columnKey }}</a></td>
                                    @foreach($columns as $language => $colValue)
                                        @if($language != 'key')                                    
                                            <td><a href="#" data-title="Enter Translate" class="translate" data-code="{{ $language }}" data-type="textarea" data-pk="{{ $columnKey }}" data-url="{{ route('translation.update.json') }}">{{ isset($columns[$language][$columnKey]) ? $columns[$language][$columnKey] : '' }}</a></td>
                                        @endif
                                    @endforeach
                                    <td><button data-action="{{ route('translations.destroy', $columnKey) }}" class="btn btn-danger btn-xs remove-key">Delete</button></td>
                                </tr>
                            @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->

@endsection

@push('styles')    
    <link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>    
@endpush

@push('scripts')    
    <script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
    
    <script>
        $(function () {  
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            $('.translate').editable({
                params: function(params) {
                    params.code = $(this).editable().data('code');
                    return params;
                }
            });


            $('.translate-key').editable({
                validate: function(value) {
                    if($.trim(value) == '') {
                        return 'Key is required';
                    }
                }
            });


            $('body').on('click', '.remove-key', function(){
                var cObj = $(this);


                if (confirm("Are you sure want to remove this item?")) {
                    $.ajax({
                        url: cObj.data('action'),
                        method: 'DELETE',
                        success: function(data) {
                            cObj.parents("tr").remove();
                            alert("Your imaginary file has been deleted.");
                        }
                    });
                }


            });
        }); 
        
    </script>
@endpush