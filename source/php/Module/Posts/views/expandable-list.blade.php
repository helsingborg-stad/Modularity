@include('partials.post-filters')

@card([
    'heading' => apply_filters('the_title', $post_title),
    'classList' => [$classes],
    'attributeList' => [
        'js-filter-container'   => $ID,
        'aria-labelledby'       => 'mod-posts-' . $ID . '-label'
    ]
])
    @if (!$hideTitle && !empty($post_title))
        <div class="c-card__header">
            @typography([
                'id'        => 'mod-posts-' . $ID . '-label',
                'element'   => "h4"
            ])
                {!! apply_filters('the_title', $post_title) !!}
            @endtypography
        </div>
    @endif

    <div>
        @if (!isset($allow_freetext_filtering) || $allow_freetext_filtering)

            <div class="c-card__body" aria-label="{{ __('Search', 'municipio') }}">
                @field([
                    'type' => 'text',
                    'attributeList' => [
                        'type' => 'search',
                        'name' => 'search',
                        'js-filter-input' => $ID
                    ],
                    'label' => __('Search', 'municipio')
                ])
                @endfield
            </div>

        @endif
    
            @if (isset($posts_list_column_titles) && $posts_list_column_titles)
                
                <header class="accordion-table__head">
       
                    @if (!$posts_hide_title_column)
                        @typography([
                            'element' => "span",
                            'classList' => ['accordion-table__head-column']
                        ])
                            {{ isset($title_column_label) && !empty($title_column_label) ? $title_column_label : __('Title', 'modularity') }}
                        @endtypography
                    @endif
              
                    @if ( is_array($posts_list_column_titles)  && !empty($posts_list_column_titles))
                        @foreach ($posts_list_column_titles as $column)
                            @typography([
                                'element' => "span",
                                'classList' => ['accordion-table__head-column']
                            ])
                                {{ $column->column_header }}
                            @endtypography
                        @endforeach
                        <span class="accordion-table__head-column-icon"></span>
                   @endif
                </header>
            @endif
        
        
        @if(count($prepareAccordion) > 0)

            @accordion([])
                @foreach ($prepareAccordion as $accordionItem)

                    @if( is_array($accordionItem['column_values'])  && !empty($accordionItem['column_values']))
               
                        @accordion__item([
                            'heading' => ($accordionItem['heading']) ?
                                array_merge( (array) $accordionItem['heading'],
                                (array) $accordionItem['column_values'] ) : $accordionItem['heading'],
                            'attributeList' => [
                                'js-filter-item' => '',
                                'js-filter-data' => ''
                            ],
                            'classList' => ['c-accordion-table']
                       ])
                            {!! $accordionItem['content'] !!}
                       @endaccordion__item
                        
                    @else
                 
                        @accordion__item([
                            'heading' => $accordionItem['heading'],
                            'attributeList' => [
                                'js-filter-item' => '',
                                'js-filter-data' => ''
                            ]
                        ])
                        {!! $accordionItem['content'] !!}
                        @endaccordion__item
                        
                   @endif
                @endforeach
            @endaccordion

        @else

            <section class="accordion-section">
                @typography([
                    'element' => "p"
                ])
                    {{ __('Nothing to display…', 'modularity') }}
                @endtypography
            </section>

        @endif

    </div>

@endcard