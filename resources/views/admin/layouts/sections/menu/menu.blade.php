<!-- Menu -->
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
    <a href="{{url('/')}}" class="app-brand-link">
        <span class="app-brand-logo demo">
        @include('SVGs.micro-logo')
        </span>
        <span class="app-brand-text menu-text">UAB Money Beat</span>
    </a>

    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
        <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
        <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
    </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        @foreach($main_menu as $menu)
        @if (isset($menu['menuHeader']))
            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">{{ $menu['menuHeader'] }}</span>
            </li>
        @else
            @php
                $activeClass = null;
                $currentRouteName = Route::currentRouteName();
                if ($currentRouteName === $menu['route']) {
                    $activeClass = 'active';
                }elseif(isset($menu['submenu'])){
                    if(gettype($menu['route']) === 'array'){
                        foreach($menu['route'] as $route){
                            if(str_contains($currentRouteName, $route) && strpos($currentRouteName, $route) === 0) {
                                $activeClass = 'active open';
                            }
                        }
                    }else{
                        if(str_contains($currentRouteName, $menu['route']) && strpos($currentRouteName,$menu['route']) === 0){
                            $activeClass = 'active open';
                        }
                    }
                }
            @endphp
            <li class="menu-item {{$activeClass}}">
                <a href="{{ isset($menu['url']) ? url($menu['url']) : 'javascript:void(0);' }}" class="menu-link{!! isset($menu['submenu']) ? ' menu-toggle' : '' !!}" {!! isset($menu['target']) ? 'target="'.$menu['target'].'"' : '' !!}>
                    @isset($menu['icon'])
                    <i class="{{ $menu['icon'] }}"></i>
                    @endisset
                    <div>{{ isset($menu['name']) ? __($menu['name']) : '' }}</div>
                    @isset($menu['badge'])
                    <div class="badge bg-label-{{ $menu['badge'][0] }} rounded-pill ms-auto">{{ $menu['badge'][1] }}</div>
                    @endisset
                </a>
                @isset($menu['submenu'])
                @include('admin.layouts.sections.menu.submenu',['menu' => $menu['submenu']])
                @endisset
            </li>
        @endif
        @endforeach
    </ul>
</aside>
<!-- / Menu -->