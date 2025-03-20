import { SavedMarker } from "../../mapData";

interface MarkerClickInterface {
    click(markerData: SavedMarker): void;
}