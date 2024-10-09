@button([
    'text' => $lang->email,
    'color' => 'default',
    'style' => 'basic',
    'href' => 'mailto:'.$contact['email'],
    'icon' => 'outgoing_mail',
    'reversePositions' => 'true',
    'attributeList' => [
        'itemprop' => 'email',
        'title' => $contact['email']
    ],
    'classList' => ['c-button--email', 'u-margin--0']
])
@endbutton