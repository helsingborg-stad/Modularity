@group([
    'fluidGrid' => $columns,
    'flexWrap' => 'wrap',
    'display' => 'grid',
    'classList' => array_merge([
        'mod-menu__container',
    ], $gridClasses ?? [])
]) 
@foreach ($menu['items'] as $index => $menuItem)
    <div class="mod-menu__item {{implode(' ', $menuItem['classList'] ?? [])}}" data-js-toggle-item="mod-menu-item-{{$ID}}-{{$index}}" data-js-toggle-class="is-expanded">
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
        @includeWhen(!empty($menuItem['children']), 'menus.listing.partials.expand')
    </div>
@endforeach
@endgroup