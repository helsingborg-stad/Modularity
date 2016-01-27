<?php

namespace Modularity\Module\Booking;

class Booking extends \Modularity\Module
{
    public function __construct()
    {
        $this->register(
            'booking',
            'Booking',
            'Bookings',
            'Outputs one widget for booking purpose',
            array('editor')
        );
    }
}
