<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('static', [$this, 'staticCall']),
        ];
    }

    public function staticCall(string $methodReference, ...$args)
    {
        $parts = explode('::', $methodReference);

        if (
            2 !== count($parts) ||
            !class_exists($parts[0]) ||
            !method_exists($parts[0], $parts[1])

        ) {
            throw new \Exception('Class or method not found : '.$methodReference);
        }

        return call_user_func_array($parts, $args);
    }
}
