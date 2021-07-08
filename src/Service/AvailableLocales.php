<?php

namespace App\Service;

use Symfony\Component\Intl\Exception\MissingResourceException;
use Symfony\Component\Intl\Languages;

class AvailableLocales
{

    public function getAll()
    {
        return ['fr', 'en_US', 'en'];
    }

    /**
     * @return array associative array like [ "Français" => "fr" ]
     */
    public function getChoices(): array
    {
        $choices = [];

        foreach($this->getAll() as $locale) {
            try {
                $choices[Languages::getName($locale)] = $locale;
            } catch (MissingResourceException $exception) {
                continue;
            }
        }

        return $choices;
    }

    public function getDefault()
    {
        return 'fr';
    }
}