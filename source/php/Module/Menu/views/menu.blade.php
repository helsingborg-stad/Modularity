@if (!empty($menu))
<div class="mod-menu {{'mod-menu--' . $displayAs}}">
    @includeIf('menus.' . $displayAs . '.' . $displayAs)
</div>
@endif