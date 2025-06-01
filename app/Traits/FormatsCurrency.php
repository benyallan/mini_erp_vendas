<?php

namespace App\Traits;

trait FormatsCurrency
{
    /**
     * Formata um valor inteiro (em centavos) como reais.
     */
    public function formatCurrency(int $value): string
    {
        return number_format($value / 100, 2, ',', '.');
    }
}
