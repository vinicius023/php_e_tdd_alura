<?php

namespace Alura\Leilao\Service;

use Alura\Leilao\Model\Leilao;

class Avaliador
{
    private $maiorValor = -INF;

    public function avalia(Leilao $leilao) : void
    {
        $lances = $leilao->getLances();
        foreach ($lances as $lance) {
            if ($lance > $this->$maiorValor) {
                $this->$maiorValor = $lance;
            }
        }
    }

    public function getMaiorValor() : float
    {
        return $this->maiorValor;
    }
}
