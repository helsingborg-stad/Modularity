@if (!$hideTitle && !empty($postTitle))
    @typography([
        'id'        => 'mod-modal-' . $ID . '-label',
        'element'   => 'h2', 
        'variant'   => 'h2', 
        'classList' => ['module-title']
    ])
        {!! $postTitle !!}
    @endtypography
@endif
@if($label && $modalContent)
    @button([
        'href'      => '',
        'text'      => $label,
        'icon'      => $icon,
        'size'      => 'md', // TODO - make this a module setting
        'color'     => 'secondary', // TODO - make this a module setting
        'classList' => ['open-modal'],
        'attributeList' => ['data-open' => 'modal-' . $modalId],
    ])
    @endbutton
    @modal([
        'panel'   => false, // TODO - make this a module setting
        'size'    => 'lg', // TODO - make this a module setting
        'heading' => $modalContentTitle,
        'id'      => 'modal-' . $modalId
    ])
        {!! $modalContent !!}
    @endmodal
@endif
