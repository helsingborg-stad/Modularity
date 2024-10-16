
@group([
    'classList' => [
        'mod-menu__items'
    ],
    'fluidGrid' => $columns,
    'flexWrap' => 'wrap',
    'gap' => 4
]) 
@foreach ($menu['items'] as $menuItem)
    <div class="mod-menu__item {{$menuItem['post_type'] ? 's-post-type-' . $menuItem['post_type'] : ''}}">
        @includeWhen(!empty($menuItem['label']), 'menus.listing.partials.parent')
        @includeWhen(!empty($menuItem['children']), 'menus.listing.partials.children')
    </div>
@endforeach
@endgroup