@element([
    'classList' => ['interactive-map'],
    'attributeList' => $attributeList 
])
    @includeWhen((!empty($selectFilters) || !empty($buttonFilters)) && $allowFiltering, 'filterIcon')
    <div class="openstreetmap" style="position: relative; height: {{$mapSize}}; width: 100%;">
        <div 
            style="position: unset; height: {{$mapSize}}; width: 100%; background: #f0f0f0;"
            id="{{$mapID}}">
        </div>

    </div>
    
    @includeWhen((!empty($selectFilters) || !empty($buttonFilters)) && $allowFiltering, 'filters')
    @include('markerInfo')
@endelement