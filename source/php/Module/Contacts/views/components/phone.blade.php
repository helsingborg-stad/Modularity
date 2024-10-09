@button([
    'text' => $lang->call,
    'color' => 'default',
    'style' => 'basic',
    'href' => 'tel:'.$phone['number'],
    'icon' => $phone['type'] == 'smartphone' ? 'smartphone' : 'call',
    'reversePositions' => 'true',
    'attributeList' => [
        'itemprop' => 'telephone',
        'title' => $phone['number']
    ],
    'classList' => ['c-button--phone', 'c-button--' . $phone['type'], 'u-margin--0']
])
@endbutton