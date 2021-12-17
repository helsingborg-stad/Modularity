<div class="c-logotypegrid">
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
  @if (!empty($list))
    <div class="o-grid">
      @foreach($list as $item)
        <div class="{{$gridClass}} u-margin--auto">
          @link(['href' => $item->url])
            @logotype([
              'src'=> $item->logo,
              'alt' => $item->alt,
              'attributeList' => ['style' => 'height: 112px;']
            ])
            @endlogotype
          @endlink
        </div>
      @endforeach
    </div>
  @endif
</div>