@element([
    'classList' => ['interactive-map'],
    'attributeList' => $attributeList 
])
    <div class="openstreetmap" style="position: relative; height: 700px; width: 100%;">
        <div 
            style="position: unset; height: 700px; width: 100%; background: #f0f0f0;"
            id="{{$mapID}}">
        </div>

    </div>
    @includeWhen(!empty($selectFilters) || !empty($buttonFilters), 'filters')
@endelement