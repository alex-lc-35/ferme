<?php

namespace App\Utils\Translator;

use App\Enum\ProductUnit;

class UnitTranslator
{
    public function translate(ProductUnit $unit): string
    {
        return match ($unit) {
            ProductUnit::PIECE  => 'Pièce',
            ProductUnit::BUNDLE => 'Botte',
            ProductUnit::BUNCH  => 'Bouquet',
            ProductUnit::LITER  => 'Litre',
            ProductUnit::KG     => 'Kilo',
            default             => $unit->value,
        };
    }
}
