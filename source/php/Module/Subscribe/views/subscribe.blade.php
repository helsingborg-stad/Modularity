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
    
    @include('service.' . $type)
    
  @endpaper
@else
  @notice([
    'type' => 'info',
    'message' => [
      'title' => 'Select a provider',
      'text' => 'No provider for this form is selected. Please select a provider available form the list.',
    ],
    'icon' => [
        'name' => 'electrical_services'
    ]
  ])
  @endnotice
@endif