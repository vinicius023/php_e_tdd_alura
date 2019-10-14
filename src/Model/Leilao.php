<?php

namespace Alura\Leilao\Model;

class Leilao
{
    /** @var Lance[] */
    private $lances;
    /** @var string */
    private $descricao;

    public function __construct(string $descricao)
    {
        $this->descricao = $descricao;
        $this->lances = [];
    }

    public function recebeLance(Lance $lance)
    {
        if (!empty($this->lances) && $this->ehDoUltimoUsuario($lance)) {
            return;
        }

        $usuario = $lance->getUsuario();
        $totalLancesUsuario = array_reduce($this->lances, function (int $totalAcumulado, Lance $lanceAtual) use ($usuario) {
            if ($lanceAtual->getUsuario == $usuario) {
                return $totalAcumulado + 1;
            }
            return $totalAcumulado;
        }, 0);
        if ($totalLancesUsuario >= 5) {
            return;
        }
        $this->lances[] = $lance;
    }

    public function ehDoUltimoUsuario(Lance $lance)
    {
        $ultimoLance = $this->lances[array_key_last($this->lances)];
        return $lance->getUsuario() == $ultimoLance->getUsuario();
    }

    /**
     * @return Lance[]
     */
    public function getLances(): array
    {
        return $this->lances;
    }
}
