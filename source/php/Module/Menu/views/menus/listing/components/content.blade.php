@group([
    'fluidGrid' => $columns,
    'flexWrap' => 'wrap',
    'display' => 'grid',
    'classList' => array_merge([
        'mod-menu__container',
    ], $gridClasses ?? [])
]) 
@foreach ($menu['items'] as $menuItem)
    <div class="mod-menu__item {{$menuItem['post_type'] ? 's-post-type-' . $menuItem['post_type'] : ''}}">
        @group([
            'display' => 'grid',
            'classList' => [
                'mod-menu__grid'
            ],
            'attributeList' => [
                'style' => 'grid-template-columns: auto 1fr;'
            ],
            'gap' => 1
        ])
            @includeWhen(!empty($menuItem['icon']['icon']), 'menus.listing.partials.icon')
            @includeWhen(!empty($menuItem['label']), 'menus.listing.partials.parent')
            @includeWhen(!empty($menuItem['description']), 'menus.listing.partials.description')
            @includeWhen(!empty($menuItem['children']), 'menus.listing.partials.children')
        @endgroup
    </div>
@endforeach
@endgroup