@if (!empty($enabledTaxonomyFilters))
    <section class="sidebar-content-area post-filters">
        <form method="get" action="" id="post-filter"
              class="gutter-lg gutter-vertical @if (!$frontEndFilters['front_end_hide_date']) disable-post-filter-js @endif">
            @if (!$frontEndFilters['front_end_display'])
                @if ( !empty($enabledTaxonomyFilters->category))
                    @if ( $frontEndFilters['front_end_tax_filtering_taxonomy'] )
                        <div class="clearfix u-mb-1">
                            @foreach ($enabledTaxonomyFilters->category as $taxKey => $taxonomy)
                                @if(count( $taxonomy->values ) > 1)
                                    <div class="pos-relative pull-left u-mr-3">
                                        <button type="button" class="btn "
                                                data-dropdown=".dropdown-{{ $taxKey }}"><?php printf(__('Select') . ' %s…',
                                                $taxonomy->label); ?></button>
                                        <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-arrow-left dropdown-{{ $taxKey }}">
                                            <?php
                                            $dropdown = \Modularity\Module\Posts\PostsFilters::getMultiTaxDropdown($taxonomy,
                                                0, 'list-hierarchical'); ?>
                                            {!! $dropdown !!}
                                        </div>

                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                @endif
            @endif
            <div class="o-grid">
                @if ($frontEndFilters['front_end_tax_filtering_text_search'])
                    <div class="o-grid-auto ">
                        <label for="filter-keyword" class="text-sm sr-only"><strong><?php _e('Search', 'municipio'); ?>
                                :</strong></label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-search"></i></span>
                            <input type="text" name="search" id="filter-keyword" class="form-control "
                                   value="{{ $searchQuery }}" placeholder="<?php _e('Search', 'municipio'); ?>">
                        </div>
                    </div>
                @endif

                @if ($frontEndFilters['front_end_tax_filtering_dates'])
                    @if ($frontEndFilters['front_end_hide_date'])
                        <div data-tooltip="<?php _e('Date', 'municipio'); ?>" data-tooltip-top  class="o-grid-fit@md u-pt-1">
                            <i id="show-date-filter" data-toogle=".date-filter"
                                                            class="hidden-xs cursor pricon-calendar pricon pricon-lg toogle show-date-filter"></i></div>@endif
                    <div id="date-filter"
                         class="o-grid-6@md  @if ($frontEndFilters['front_end_hide_date']) hidden date-filter @endif ">
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
                @if ($frontEndFilters['front_end_display'])
                    @if ( !empty($enabledTaxonomyFilters->category))
                        @foreach ($enabledTaxonomyFilters->category as $taxKey => $taxonomy)

                            @if(count( $taxonomy->values ) > 1)
                                <div class="pos-relative o-grid-auto">
                                    <button type="button" class="btn "
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
                @endif
                @if($queryString)
                    <div class="hidden-sm hidden-xs o-grid-fit@md u-pt-1" data-tooltip="<?php _e('Clear filters', 'municipio'); ?>" data-tooltip-top>
                        <a  class="pricon-lg pricon pricon-close pricon-space-right"
                           href="/{{ $pageUrl }}"></a>
                    </div>
                @endif
                <div class="o-grid-12@am o-grid-fit@md u-pt-1@sm u-pt-1@xs">
                    <input type="submit" value="{{$frontEndFilters['front_end_button_text']}}"
                           class="btn btn-primary btn-block">
                </div>
            </div>
        </form>
    </section>
@endif
