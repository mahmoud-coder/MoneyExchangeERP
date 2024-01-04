<ul class="menu-sub">
@foreach($menu as $submenu)
    @php
    $activeClass = null;
    $currentRouteName =  Route::currentRouteName();
    if($currentRouteName === $submenu['route']) {
          $activeClass = 'active';
    }elseif(isset($submenu['submenu'])){
        if(gettype($submenu['route']) === 'array'){
            foreach($submenu['route'] as $route){
                if(str_contains($currentRouteName, $route) && strpos($currentRouteName, $route) === 0){
                    $activeClass = 'active open';
                }
            }
        }else{
            if(str_contains($currentRouteName, $submenu['route']) && strpos($currentRouteName, $submenu['route']) === 0) {
                $activeClass = 'active open';
            }
        }
    }
    @endphp
    <li class="menu-item {{$activeClass}}">
        <a href="{{ isset($submenu['url']) ? url($submenu['url']) : 'javascript:void(0)' }}" class="menu-link{{ isset($submenu['submenu']) ? ' menu-toggle' : '' }}" {!! isset($menu['target']) ? 'target="'.$menu['target'].'"' : '' !!}>
          @if (isset($submenu['icon']))
          <i class="{{ $submenu['icon'] }}"></i>
          @endif
          <div>{{ isset($submenu['name']) ? __($submenu['name']) : '' }}</div>
        </a>
        @if (isset($submenu['submenu']))
          @include('admin.slayouts.sections.menu.submenu',['menu' => $submenu['submenu']])
        @endif
      </li>
@endforeach
</ul>