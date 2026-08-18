<ul class="nav nav-tabs mb-4">
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.reports.sales') ? 'active' : '' }}" href="{{ route('admin.reports.sales') }}">
            <i class="bi bi-cash-stack"></i> Sales Report
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.reports.visits') ? 'active' : '' }}" href="{{ route('admin.reports.visits') }}">
            <i class="bi bi-geo-alt"></i> Visit Report
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.reports.orders') ? 'active' : '' }}" href="{{ route('admin.reports.orders') }}">
            <i class="bi bi-bag-check"></i> Order Report
        </a>
    </li>
</ul>