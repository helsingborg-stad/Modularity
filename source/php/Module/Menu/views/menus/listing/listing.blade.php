
@group([
    'columns' => $columns,
    'flexWrap' => 'wrap',
])
@foreach ($menu as $menuItem)
    <div class="u-display--flex u-justify-content--center">
        <div>
            @includeWhen(!empty($menuItem['label']), 'menus.listing.partials.parent')
            @includeWhen(!empty($menuItem['children']), 'menus.listing.partials.children')
        </div>
    </div>
@endforeach
@endgroup