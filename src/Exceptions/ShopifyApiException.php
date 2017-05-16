<?php

namespace CultureKings\ShopifyAuth\Exceptions;

use Exception;

class ShopifyAuthApiException extends Exception
{

    /**
     * ShopifyAuthApiException constructor.
     * @param string $message
     * @param int    $code
     */
    public function __construct($message, $code)
    {
        parent::__construct($message, $code);
    }
}