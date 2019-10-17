<section class="sidebar">
    
    <div class="user-panel">
        <div class="pull-left image">
            <img src="{{Auth::user()->logo ? Auth::user()->getUserAvatar() : asset('/img/default-user.png')}}" class="img-circle user-image" alt="">
        </div>
        <div class="pull-left info">
            <p>{{Auth::user()->last_name || Auth::user()->first_name ? Auth::user()->last_name .' '. Auth::user()->first_name : Auth::user()->name}}</p>
            <a href="#"><i class="fa fa-circle text-success"></i> {{ __('On-line') }}</a>
        </div>
    </div>
    
    <ul class="sidebar-menu" data-widget="tree">        
                        
        @if(Auth::user()->hasPermissionTo('show projects'))
            <li class="{{ Request::is('admin/projects*') ? 'active' : '' }}">
                <a href="{{route('projects.index')}}">
                    <i class="fa fa-building"></i> <span>{{__('Projects')}}</span>                
                </a>
            </li>   
        @endif
        
        @if(Auth::user()->hasPermissionTo('show estimates'))
            <li class="{{ Request::is('admin/estimates*') ? 'active' : '' }}">
                <a href="{{route('estimates.index')}}">
                    <i class="fa fa-question"></i> <span>{{__('Estimates')}}</span>                
                </a>
            </li>   
        @endif
        
        @if(Auth::user()->hasPermissionTo('show dev_report'))
            <li class="{{ Request::is('admin/dev_reports*') ? 'active' : '' }}">
                <a href="{{route('dev_reports.index')}}">
                    <i class="fa fa-file"></i> <span>{{__('Report')}}</span>                
                </a>
            </li>   
        @endif
        
        @if(Auth::user()->hasAnyPermission(['show dev_reports']))
            <li class="treeview {{ Request::is('admin/reports*') ? 'active menu-open' : '' }}">
                <a href="#">
                    <i class="fa fa-files-o"></i> <span> {{ __('Reports') }}</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">

                    @if(Auth::user()->hasPermissionTo('show dev_reports'))
                        <li class="{{ Request::is('admin/reports/dev_reports*') ? 'active' : '' }}">
                            <a href="{{route('reports.dev.index')}}">
                                <i class="fa fa-tint"></i> <span>{{ __('By developers') }}</span>                
                            </a>
                        </li>
                    @endif
                    
                </ul>
            </li>
        @endif
        
        @if(Auth::user()->hasAnyPermission(['show roles', 'show permission', 'show users' ]))
            <li class="treeview {{ Request::is('admin/roles*') || Request::is('admin/permissions*') || Request::is('admin/users*') ? 'active menu-open' : '' }}">
                <a href="#">
                    <i class="fa fa-key"></i> <span> {{ __('Users') }}</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    @if(Auth::user()->hasPermissionTo('show users'))
                        <li class="{{ Request::is('admin/users*') ? 'active' : '' }}">
                            <a href="{{route('users.index')}}">
                                <i class="fa fa-user-secret"></i> <span>{{ __('Users') }}</span>                
                            </a>
                        </li> 
                    
                    @endif
                    @if(Auth::user()->hasPermissionTo('show roles'))
                        <li class="{{ Request::is('admin/roles*') ? 'active' : '' }}">
                            <a href="{{route('roles.index')}}"><i class="fa fa-unlock-alt"></i> {{ __('Roles') }}</a>
                        </li>
                    @endif
                        
                    @if(Auth::user()->hasPermissionTo('show permission'))
                        <li class="{{ Request::is('admin/permissions*') ? 'active' : '' }}" >
                            <a href="{{route('permissions.index')}}"><i class="fa fa-unlock"></i> {{ __('Permissions') }}</a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif
        
        @if(Auth::user()->hasAnyPermission([ 'setting translate', 'show settings' ]))
            <li class="treeview {{ Request::is('admin/settings*') ? 'active menu-open' : '' }}">
                <a href="#">
                    <i class="fa fa-cogs"></i> <span> {{ __('Settings') }}</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">

                    @if(Auth::user()->hasPermissionTo('show settings'))
                        <li class="{{ Request::is('admin/settings/settings*') ? 'active' : '' }}">
                            <a href="{{route('settings.index')}}">
                                <i class="fa fa-wrench"></i> <span>{{__('Settings')}}</span>                
                            </a>
                        </li>   
                        
                        <li class="{{ Request::is('admin/settings/skills*') ? 'active' : '' }}">
                            <a href="{{route('settings.skills.index')}}">
                                <i class="fa fa-cubes"></i> <span>{{__('Skills')}}</span>                
                            </a>
                        </li>   
                    @endif
                    
                    @if(Auth::user()->hasPermissionTo('setting office'))
                        <li class="{{ Request::is('admin/settings/positions*') ? 'active' : '' }}">
                            <a href="{{route('settings.positions.index')}}">
                                <i class="fa fa-graduation-cap"></i> <span>{{__('Positions')}}</span>                
                            </a>
                        </li>                            
                    @endif
                                        
                    @if(Auth::user()->hasPermissionTo('setting translate'))
                        <li class="{{ Request::is('admin/settings/translate*') ? 'active' : '' }}">
                            <a href="{{route('settings.translate.index')}}">
                                <i class="fa fa-language"></i> <span>{{__('Translates')}}</span>                
                            </a>
                        </li>   
                    @endif

                </ul>
            </li>
        @endif
        
    </ul>
</section>