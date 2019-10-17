<header class="main-header">

    <!-- Logo -->
    <a href="{{url('admin')}}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini">
            <b>R</b>
        </span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>Reports</b> Urich</span>
    </a>

    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">{{ __('Toggle navigation') }}</span>
        </a>
          
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">                  
                <li class="dropdown notifications-menu">  
                    <a href="#" class="dropdown-toggle" data-toggle="modal" data-target="#chatModal">
                        <i class="fa fa-comments"></i>
                        <!--<span class="label label-success">4</span>-->
                    </a>                    
                </li>
                
                <li class="dropdown messages-menu">
                    @if(Config::get('app.locale_enabled'))
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="{{asset('/img/blank_flag.gif')}}" class="flag flag-{{App::getLocale()}}" alt="{{App::getLocale()}}" />
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header text-center text-bold">{{__('Language selection')}}</li>
                            <li>                            
                                <ul class="menu">                                  
                                    @foreach(Config::get('app.locale_enabled') as $locale)
                                        <li>
                                            <a href="#" class="jq_lang-set" data-locale="{{$locale}}">
                                                <img src="{{asset('/img/blank_flag.gif')}}" class="flag flag-{{$locale}}" alt="{{$locale}}" />
                                                {{ucfirst($locale)}} {{__($locale)}}
                                            </a>
                                        </li>                                        
                                    @endforeach
                                </ul>
                            </li>
                        </ul>                    
                    @endif
                </li>
                
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="{{Auth::user()->logo ? Auth::user()->getUserAvatar() : asset('/img/default-user.png')}}" class="user-image" alt="">
                        <span class="hidden-xs">{{Auth::user()->name}}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="{{Auth::user()->avatar ? Auth::user()->avatar : asset('/img/default-user.png')}}" class="img-circle" alt="">
                            <p>
                                {{Auth::user()->name}}
                                <small>
                                    @if(!empty(Auth::user()->getRoleNames()))
                                        @foreach(Auth::user()->getRoleNames() as $roleName)
                                            {{ __($roleName) }}&nbsp;
                                        @endforeach
                                    @endif                                    
                                </small>
                            </p>
                        </li>
                        <!-- Menu Body -->
                        
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="{{route('users.profile')}}" class="btn btn-default btn-flat">{{ __('Profile') }}</a>
                            </div>
                            <div class="pull-right">                                
                                <a class="btn btn-default btn-flat" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">{{ __('Выход') }}</a>            
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    </ul>
                </li>
                <!-- Control Sidebar Toggle Button -->
<!--                <li>
                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                </li>-->
            </ul>
        </div>

    </nav>
    
    @include('admin.templates.modal_chat')
    
</header>

@push('scripts')
    <script>
        $(function () {  
           $(document).on('click','.jq_lang-set',function (e){
                e.preventDefault(); 
                setCookie('setLang',$(this).attr('data-locale'));               
                window.location.reload(true);
            });              
        });
        
    </script>    
@endpush
