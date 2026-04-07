@php
$settings = \App\Models\Settings::first();
@endphp
<div id="sidebar">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header">
            <div class="d-flex justify-content-between">
                <div class="logo d-flex justify-content-center align-items-center">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center">
                        @if($settings && $settings->logo_url)
                            <img src="{{ asset($settings->logo_url) }}" alt="{{ $settings->logo_alt_text ?? 'Logo' }}" srcset="" style="max-width: 320px; max-height: 100px; object-fit: contain;">
                        @else
                            <img src="{{ asset('dist/assets/static/images/logo.svg') }}" alt="Logo" srcset="" style="max-width: 320px; max-height: 100px; object-fit: contain;">
                        @endif
                    </a>
                </div>
                <div class="toggler">
                    <a href="#" class="sidebar-hide d-xl-none d-block">
                        <i class="bi bi-x bi-middle"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="sidebar-menu">
            <ul class="menu">
                <li class="sidebar-title">Navigation</li>
                
                <li class="sidebar-item {{ request()->is('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}" class='sidebar-link'>
                        <i class="bi bi-grid-1x2-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                
                <li class="sidebar-title">Content Management</li>
                
                <li class="sidebar-item has-sub {{ request()->is('project-management*') ? 'active' : '' }}">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-folder"></i>
                        <span>Projects</span>
                    </a>
                    <ul class="submenu {{ request()->is('project-management*') ? 'active' : '' }}">
                        <li class="submenu-item {{ request()->is('project-management') ? 'active' : '' }}">
                            <a href="{{ route('projects.index') }}" class="submenu-link">All Projects</a>
                        </li>
                        <li class="submenu-item">
                            <a href="{{ route('projects.create') }}" class="submenu-link">
                                <i class="bi bi-plus-circle"></i> Add New
                            </a>
                        </li>
                    </ul>
                </li>
                
                <li class="sidebar-item has-sub {{ request()->is('testimonials*') ? 'active' : '' }}">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-chat-quote"></i>
                        <span>Testimonials</span>
                    </a>
                    <ul class="submenu {{ request()->is('testimonials*') ? 'active' : '' }}">
                        <li class="submenu-item {{ request()->is('testimonials') ? 'active' : '' }}">
                            <a href="{{ route('testimonials.index') }}" class="submenu-link">All Testimonials</a>
                        </li>
                        <li class="submenu-item">
                            <a href="{{ route('testimonials.create') }}" class="submenu-link">
                                <i class="bi bi-plus-circle"></i> Add New
                            </a>
                        </li>
                    </ul>
                </li>
                
                <li class="sidebar-item has-sub {{ request()->is('stats*') ? 'active' : '' }}">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-bar-chart-line"></i>
                        <span>Statistics</span>
                    </a>
                    <ul class="submenu {{ request()->is('stats*') ? 'active' : '' }}">
                        <li class="submenu-item {{ request()->is('stats') ? 'active' : '' }}">
                            <a href="{{ route('stats.index') }}" class="submenu-link">All Stats</a>
                        </li>
                        <li class="submenu-item">
                            <a href="{{ route('stats.create') }}" class="submenu-link">
                                <i class="bi bi-plus-circle"></i> Add New
                            </a>
                        </li>
                    </ul>
                </li>
                
                <li class="sidebar-item has-sub {{ request()->is('clients*') ? 'active' : '' }}">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-people"></i>
                        <span>Clients</span>
                    </a>
                    <ul class="submenu {{ request()->is('clients*') ? 'active' : '' }}">
                        <li class="submenu-item {{ request()->is('clients') ? 'active' : '' }}">
                            <a href="{{ route('clients.index') }}" class="submenu-link">All Clients</a>
                        </li>
                        <li class="submenu-item">
                            <a href="{{ route('clients.create') }}" class="submenu-link">
                                <i class="bi bi-plus-circle"></i> Add New
                            </a>
                        </li>
                    </ul>
                </li>
                
                <li class="sidebar-title">Dashboard Metrics</li>
                
                <li class="sidebar-item {{ request()->is('process-info*') ? 'active' : '' }}">
                    <a href="{{ route('process-info.index') }}" class='sidebar-link'>
                        <i class="bi bi-speedometer"></i>
                        <span>Process Info</span>
                    </a>
                </li>
                
                <li class="sidebar-title">System</li>
                
                <li class="sidebar-item {{ request()->is('audit-logs*') ? 'active' : '' }}">
                    <a href="{{ route('audit-logs.index') }}" class='sidebar-link'>
                        <i class="bi bi-clock-history"></i>
                        <span>Audit Logs</span>
                    </a>
                </li>
                
                <li class="sidebar-item {{ request()->is('settings*') ? 'active' : '' }}">
                    <a href="{{ route('settings.index') }}" class='sidebar-link'>
                        <i class="bi bi-sliders"></i>
                        <span>Settings</span>
                    </a>
                </li>
                
                <li class="sidebar-item">
                    <a href="{{ route('logout') }}" class='sidebar-link text-danger'>
                        <i class="bi bi-box-arrow-left"></i>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
        </div>
        <button class="sidebar-toggler btn x"><i class="bi bi-list"></i></button>
    </div>
</div>
