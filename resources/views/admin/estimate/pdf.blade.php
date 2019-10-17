<!DOCTYPE html>
<html lang="en">
    <head>
        <title>{{$estimate->name}} - PDF</title>

        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="shortcut icon" href="favicon.ico">               
        <link href="{{ asset('/bower_components/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('/css/pdf.css') }}" rel="stylesheet">
    </head>

    <body>
        <div class="header">
            <table>
                <tr>
                    <td class="header-logo"><img src="{{ asset('/img/logo-urich.png') }}"></td>
                    <th class="header-name color-light-blue">{{ $estimate->name }}</th>
                    <td class="header-info">
                        <table>
                            <tr>
                                <th>E-mail:</th>
                                <td class="text-right"><a href="maimailto:info@urich.org">info@urich.org</a></td>
                            </tr>
                            <tr>
                                <th>Skype:</th>
                                <td class="text-right"><a href="#">developer158</a></td>
                            </tr>
                            <tr>
                                <th>Site:</th>
                                <td class="text-right"><a href="https://urich.org">https://urich.org</a></td>
                            </tr>
                        </table> 
                    </td>
                </tr>
            </table>                    
        </div>    

        <div class="content">
            <h3 class="color-light-blue text-center">Estimate (h)</h3>            
                @if(Auth::user()->hasPermissionTo('edit estimates') && $estimate->timing)
                    <table class="table table-striped bg-gray"> 
                        <thead class="thead-dark bg-light-blue">
                            <tr>
                                <th>{{ __('#') }}</th>
                                <th>{{ __('Task') }}</th>                        
                                <th>{{ __('Optimistic') }}</th>
                                <th>{{ __('Pessimistic') }}</th>
                                <th>{{ __('Comments') }}</th>                
                            </tr>
                        </thead>   
                        <tbody>       
                            @foreach(json_decode($estimate->timing)->blocks as $timingShowBlock)                        
                                @if($timingShowBlock->type == 'table')                                
                                    @include('admin.estimate.timing_block_table')
                                @else
                                    @include('admin.estimate.timing_block_note')
                                @endif
                            @endforeach
                        </tbody>
                        <tfoot>
                            @include('admin.estimate.timing_block_table_totals')
                        </tfoot>
                    </table>
                @endif                
        </div>
        
        <div class="footer">
            <h3 class="color-light-blue text-center">Important notes and assumptions</h3>
            
            <h4 class="color-light-blue">All contents of this proposal are based on the following assumptions:</h4>            
            <ul>
                <li>Project will be executed according to <b>[Time and material]</b> model of work</li>
                <li>Customer is reasonably available during the project for communication and clarification of requirements</li>
                <li>Estimate includes only requirements clearly and explicitly described by the Customer. Discovery of any implicit requirements or important project details may lead to revision of the estimate</li>
            </ul>
            
            <h4 class="color-light-blue">Optimistic and pessimistic estimates explanation:</h4>            
            <ul>
                <li>Optimistic estimate assumes both Customer and development team will work together on minimizing the development effort and budget. This is usually achieved by: prioritizing requirements by business impact, excluding low pririty requirements from scope, or simplifying them. Optimistic estimate also assumes technical risks related to working with legacy code or 3rd party products won't be a case.</li>
                <li>Pessimistic estimate includes reasonable time buffer to mitigate technical risks and "feature creep". It is meant as a prognosis of the development effort, which is based on past experence from similar projects into account.</li>
            </ul>            
        </div>

    </body>
</html>
