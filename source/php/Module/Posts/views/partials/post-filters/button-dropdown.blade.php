<?php $tax->slug = $taxKey; $dropdown = \Modularity\Module\Posts\PostsFilters::getMultiTaxDropdown($tax, 0, 'list-hierarchical'); ?>
<div class="pos-relative">
    <button type="button" class="btn" data-dropdown=".dropdown-{{ $taxKey }}"><?php printf(__('Select') . ' %s…', $tax->label); ?></button>
    <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-arrow-right dropdown-{{ $taxKey }}" style="right:0;">
        {!! $dropdown !!}
    </div>
</div>
