@button([
    'text' => $lang->call,
    'color' => 'default',
    'style' => 'basic',
    'href' => 'tel:' . $number,
    'icon' => $type == 'smartphone' ? 'smartphone' : 'call',
    'reversePositions' => 'true',
    'attributeList' => [
        'itemprop' => 'telephone',
        'data-tooltip' => $number
    ],
    'classList' => ['c-button--phone', 'c-button--' . $type, 'u-margin--0']
])
@endbutton