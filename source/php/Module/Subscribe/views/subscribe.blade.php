@if (!$hideTitle && !empty($postTitle))
  @typography([
    'element' => 'h2', 
    'variant' => 'h2', 
    'classList' => ['module-title']
  ])
    {!! $postTitle !!}
  @endtypography
@endif

@if($type) 
  @paper(['padding' => '3'])

    @if($content)
      @typography([
        "variant" => "p",
        "element" => "p",
        "classList" => ["u-margin__top--0", "u-margin__bottom--4"]
      ])
        {{ $content }}
      @endtypography
    @endif

    @notice([
      'type' => 'danger',
      'message' => [
        'title' => $lang->error->title,
        'text' => $lang->error->text,
      ],
      'icon' => [
          'name' => 'sentiment_neutral'
      ],
      'classList' => [
        'u-margin__bottom--2',
        'u-display--none'
      ],
      'attributeList' => [
        'aria-hidden' => 'true',
      ]
    ])
    @endnotice
      
    @include('service.' . $type)

  @endpaper
@else
  @notice([
    'type' => 'info',
    'message' => [
      'title' => $lang->incomplete->title,
      'text' => $lang->incomplete->text,
    ],
    'icon' => [
        'name' => 'electrical_services'
    ]
  ])
  @endnotice
@endif