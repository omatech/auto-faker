<?php
namespace Omatech\AutoFaker;

class AutoFakerFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'autofake'; }
}