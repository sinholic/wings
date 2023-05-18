<!-- begin::navigation menu -->
<div class="navigation-menu-body">

    <!-- begin::navigation-logo -->
    <div>
        <div id="navigation-logo">
            <a href="{{ route('index') }}">
                <img class="logo" src="{{ asset('assets/media/image/logo.png') }}" alt="image" style="width: 120px;">
            </a>
        </div>
    </div>
    <!-- end::navigation-logo -->

    <div class="navigation-menu-group">
        @foreach($menus as $menu)
            <div class="{{ request()->is($menu->menu_tab_prefix.'*') ? 'open' : '' }}" id="{{ $menu->menu_tab_prefix }}">
                <ul>
                    <li class="navigation-divider">{{ $menu->menu_tab_label }}</li>
                    @foreach($menu->child_menus as $child_menu)
                        <li><a class="{{ request()->is($menu->menu_tab_prefix.'/'.$child_menu->prefix.'*') ? 'active' : '' }}" href="{{ route($child_menu->link) }}">{{ $child_menu->label }}</a></li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </div>
</div>
<!-- end::navigation menu -->