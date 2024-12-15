<li>
    <a href="javascript:void(0)">
        <i class="la la-shopping-cart"></i>
        <span>Orders</span>
        <span class="menu-arrow"></span>
    </a>
    <ul style="display: none;">
        <li>
            <a href="{{ route('orders') }}">
                <i class="la la-list"></i>
                <span>Orders List</span>
            </a>
        </li>
        <li>
            <a href="{{ route('orders.retention') }}">
                <i class="la la-archive"></i>
                <span>Retention</span>
            </a>
        </li>
    </ul>
</li> 