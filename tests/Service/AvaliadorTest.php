<?php

namespace Alura\Leilao\Tests\Service;

use PHPUnit\Framework\TestCase;

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use Alura\Leilao\Service\Avaliador;

class AvaliadorTest extends TestCase
{
    /** @var Avaliador */
    private $leiloeiro;
    /**
     * ARRANGE - GIVEN
     * Criando cenario 1 para teste
     */
    public function montarCenarioLancesOrdemCrescente()
    {
        $leilao = new Leilao('Fiat 147 0KM');

        $maria = new Usuario('Maria ');
        $joao = new Usuario('João');
        $ana = new Usuario('Ana');
        $jorge = new Usuario('Jorge');

        $leilao->recebeLance(new Lance($ana, 1500));
        $leilao->recebeLance(new Lance($jorge, 1700));
        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($maria, 2500));

        /**
         * Retorna um array de array pois cada parametro do 
         * metodo em teste utiliza uma posicação do array passado 
         * pelo dataProvider em cada caso de teste
         */
        return [
            'ordem-crescente' => [$leilao]
        ];
    }

    /**
     * ARRANGE - GIVEN
     * Criando cenario 2 para teste
     */
    public function montarCenarioLancesOrdemDecrescente()
    {
        $leilao = new Leilao('Fiat 147 0KM');

        $maria = new Usuario('Maria ');
        $joao = new Usuario('João');
        $ana = new Usuario('Ana');
        $jorge = new Usuario('Jorge');

        
        $leilao->recebeLance(new Lance($maria, 2500));
        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($jorge, 1700));
        $leilao->recebeLance(new Lance($ana, 1500));

        /**
         * Retorna um array de array pois cada parametro do 
         * metodo em teste utiliza uma posicação do array passado 
         * pelo dataProvider em cada caso de teste
         */
        return [
            'ordem-decrescente' => [$leilao]
        ];
    }

    /**
     * ARRANGE - GIVEN
     * Criando cenario 3 para teste
     */
    public function montarCenarioLancesOrdemAleatoria()
    {
        $leilao = new Leilao('Fiat 147 0KM');

        $maria = new Usuario('Maria ');
        $joao = new Usuario('João');
        $ana = new Usuario('Ana');
        $jorge = new Usuario('Jorge');

        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($maria, 2500));
        $leilao->recebeLance(new Lance($jorge, 1700));
        $leilao->recebeLance(new Lance($ana, 1500));

        /**
         * Retorna um array de array pois cada parametro do 
         * metodo em teste utiliza uma posicação do array passado 
         * pelo dataProvider em cada caso de teste
         */
        return [
            'ordem-aleatoria' => [$leilao]
        ];
    }

    public function criaAvaliador()
    {
        $this->leiloeiro = new Avaliador();
    }

    protected function setUp() : void
    {
        // echo 'Executando setUp!'.PHP_EOL;
        $this->criaAvaliador();
    }

    /**
     * @dataProvider montarCenarioLancesOrdemCrescente
     * @dataProvider montarCenarioLancesOrdemDecrescente
     * @dataProvider montarCenarioLancesOrdemAleatoria
     */
    public function testAvaliadorDeveEncontrarOMaiorValorDeLance(Leilao $leilao)
    {
        /**
         * ACT - WHEN
         * Executando chamada das funcionalidades desenvolvidas (execução da regra de negócio)
         */
        $this->leiloeiro->avalia($leilao);

        $maiorValor = $this->leiloeiro->getMaiorValor();

        /**
         * ASSERT - THEN
         * Validando retorno dos metodos (verificação do resultado)
         */
        self::assertEquals(2500, $maiorValor);
    }

    /**
     * @dataProvider montarCenarioLancesOrdemCrescente
     * @dataProvider montarCenarioLancesOrdemDecrescente
     * @dataProvider montarCenarioLancesOrdemAleatoria
     */
    public function testAvaliadorDeveEncontrarOMenorValorDeLance(Leilao $leilao)
    {
        /**
         * ACT - WHEN
         * Executando chamada das funcionalidades desenvolvidas (execução da regra de negócio)
         */
        $this->leiloeiro->avalia($leilao);

        $menorValor = $this->leiloeiro->getMenorValor();

        /**
         * ASSERT - THEN
         * Validando retorno dos metodos (verificação do resultado)
         */
        self::assertEquals(1500, $menorValor);
    }

    /**
     * @dataProvider montarCenarioLancesOrdemCrescente
     * @dataProvider montarCenarioLancesOrdemDecrescente
     * @dataProvider montarCenarioLancesOrdemAleatoria
     */
    public function testAvaliadorDeveBuscar3MaioresValores(Leilao $leilao)
    {
        /**
         * ACT - WHEN
         * Executando chamada das funcionalidades desenvolvidas (execução da regra de negócio)
         */
        $this->leiloeiro->avalia($leilao);

        $maioresLances = $this->leiloeiro->getMaioresLances();

        self::assertCount(3, $maioresLances);
        self::assertEquals(2500, $maioresLances[0]->getValor());
        self::assertEquals(2000, $maioresLances[1]->getValor());
        self::assertEquals(1700, $maioresLances[2]->getValor());
    }
    
    public function testLeilaoVazioNaoPodeSerAvaliado()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Não é possível avaliar leilão vazio');
        $leilao = new Leilao('Fusca Azul');
        $this->leiloeiro->avalia($leilao);
    }

    public function testLeilaoFinalizadoNaoPodeSerAvaliado()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Leilão já finalizado');

        $leilao = new Leilao('Fiat Toro 147 0KM');
        $leilao->recebeLance(new Lance(new Usuario('Sabino Testa'), 2000));
        $leilao->finaliza();

        $this->leiloeiro->avalia($leilao);
    }
}
