<?php

require 'vendor/autoload.php';

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;


/**
 * Cliente da API da Academia.
 */
class ClienteAcademia {
    private GuzzleClient $guzzle;


    /**
     * @param string url_servico - URL da API.
     */
    public function __construct(private string $url_servico) {
        $this->guzzle = new GuzzleClient([
            'base_uri' => $this->url_servico,
            'http_errors' => false
        ]);
    }


    /**
     * Retorna a lista de treinos.
     */
    public function getTreinos(): array {
        $resposta = $this->guzzle->request("GET", 'treinos');
        $treinos = json_decode($resposta->getBody());
        return $treinos;
    }
    /**
     * Cria uma treino.
     * 
     * @param array p - array contendo os dados da treino.
     */
    public function criarTreino($t) {
        $resposta = $this->guzzle->post(
            'treino/create',
            ['json' => $t]
        );
        return $resposta;
    }
    /**
     * Excui um treino.
     * 
     * @param int id - ID do treino a ser excluída.
     */
    public function excluirTreino($id) {
        $resposta = $this->guzzle->delete("treino/delete/$id");
        return $resposta;
    }
    /**
     * Excui um treino.
     * 
     * @param int id - ID do treino a ser excluída.
     */
    public function editarTreino($id,$treino) {
        $resposta = $this->guzzle->put(
            "treino/edit/$id",
            ['json' => $treino]
        );
        return $resposta;
    }
}