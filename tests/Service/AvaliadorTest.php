<?php

namespace Alura\Leilao\Tests\Service;

use PHPUnit\Framework\TestCase;

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use Alura\Leilao\Service\Avaliador;

class AvaliadorTest extends TestCase
{
    public function testeAvaliadorDeveEncontrarOMaiorValorDeLanceEmOrdemCrescente()
    {

        /**
         * ARRANGE - GIVEN
         * Criando cenario para teste (inicialização do cenário)
         */
        $leilao = new Leilao('Fiat 147 0KM');

        $maria = new Usuario('Maria ');
        $joao = new Usuario('João');

        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($maria, 2500));

        $leiloeiro = new Avaliador();

        /**
         * ACT - WHEN
         * Executando chamada das funcionalidades desenvolvidas (execução da regra de negócio)
         */
        $leiloeiro->avalia($leilao);

        $maiorValor = $leiloeiro->getMaiorValor();

        /**
         * ASSERT - THEN
         * Validando retorno dos metodos (verificação do resultado)
         */
        self::assertEquals(2500, $maiorValor);
    }

    public function testeAvaliadorDeveEncontrarOMaiorValorDeLanceEmOrdemDecrescente()
    {

        /**
         * ARRANGE - GIVEN
         * Criando cenario para teste (inicialização do cenário)
         */
        $leilao = new Leilao('Fiat 147 0KM');

        $maria = new Usuario('Maria ');
        $joao = new Usuario('João');

        $leilao->recebeLance(new Lance($maria, 2500));
        $leilao->recebeLance(new Lance($joao, 2000));

        $leiloeiro = new Avaliador();

        /**
         * ACT - WHEN
         * Executando chamada das funcionalidades desenvolvidas (execução da regra de negócio)
         */
        $leiloeiro->avalia($leilao);

        $maiorValor = $leiloeiro->getMaiorValor();

        /**
         * ASSERT - THEN
         * Validando retorno dos metodos (verificação do resultado)
         */
        self::assertEquals(2500, $maiorValor);
    }
}
