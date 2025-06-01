<?php

namespace App\Support;

trait FormatsCurrency
{
    /**
     * Formata um valor inteiro (em centavos) como reais.
     *
     * @param int $value
     * @return string
     */
    public function formatCurrency(int $value): string
    {
        return number_format($value / 100, 2, ',', '.');
    }
}
