<?php

namespace Modularity\Module\InteractiveMap\Config;

interface LocationInterface
{
    /**
     * Get the zoom level.
     */
    public function getZoom(): int;

    /**
     * Get the latitude.
     */
    public function getLat(): float;

    /**
     * Get the longitude.
     */
    public function getLng(): float;

    /**
     * Get the address.
     */
    public function getAddress(): ?string;

    /**
     * Get the street number.
     */
    public function streetNumber(): ?int;

    /**
     * Get the street name.
     */
    public function streetName(): ?string;

    /**
     * Get the city.
     */
    public function city(): ?string;

    /**
     * Get the state.
     */
    public function state(): ?string;

    /**
     * Get the post code.
     */
    public function postCode(): ?string;

    /**
     * Get the country.
     */
    public function country(): ?string;
}