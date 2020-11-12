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
                                    <div class="o-grid-12 o-grid-auto@sm">
                                        @php $dropdown = \Modularity\Module\Posts\PostsFilters::getMultiTaxDropdown($taxonomy,
                                        0, 'list-hierarchical'); @endphp
                                        @select($dropdown)
                                        @endselect
                                    </div>
     
                                @endif
                            @endforeach
                            
                            
                            
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
                        <div class="input-group">
                            
                            @field( [
                                'type' => 'text',
                                'value' => $searchQuery,
                                'label' => __('Search', 'municipio'),
                                'classList' => ['u-width--100'],
                                'attributeList' => [
                                    'type' => 'text',
                                    'name' => 'search'
                                ],
                                'required' => false,
                            ])
                            @endfield
          
                        </div>
                    </div>
                @endif
    
             
                
                
                @if ($frontEndFilters['front_end_tax_filtering_dates'])
                    @if ($frontEndFilters['front_end_hide_date'])
                        <div data-tooltip="<?php _e('Date', 'municipio'); ?>" data-tooltip-top  class="grid-md-fit-content u-pt-1">
                            <!-- <i id="show-date-filter" data-toogle=".date-filter"
                class="hidden-xs cursor pricon-calendar pricon pricon-lg toogle show-date-filter"></i></div> -->
                    @endif
    
                            <div class="{{ $frontEndFilters['front_end_tax_filtering_dates'] ? '' : 'u-display--none' }}" js-toggle-item="dateWrapper" js-toggle-class="u-display--none">
                                <div class="o-grid">
                                    <div class="o-grid-12 o-grid-auto@sm">
                                        @field([
                                            'type' => 'datepicker',
                                            'value' =>  isset($_GET[$modId.'f']) && !empty($_GET[$modId.'f']) ? sanitize_text_field($_GET[$modId.'f']) : '',
                                            'label' => __('From date', 'municipio'),
                                            'attributeList' => [
                                                'type' => 'text',
                                                'name' => $modId.'f',
                                                'data-invalid-message' => __('dateInvalid', 'municipio'),
                                                'js-archive-filter-from',
                                                ''
                                            ],
                                            'required' => false,
                                            'datepicker' => [
                                                'title'                 => __('From date', 'municipio'),
                                                'minDate'               => "6/29/1997",
                                                'maxDate'               => "tomorrow",
                                                'required'              => true,
                                                'showResetButton'       => true,
                                                'showDaysOutOfMonth'    => true,
                                                'showClearButton'       => true,
                                                'hideOnBlur'            => true,
                                                'hideOnSelect'          => false,
                                            ]
                                        ])
                                        @endfield
                                    </div>
                                    <div class="o-grid-12 o-grid-auto@sm">
                                        @field([
                                            'type' => 'datepicker',
                                            'value' => isset($_GET[$modId.'t']) && !empty($_GET[$modId.'t']) ? sanitize_text_field($_GET[$modId.'t']) : '',
                                            'label' => __('To date', 'municipio'),
                                            'attributeList' => [
                                                'type' => 'text',
                                                'name' => $modId.'t',
                                                'data-invalid-message' => __('dateInvalid', 'municipio'),
                                                'js-archive-filter-to' => ''
                                            ],
                                            'required' => false,
                                            'datepicker' => [
                                                'title'                 => __('To date', 'municipio'),
                                                'minDate'               => "6/29/1997",
                                                'maxDate'               => "tomorrow",
                                                'required'              => true,
                                                'showResetButton'       => true,
                                                'showDaysOutOfMonth'    => true,
                                                'showClearButton'       => true,
                                                'hideOnBlur'            => true,
                                                'hideOnSelect'          => false,
                                            ]
                                        ])
                                        @endfield
                                    </div>
                                </div>
                            </div>
                            
                            
                @endif
                
                @if ($frontEndFilters['front_end_display'])
                    @if ( !empty($enabledTaxonomyFilters->category))
                        @foreach ($enabledTaxonomyFilters->category as $taxKey => $taxonomy)

                            @if(count( $taxonomy->values ) > 1)
                    
                                <?php
                                
                                $dropdown = \Modularity\Module\Posts\PostsFilters::getMultiTaxDropdown($taxonomy,
                                        0, 'list-hierarchical');
                                ?>
                                @select($dropdown)
                                @endselect
                                
                                <div class="pos-relative grid-auto">
                                    <button type="button" class="btn "
                                            data-dropdown=".dropdown-{{ $taxKey }}"><?php printf(__('Select') . ' %s…',
                                            $taxonomy->label); ?></button>
                                    <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-arrow-right dropdown-{{ $taxKey }}">

                                        
                                        <?php var_dump($dropdown); ?>
                                            
                                            {!! $dropdown !!}
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endif
                @endif
            
                @if($queryString)
                    <div class="hidden-sm hidden-xs grid-md-fit-content u-pt-1" data-tooltip="<?php _e('Clear filters', 'municipio'); ?>" data-tooltip-top>
                        <a  class="pricon-lg pricon pricon-close pricon-space-right"
                           href="/{{ $pageUrl }}"></a>
                    </div>
                @endif
                <div class="grid-sm-12 grid-md-fit-content u-pt-1@sm u-pt-1@xs">
                    <input type="submit" value="{{$frontEndFilters['front_end_button_text']}}"
                           class="btn btn-primary btn-block">
                </div>
            </div>
        </form>
    </section>
@endif
