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
        <i class="nav-icon fas fa-users"></i>
        <p>Vendor Accounts</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('vendor_invoice') }}" class="nav-link {{ Request::is('vendor_invoice') ? 'active' : '' }}">
        <i class="nav-icon  fas fa-file-invoice"></i>
        <p>Vendor Invoices</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('service_coordinator_accounts') }}" class="nav-link {{ Request::is('service_coordinator_accounts') ? 'active' : '' }}">
        <i class="nav-icon  fas fa-people-arrows"></i>
        <p>Service Coordinators</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('fetch_all_reports') }}" class="nav-link {{ Request::is('fetch_all_reports') ? 'active' : '' }}">
        <i class="nav-icon  fas fa-file-pdf"></i>
        <p>Reports</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('client-spending-plan.index') }}" class="nav-link {{ Request::is('client-spending-plan') ? 'active' : '' }}">
        <i class="nav-icon  fas fa-clipboard-list"></i>
        <p>Client Spending Plan</p>
    </a>
</li>
<li class="nav-item has-treeview {{ Request::is('clients*') || Request::is('employees*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link {{ Request::is('clients*') || Request::is('employees*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-dharmachakra"></i>
        <p>
            Configuration
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('service_category') }}" class="nav-link {{ Request::is('service_category') ? 'active' : '' }}">
                <i class="nav-icon 	fas fa-ethernet"></i>
                <p>Service Code Category</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('service_code') }}" class="nav-link {{ Request::is('service_code') ? 'active' : '' }}">
                <i class="nav-icon fas fa-file-code"></i>
                <p>Service Code</p>
            </a>
        </li>
    </ul>
</li>

<li class="nav-item">
    <a href="{{ route('register_admin') }}" class="nav-link {{ Request::is('register_admin') ? 'active' : '' }}">
        <i class="nav-icon fas fa-user-shield"></i>
        <p>Add Admin</p>
    </a>
</li>


