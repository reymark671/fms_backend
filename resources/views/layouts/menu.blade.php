<li class="nav-item">
    <a href="{{ route('home') }}" class="nav-link {{ Request::is('home') ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <p>Dashboard</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('clients') }}" class="nav-link {{ Request::is('clients') ? 'active' : '' }}">
        <i class="nav-icon fas fa-address-card"></i>
        <p>Clients</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('employees') }}" class="nav-link {{ Request::is('employees') ? 'active' : '' }}">
        <i class="nav-icon fas fa-id-card-alt"></i>
        <p>Employees</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('payables') }}" class="nav-link {{ Request::is('payables') ? 'active' : '' }}">
        <i class="nav-icon fas fa-file-invoice-dollar"></i>
        <p>Payables</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('payroll') }}" class="nav-link {{ Request::is('payroll') ? 'active' : '' }}">
        <i class="nav-icon fas fa-money-check"></i>
        <p>Payroll</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('timesheets') }}" class="nav-link {{ Request::is('timesheets') ? 'active' : '' }}">
        <i class="nav-icon fas fa-stopwatch"></i>
        <p>Timesheets</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('resources') }}" class="nav-link {{ Request::is('resources') ? 'active' : '' }}">
        <i class="nav-icon fas fa-folder-open"></i>
        <p>Resources</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('vendors') }}" class="nav-link {{ Request::is('vendors') ? 'active' : '' }}">
        <i class="nav-icon  fas fa-shopping-cart"></i>
        <p>Vendor Accounts</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('vendor_invoice') }}" class="nav-link {{ Request::is('vendor_invoice') ? 'active' : '' }}">
        <i class="nav-icon  fas fa-shopping-cart"></i>
        <p>Vendor Invoices</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('service_coordinator_accounts') }}" class="nav-link {{ Request::is('service_coordinator_accounts') ? 'active' : '' }}">
        <i class="nav-icon  fas fa-shopping-cart"></i>
        <p>Service Coordinators</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('fetch_all_reports') }}" class="nav-link {{ Request::is('fetch_all_reports') ? 'active' : '' }}">
        <i class="nav-icon  fas fa-shopping-cart"></i>
        <p>Reports</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('register_admin') }}" class="nav-link {{ Request::is('register_admin') ? 'active' : '' }}">
        <i class="nav-icon fas fa-user-shield"></i>
        <p>Add Admin</p>
    </a>
</li>

