<div class="o-grid">
    <div class="o-grid-12">

        @card([
            'context' => $context
        ])
            @if (empty($hideTitle) && !empty($post_title))
                <div class="c-card__header">
                    @include('partials.post-title', ['variant' => 'h4', 'classList' => []])
                </div>
            @endif
            <div>
                @if (!empty($accordionColumnTitles))
                    <header class="accordion-table__head">
                        @foreach ($accordionColumnTitles as $title)
                            @typography([
                                'element' => 'span',
                                'classList' => ['accordion-table__head-column']
                            ])
                                {{ $title }}
                            @endtypography
                        @endforeach
                    </header>
                @endif

                @accordion([])
                    @foreach ($manualInputs as $input)
                            @accordion__item([
                                'heading' => $input['title'],
                            ])
                                {!! $input['content'] !!}
                            @endaccordion__item
                    @endforeach
                @endaccordion
            </div>
        @endcard
    </div>
</div>
