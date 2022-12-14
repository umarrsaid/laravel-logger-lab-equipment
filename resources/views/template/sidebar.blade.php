@role('superuser|Lab|user')
<li class="nav-item start @stack('dashboard')">
    <a href="{{url('/')}}" class="nav-link nav-toggle">
        <i class="icon-home"></i>
        <span class="title">Dashboard</span>
    </a>
</li>
@endrole
@role('superuser')
<li class="nav-item  @stack('data-master')">
    <a href="javascript:;" class="nav-link nav-toggle">
        <i class="icon-layers"></i>
        <span class="title">Data Master</span>
        @stack('selected_dm')
        <span class="arrow @stack('open_dm')"></span>
    </a>
    <ul class="sub-menu">
        <li class="nav-item @stack('active_machine')">
            <a href="{{url('/machine')}}" class="nav-link ">
                <span class="title">Machine</span>
                @stack('selected_mac')
            </a>
        </li>
        <li class="nav-item @stack('active_measure')">
            <a href="{{url('/measure')}}" class="nav-link ">
                <span class="title">Measure</span>
                @stack('selected_mea')
            </a>
        </li>
        <li class="nav-item @stack('active_logger')">
            <a href="{{url('/logger')}}" class="nav-link ">
                <span class="title">Logger</span>
                @stack('selected_log')
            </a>
        </li>
        <li class="nav-item @stack('active_pool')">
            <a href="{{url('/location')}}" class="nav-link ">
                <span class="title">Location</span>
                @stack('selected_pol')
            </a>
        </li>
        <li class="nav-item @stack('active_user')">
            <a href="{{url('/user')}}" class="nav-link ">
                <span class="title">User</span>
                @stack('selected_usr')
            </a>
        </li>
    </ul>
</li>
@endrole
@role('superuser|Lab|user')
<li class="nav-item  @stack('activity')">
    <a href="javascript:;" class="nav-link nav-toggle">
        <i class="icon-bulb"></i>
        <span class="title">Activity</span>
        @stack('selected_act')
        <span class="arrow @stack('open_act')"></span>
    </a>
    <ul class="sub-menu">
        <li class="nav-item @stack('active_data_log')">
            <a href="{{url('/data-log')}}" class="nav-link ">
                <span class="title">Data Logging</span>
                @stack('selected_dlog')
            </a>
        </li>
    </ul>
</li>
@endrole
