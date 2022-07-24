<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class StripeExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_pk', [$this, 'getStripePublicKey']),
        ];
    }

    public function getStripePublicKey() :string
    {
        return $_ENV['STRIPE_PUBLISH'];
    }
}
