@if (!$hideTitle && !empty($postTitle))
    <div class="c-card__header">
        @typography([
            'id'        =>  'mod-logogrid' . $id . '-label',
            'element'   => 'h4',
            'classList' => []
        ])
            {!! $postTitle !!}
        @endtypography
    </div>
@endif

<!-- the grid -->
@logotypegrid(['items'=> $list])
@endlogotypegrid
