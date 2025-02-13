<?php

namespace Modularity\Module\InteractiveMap\Config;

/**
 * Class GoogleMapsLocation
 */
class GoogleMapsAcfLocation implements LocationInterface
{
    private float $lat;
    private float $lng;
    private int $zoom;
    private ?string $address;
    private ?int $streetNumber;
    private ?string $streetName;
    private ?string $city;
    private ?string $state;
    private ?string $postCode;
    private ?string $country;

    /**
     * GoogleMapsLocation constructor.
     */
    public function __construct(array $googleMapsAcfLocation)
    {
        $this->lat          = $googleMapsAcfLocation['lat'] ?? 59.32932;
        $this->lng          = $googleMapsAcfLocation['lng'] ?? 18.06858;
        $this->zoom         = $googleMapsAcfLocation['zoom'] ?? 14;
        $this->address      = $googleMapsAcfLocation['address'] ?? null;
        $this->streetNumber = $googleMapsAcfLocation['street_number'] ?? null;
        $this->streetName   = $googleMapsAcfLocation['street_name'] ?? null;
        $this->city         = $googleMapsAcfLocation['city'] ?? null;
        $this->state        = $googleMapsAcfLocation['state'] ?? null;
        $this->postCode     = $googleMapsAcfLocation['post_code'] ?? null;
        $this->country      = $googleMapsAcfLocation['country'] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getLat(): float
    {
        return $this->lat;
    }

    /**
     * @inheritDoc
     */
    public function getLng(): float
    {
        return $this->lng;
    }

    /**
     * @inheritDoc
     */
    public function getZoom(): int
    {
        return $this->zoom;
    }

    /**
     * @inheritDoc
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @inheritDoc
     */
    public function streetNumber(): ?int
    {
        return $this->streetNumber;
    }

    /**
     * @inheritDoc
     */
    public function streetName(): ?string
    {
        return $this->streetName;
    }

    /**
     * @inheritDoc
     */
    public function city(): ?string
    {
        return $this->city;
    }

    /**
     * @inheritDoc
     */
    public function state(): ?string
    {
        return $this->state;
    }

    /**
     * @inheritDoc
     */
    public function postCode(): ?string
    {
        return $this->postCode;
    }

    /**
     * @inheritDoc
     */
    public function country(): ?string
    {
        return $this->country;
    }
}