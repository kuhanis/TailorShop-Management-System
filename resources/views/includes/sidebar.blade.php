<!-- BEGIN: Main Menu-->

<div class="main-menu material-menu menu-fixed menu-light menu-accordion menu-shadow " data-scroll-to-active="true">
    <div class="user-profile text-center">
        <img class="user-img img-fluid rounded-circle" 
             src="@if(!empty(auth()->user()->avatar))
                    {{ asset('storage/' . auth()->user()->avatar) }}
                 @else 
                    {{ asset('app-assets/images/portrait/small/avatar-s-1.png') }} 
                 @endif"
        />
        <div class="role-text">
            @if(auth()->user()->name === 'admin')
                Admin
            @else
                Staff
            @endif
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

            <li class="nav-item">
                <a href="#">
                    <i class="ft ft-shopping-cart"></i>
                    <span class="menu-title" data-i18n="Order">Orders</span>
                </a>
                <ul class="menu-content">
                    <li class="{{ Request::routeIs('orders') ? 'active' : '' }}">
                        <a href="{{route('orders')}}">
                            <i class="material-icons"></i>
                            <span data-i18n="Orders">Orders List</span>
                        </a>
                    </li>

                    <li class="{{ Request::routeIs('orders.retention') ? 'active' : '' }}">
                        <a href="{{route('orders.retention')}}">
                            <i class="material-icons"></i>
                            <span data-i18n="Retention">Retention</span>
                        </a>
                    </li>

                    <li class="{{ Request::routeIs('orders.history') ? 'active' : '' }}">
                        <a href="{{route('orders.history')}}">
                            <i class="material-icons"></i>
                            <span data-i18n="History">Order History</span>
                        </a>
                    </li>
                </ul>
            </li>

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
