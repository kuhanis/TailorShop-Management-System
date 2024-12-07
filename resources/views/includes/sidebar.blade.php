<!-- BEGIN: Main Menu-->

<div class="main-menu material-menu menu-fixed menu-light menu-accordion menu-shadow " data-scroll-to-active="true">
    <div class="user-profile">
        <div class="user-info text-center pt-1 pb-1"><img class="user-img img-fluid rounded-circle" src="@if(!empty(auth()->user()->avatar)){{asset('storage/avatars/'.auth()->user()->avatar)}}@else {{asset('app-assets/images/portrait/small/avatar-s-1.png')}} @endif"/>
            <div class="name-wrapper d-block dropdown">
                <a class="white dropdown-toggle ml-2" id="user-account" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="user-name">{{auth()->user()->name}}</span>
                </a>
                <div class="text-light">{{ucfirst(auth()->user()->role)}}</div>
                <div class="dropdown-menu arrow" aria-labelledby="dropdownMenuLink">
                    <a href="{{route('user-profile')}}" class="dropdown-item">
                        <i class="material-icons align-middle mr-1">person</i>
                        <span class="align-middle">Profile</span>
                    </a>


                    <a href="{{route('logout')}}" class="dropdown-item">
                        <i class="material-icons align-middle mr-1">power_settings_new</i>
                        <span class="align-middle">Log Out</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <li class="nav-item {{ Request::routeIs('dashboard') ? 'active' : '' }}">
                <a href="{{route('dashboard')}}">
                    <i class="material-icons">dashboard</i>
                    <span class="menu-title" data-i18n="Dashboard">Dashboard</span>
                </a>
            </li>

            @if(auth()->user()->staff && auth()->user()->staff->role !== 'staff')
                <li class="{{ Request::routeIs('users') ? 'active' : '' }}">
                    <a href="{{route('users')}}">
                        <i class="material-icons">people_outline</i>
                        <span class="menu-title" data-i18n="Users">Staff Management</span>
                    </a>
                </li>
            @endif

            <li class=" nav-item">
                <a href="#">
                    <i class="ft ft-users"></i>
                    <span class="menu-title" data-i18n="Measurement Settings">Customer Details</span>
                </a>
                <ul class="menu-content">
                    <li class="{{ Request::routeIs('customers') ? 'active' : '' }} nav-item">
                        <a href="{{route('customers')}}">
                            <i class="material-icons"></i>
                            <span data-i18n="Customers">Basic Info</span>
                        </a>
                    </li>
        
                    <li class="{{ Request::routeIs('measurement-parts') ? 'active' : '' }}">
                        <a class="menu-item" href="{{route('measurement-parts')}}">
                            <i class="material-icons"></i>
                            <span data-i18n="Measurement Parts">Body Measurements</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="{{ Request::routeIs('orders') ? 'active' : '' }} nav-item">
                <a href="{{route('orders')}}">
                    <i class="ft ft-shopping-cart"></i>
                    <span class="menu-title" data-i18n="Order">Orders</span>
                </a>
            </li>

            @if(auth()->user()->staff && auth()->user()->staff->role !== 'staff')
                <li class=" nav-item">
                    <a href="{{route('measurement-parts')}}">
                        <i class="material-icons">face</i>
                        <span class="menu-title" data-i18n="Measurement Settings">Measurement Settings</span>
                    </a>
                </li>
            @endif

            @if(auth()->user()->staff && auth()->user()->staff->role !== 'staff')
                <li class="nav-item {{ Request::routeIs('settings') ? 'active' : '' }}">
                    <a href="{{route('settings')}}">
                        <i class="material-icons">settings</i>
                        <span class="menu-title" data-i18n="Setting">Settings</span>
                    </a>
                </li>
            @endif
        </ul>
    </div>
</div>

<!-- END: Main Menu-->
