<?php

namespace TarfinLabs\EasyPdf;

use Illuminate\Support\Facades\Facade;

class EasyPdfFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return EasyPdf::class;
    }
}
