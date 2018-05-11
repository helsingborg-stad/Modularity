@if (!empty($enabledTaxonomyFilters))

                <section class="creamy creamy-border-bottom gutter-lg gutter-vertical sidebar-content-area archive-filters">

                    <form method="get" action="{{ $pageUrl }}" class="container" id="archive-filter">

                            @foreach ($enabledTaxonomyFilters as $taxKey => $taxonomy)
                                @if(count($taxonomy->values) > 1)
                                    <div class="gutter gutter-top">
                                        <div class="grid">
                                            <div class="grid-xs-12">
                                                <ul>
                                                    <li class="highlighted-title"><h3>{{ $taxonomy->label }}</h3></li>
                                                    <ul class="nav nav-pills nav-horizontal nav-pills--badge">
                                                        @foreach ($taxonomy->values as $term)
                                                            <li>
                                                                <input id="segment-id-{{ $taxKey }}-{{ $term->slug }}" type="checkbox" name="filter[{{ $taxKey }}][]" value="{{ $term->slug }}" {{ checked(true, isset($_GET['filter'][$taxKey]) && is_array($_GET['filter'][$taxKey]) && in_array($term->slug, $_GET['filter'][$taxKey])) }}>
                                                                <a>
                                                                    <label for="segment-id-{{ $taxKey }}-{{ $term->slug }}" class="checkbox inline-block">{{ $term->name }}</label>
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach


                        <div class="grid">
                            @if ($frontEndFilters['front_end_tax_filtering_text_search'])
                                <div class="grid-sm-12 grid-md-auto">
                                    <label for="filter-keyword" class="text-sm sr-only"><strong><?php _e('Search', 'municipio'); ?>:</strong></label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-search"></i></span>
                                        <input type="text" name="s" id="filter-keyword" class="form-control" value="{{ $searchQuery }}" placeholder="<?php _e('Search', 'municipio'); ?>">
                                    </div>
                                </div>
                            @endif

                            @if ($frontEndFilters['front_end_tax_filtering_dates'])
                                <div class="grid-sm-12 grid-md-auto">
                                    <label for="filter-date-from" class="text-sm sr-only"><strong><?php _e('Date published', 'municipio'); ?>:</strong></label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><?php _e('From', 'municipio'); ?>:</span>
                                        <input type="text" name="from" placeholder="<?php _e('From date', 'municipio'); ?>…" id="filter-date-from" class="form-control datepicker-range datepicker-range-from" value="{{ isset($_GET['from']) && !empty($_GET['from']) ? sanitize_text_field($_GET['from']) : '' }}" readonly>
                                        <span class="input-group-addon"><?php _e('To', 'municipio'); ?>:</span>
                                        <input type="text" name="to" placeholder="<?php _e('To date', 'municipio'); ?>" class="form-control datepicker-range datepicker-range-to" value="{{ isset($_GET['to']) && !empty($_GET['to']) ? sanitize_text_field($_GET['to']) : '' }}" readonly>
                                    </div>
                                </div>
                            @endif

                            @if (isset($enabledTaxonomyFilters->primary) && !empty($enabledTaxonomyFilters->primary))
                                @foreach ($enabledTaxonomyFilters->primary as $taxKey => $tax)
                                    <div class="grid-sm-12 {{ $tax->type == 'multi' ? 'grid-md-fit-content' : 'grid-md-auto' }}">
                                        <label for="filter-{{ $taxKey }}" class="text-sm sr-only">{{ $tax->label }}</label>
                                        @if ($tax->type === 'single')
                                            @include('partials.archive-filters.select')
                                        @else
                                            @include('partials.archive-filters.button-dropdown')
                                        @endif
                                    </div>
                                @endforeach
                            @endif

                            @if($queryString)
                                <div class="grid-sm-12 hidden-sm hidden-xs grid-md-fit-content">
                                    <a class="btn btn-block pricon pricon-close pricon-space-right" href="{{ $archiveUrl }}"><?php _e('Clear filters', 'municipio'); ?></a>
                                </div>
                            @endif
                            <div class="grid-sm-12 grid-md-fit-content">
                                <input type="submit" value="<?php _e('Search', 'municipio'); ?>" class="btn btn-primary btn-block">
                            </div>
                        </div>


                    </form>
                </section>
    @endif
