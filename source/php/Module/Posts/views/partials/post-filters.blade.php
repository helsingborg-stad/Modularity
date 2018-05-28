@if (!empty($enabledTaxonomyFilters))

    <section class="creamy creamy-border-bottom gutter-lg gutter-vertical sidebar-content-area post-filters">

        <form method="get" action="" class="container" id="post-filter">
            @if ( !empty($enabledTaxonomyFilters->category))
                @foreach ($enabledTaxonomyFilters->category as $taxKey => $taxonomy)

                    @if(count( $taxonomy->values ) > 1)
                        <div class="pos-relative">
                            <button type="button" class="btn"
                                    data-dropdown=".dropdown-{{ $taxKey }}"><?php printf(__('Select') . ' %s…',
                                    $taxonomy->label); ?></button>
                            <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-arrow-right dropdown-{{ $taxKey }}">

                                <?php
                                $dropdown = \Modularity\Module\Posts\PostsFilters::getMultiTaxDropdown($taxonomy,
                                    0, 'list-hierarchical'); ?>
                                {!! $dropdown !!}
                            </div>
                        </div>
                    @endif
                @endforeach
            @endif
            <div class="grid">
                @if ($frontEndFilters['front_end_tax_filtering_text_search'])
                    <div class="grid-sm-12 grid-md-auto">
                        <label for="filter-keyword" class="text-sm sr-only"><strong><?php _e('Search', 'municipio'); ?>
                                :</strong></label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-search"></i></span>
                            <input type="text" name="search" id="filter-keyword" class="form-control"
                                   value="{{ $searchQuery }}" placeholder="<?php _e('Search', 'municipio'); ?>">
                        </div>
                    </div>
                @endif

                @if ($frontEndFilters['front_end_tax_filtering_dates'])
                        <i class="btn pricon pricon-lg show-date-filter pricon-calendar"> <?php _e('Date', 'municipio'); ?> </i>
                        <div class="grid-sm-12 grid-md-auto hidden date-filter">
                        <label for="filter-date-from" class="text-sm sr-only"><strong><?php _e('Date published',
                                    'municipio'); ?>:</strong></label>
                        <div class="input-group">
                            <span class="input-group-addon"><?php _e('From', 'municipio'); ?>:</span>
                            <input type="text" name="{{$modId}}f" placeholder="<?php _e('From date', 'municipio'); ?>…"
                                   id="filter-date-from" class="form-control datepicker-range datepicker-range-from"
                                   value="{{ isset($_GET[$modId.'f']) && !empty($_GET[$modId.'f']) ? sanitize_text_field($_GET[$modId.'f']) : '' }}"
                                   readonly>
                            <span class="input-group-addon"><?php _e('To', 'municipio'); ?>:</span>
                            <input type="text" name="{{$modId}}t" placeholder="<?php _e('To date', 'municipio'); ?>"
                                   class="form-control datepicker-range datepicker-range-to"
                                   value="{{ isset($_GET[$modId.'t']) && !empty($_GET[$modId.'t']) ? sanitize_text_field($_GET[$modId.'t']) : '' }}"
                                   readonly>
                        </div>
                    </div>
                @endif

                @if($queryString)
                    <div class="grid-sm-12 hidden-sm hidden-xs grid-md-fit-content">
                        <a class="btn btn-block pricon pricon-close pricon-space-right"
                           href="/{{ $pageUrl }}"><?php _e('Clear filters', 'municipio'); ?></a>
                    </div>
                @endif
                <div class="grid-sm-12 grid-md-fit-content">
                    <input type="submit" value="<?php _e('Search', 'municipio'); ?>" class="btn btn-primary btn-block">
                </div>
            </div>


        </form>
    </section>
@endif
