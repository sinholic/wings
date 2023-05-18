<div class="navigation-menu-tab">
    <div>
        <div class="navigation-menu-tab-header" data-toggle="tooltip" title="{{ Auth::user()->username }}" data-placement="right">
            <a href="#" class="nav-link" data-toggle="dropdown" aria-expanded="false">
                <figure class="avatar avatar-sm">
                    <img src="https://ui-avatars.com/api/?name={{ \Auth::user()->username }}" class="rounded-circle" alt="avatar">
                </figure>
            </a>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-big">
                <div class="p-3 text-center" data-backround-image="{{ asset('assets/media/image/image1.jpg') }}">
                    <figure class="avatar mb-3">
                        <img src="https://ui-avatars.com/api/?name={{ \Auth::user()->username }}" class="rounded-circle" alt="image">
                    </figure>
                    <h6 class="d-flex align-items-center justify-content-center">
                        {{ Auth::user()->username }}
                        <a href="#" class="btn btn-primary btn-sm ml-2" data-toggle="tooltip" title="Edit profile">
                            <i data-feather="edit-2"></i>
                        </a>
                    </h6>
                </div>
                <div class="dropdown-menu-body">
                    <div class="list-group list-group-flush">
                        <a href="#" class="list-group-item">Profile</a>
                        <a href="{{ route('logout') }}" class="list-group-item text-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Sign Out!
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="flex-grow-1">
        <ul>
            @foreach($menus as $menu)
                <li>
                    <a class="{{ request()->is($menu->menu_tab_prefix.'*') ? 'active' : '' }}" href="#" data-toggle="tooltip" data-placement="right" title="{{$menu->menu_tab_label}}"
                    data-nav-target="#{{$menu->menu_tab_prefix}}">
                        <i data-feather="{{$menu->menu_tab_icon}}"></i>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
    <div>
        <ul>
            <li>
                <a href="{{ route('logout') }}" data-toggle="tooltip" data-placement="right" title="Logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i data-feather="log-out"></i>
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </li>
        </ul>
    </div>
</div>
