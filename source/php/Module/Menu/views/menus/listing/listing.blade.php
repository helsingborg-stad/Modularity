
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
        @group([
            'display' => 'grid',
            'attributeList' => [
                'style' => 'grid-template-columns: auto 1fr;'
            ],
            'gap' => 1
        ])
            @includeWhen(!empty($menuItem['icon']['icon']), 'menus.listing.partials.icon')
            <div>
                @includeWhen(!empty($menuItem['label']), 'menus.listing.partials.parent')
                @includeWhen(!empty($menuItem['children']), 'menus.listing.partials.children')
            </div>
        @endgroup
    </div>
@endforeach
@endgroup