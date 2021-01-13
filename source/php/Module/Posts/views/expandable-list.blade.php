@include('partials.post-filters')

@card([
    'heading' => apply_filters('the_title', $post_title),
    'classList' => [$classes],
    'attributeList' => [
        'js-filter-container' => $ID
    ]
])
    @if (!$hideTitle && !empty($post_title))
        <div class="c-card__header">
            @typography([
                'element' => "h4"
            ])
                {!! apply_filters('the_title', $post_title) !!}
            @endtypography
        </div>
    @endif

    @if (isset($posts_list_column_titles) && $posts_list_column_titles)
        <header class="accordion-table accordion-table-head">
            @if ($posts_hide_title_column)

                @typography([
                    'element' => "span",
                    'classList' => ['column-header']
                ])
                    {{ isset($title_column_label) && !empty($title_column_label) ? $title_column_label : __('Title', 'modularity') }}
                @endtypography

            @endif

            @foreach ($posts_list_column_titles as $column)

                    @typography([
                        'element' => "span",
                        'classList' => ['column-header']
                    ])
                        {{ $column->column_header }}
                    @endtypography

            @endforeach
        </header>
    @endif

    <div>
        @if (!isset($allow_freetext_filtering) || $allow_freetext_filtering)

            <div class="c-card__body">
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

        @if(count($prepareAccordion) > 0)

            @accordion([
            ])
                @foreach ($prepareAccordion as $accordionItem)
                    @accordion__item([
                        'heading' => $accordionItem['heading'],
                        'attributeList' => [
                            'js-filter-item' => '',
                            'js-filter-data' => ''
                            ]
                        ])
                        <p>{!! $accordionItem['content'] !!}</p>
                    @endaccordion__item
                @endforeach
            @endaccordion

        @else

            <section class="accordion-section">
                @typography([
                    'element' => "p"
                ])
                    _e('Nothing to display…', 'modularity');
                @endtypography
            </section>

        @endif

    </div>

@endcard

{{-- <div class="{{ $classes }}">


</div> --}}
