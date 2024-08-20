@if (!empty($menu))
    @foreach ($menu as $menuItem)
        @includeWhen(!empty($menuItem['label']), 'partials.parent')
        @includeWhen(!empty($menuItem['children']), 'partials.children')
    @endforeach
@endif