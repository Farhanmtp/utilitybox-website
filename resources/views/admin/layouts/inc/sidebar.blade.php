<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url('admin') }}" class="brand-link">
        <img src="{{ asset('assets/admin/img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
             class="brand-image img-circle elevation-3"
             style="opacity: .8">
        <span class="brand-text font-weight-light">{{ config('app.name') }}</span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        {{--<div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ auth()->user()->avatar_url }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ auth()->user()->first_name }}</a>
            </div>
        </div>--}}
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar nav-legacy _nav-flat flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item">
                    <a href="{{ url('admin') }}" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                @if(hasPermission('suppliers.view'))
                    <li class="nav-item">
                        <a href="{{ route('admin.suppliers.index') }}" class="nav-link {{ isActive('suppliers') }}">
                            <i class="nav-icon fas fa-user-tie"></i>
                            <p>Suppliers</p>
                        </a>
                    </li>
                @endif
                @if(hasPermission('deals.view'))
                    <li class="nav-item">
                        <a href="{{ route('admin.deals.index') }}" class="nav-link {{ isActive('deals') }}">
                            <i class="nav-icon fas fa-file"></i>
                            <p>Deals</p>
                        </a>
                    </li>
                @endif
                @if(hasPermission('blogs.view|categories.view'))
                    <li class="nav-item {{ isActive('categories,posts') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ isActive('categories,posts') }}">
                            <i class="nav-icon fa fa-comment-alt"></i>
                            <p>
                                Blog
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @if(hasPermission('blogs.view'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.posts.index') }}" class="nav-link {{ isActive('posts') }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Posts</p>
                                    </a>
                                </li>
                            @endif
                            @if(hasPermission('categories.view'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.categories.index') }}" class="nav-link {{ isActive('categories') }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Categories</p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
                @if(hasPermission('users.view|roles.view'))
                    <li class="nav-item {{ isActive('users,roles') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ isActive('users,roles') }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                Users
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @if(hasPermission('users.view'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.users.index') }}" class="nav-link {{ isActive('users','list,edit') }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Users</p>
                                    </a>
                                </li>
                            @endif
                            @if(hasPermission('roles.view'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.roles.index') }}" class="nav-link {{ isActive('roles','create') }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Roles</p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
                @if(hasPermission('forms.view'))
                    <li class="nav-item">
                        <a href="{{ route('admin.messages.index') }}" class="nav-link {{ isActive('messages') }}">
                            <i class="nav-icon fas fa-list-alt"></i>
                            <p>Messages</p>
                        </a>
                    </li>
                @endif
                @if(hasPermission('settings.view'))
                    <li class="nav-item">
                        <a href="{{ route('admin.settings.index') }}" class="nav-link {{ isActive('settings') }}">
                            <i class="nav-icon fas fa-cogs"></i>
                            <p>Settings</p>
                        </a>
                    </li>
                @endif
                @if(hasPermission('super-admin'))
                    <li class="nav-item">
                        <a href="{{ route('admin.clear-cache') }}" class="nav-link">
                            <i class="nav-icon fas fa-sync"></i>
                            <p>Clear Cache</p>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
