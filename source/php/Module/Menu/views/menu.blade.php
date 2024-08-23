@if (!empty($menu))
    @includeIf('menus.' . $displayAs . '.' . $displayAs)
@endif