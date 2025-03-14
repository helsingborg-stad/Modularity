@element([
    'classList' => ['interactive-map'],
    'attributeList' => $attributeList 
])
<div style="position: absolute; top: 0; right: 0; height: 64px; width: 64px; z-index: 9000; background-color: blue;"></div>
    <div class="openstreetmap" style="position: relative; height: 700px; width: 100%;">
        <div 
            style="position: unset; height: 700px; width: 100%; background: #f0f0f0;"
            id="{{$mapID}}">
        </div>

    </div>
    @includeWhen(!empty($selectFilters) || !empty($buttonFilters), 'filters')
@endelement