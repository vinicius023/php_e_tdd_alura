<?php

namespace Alura\Leilao\Tests\Model;

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use PHPUnit\Framework\TestCase;

class LeilaoTest extends TestCase
{
    public function geraLances()
    {
        $joao = new Usuario('João');
        $maria = new Usuario('Maria ');

        $leilaoComUmLance = new Leilao('Fusca 1972 0KM');
        $leilaoComDoisLances = new Leilao('Fiat 147 0KM');

        $leilaoComUmLance->recebeLance(new Lance($joao, 5000));
        $leilaoComDoisLances->recebeLance(new Lance($joao, 1000));
        $leilaoComDoisLances->recebeLance(new Lance($maria, 2000));

        return [
            'Leilao com 1 lance' => [1, $leilaoComUmLance, [5000]],
            'Leilao com 2 lances' => [2, $leilaoComDoisLances, [1000, 2000]]
        ];
    }

    /**
     * @dataProvider geraLances
     */
    public function testLeilaoDeveReceberLances(int $qtdLances, Leilao $leilao, array $valores)
    {
        static::assertCount($qtdLances, $leilao->getLances());
        foreach ($valores as $index => $valor) {
            static::assertEquals($valor, $leilao->getLances()[$index]->getValor());
        }
    }

    public function testLeilaoNaoDeveReceberLancesRepetidos()
    {
        $leilao = new Leilao('Variante 1994 99999KM');
        $ana = new Usuario('Ana');

        $leilao->recebeLance(new Lance($ana, 1000));
        $leilao->recebeLance(new Lance($ana, 1500));

        static::assertCount(1, $leilao->getLances());
        static::assertEquals(1000, $leilao->getLances()[0]->getValor());
    }
}