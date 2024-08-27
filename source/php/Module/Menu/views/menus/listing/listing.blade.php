
@group([
    'columns' => $columns,
    'flexWrap' => 'wrap',
    'gap' => 6,
])
@foreach ($menu as $menuItem)
    <div class="mod-menu__item">
        @includeWhen(!empty($menuItem['label']), 'menus.listing.partials.parent')
        @includeWhen(!empty($menuItem['children']), 'menus.listing.partials.children')
    </div>
@endforeach
@endgroup