<ul class="nav nav-tabs mb-4">
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('distributor.reports.sales') ? 'active' : '' }}" href="{{ route('distributor.reports.sales') }}">Sales Report</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('distributor.reports.visits') ? 'active' : '' }}" href="{{ route('distributor.reports.visits') }}">Visit Report</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('distributor.reports.orders') ? 'active' : '' }}" href="{{ route('distributor.reports.orders') }}">Order Report</a>
    </li>
</ul>