<?php

require 'vendor/autoload.php';

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;


/**
 * Cliente da API da Academia.
 */
class ClienteAcademia {

    private GuzzleClient $suap;
    private GuzzleClient $academia;



    /**
     * @param string $uri_academia URI da API da academia.
     * @param string $uri_suap URI da API do SUAP.
     * @param string $suap_token Token JWT de acesso ao SUAP.
     */

     public function __construct(
        string $uri_academia,
        string $uri_suap,
        private string $suap_token = ''
    ) {
    
    $this->academia = new GuzzleClient([
        'base_uri' => $uri_academia,
        'http_errors' => false,
    ]);

    $this->suap = new GuzzleClient([
        'base_uri' => $uri_suap,
        'verify' => false,
    ]);
    }



    /**
     * Retorna a lista de treinos.
     */
    public function getTreinos(): array {
        $resposta = $this->academia->request("GET", 'treinos');
        $treinos = json_decode($resposta->getBody());
        return $treinos;
    }
    /**
     * Cria uma treino.
     * 
     * @param array p - array contendo os dados da treino.
     */
    public function criarTreino($t) {
        $resposta = $this->academia->post(
            'treino/create',
            [
                'json' => $t,
                'headers' => ['Authorization' => "Bearer $this->suap_token"]
            ]
        );
        return $resposta;
    }
    /**
     * Excui um treino.
     * 
     * @param int id - ID do treino a ser excluída.
     */
    public function excluirTreino($id) {
        $resposta = $this->academia->delete(
            "treino/delete/$id",
            ['headers' => ['Authorization' => "Bearer $this->suap_token"]]
        );
        return $resposta;
    }
    
    /**
     * editar um treino.
     * 
     * @param int id - ID do treino a ser excluída.
     */
    public function editarTreino($id,$treino) {
        $resposta = $this->academia->put(
            "treino/edit/$id",
            [
                'json' => $treino,
                'headers' => ['Authorization' => "Bearer $this->suap_token"]
            ]
        );
        return $resposta;
    }

    /**
     * Envia uma requisição de login ao SUAP para gerar um token de acesso e 
     * retorna os dados do usuário logado.
     * 
     * @param string $matricula A matrícula SUAP do usuário.
     * @param string $senha A senha do SUAP do usuário.
     * 
     * @return array Dados do usuário logado e token de acesso.
     */
    public function login($matricula, $senha): array {
        $this->suap_token = $this->criarTokenSUAP($matricula, $senha);

        $usuario = $this->getDadosUsuarioSUAP();
        $usuario['suap_token'] = $this->suap_token;
        
        return $usuario;
    }
     /**
     * Cria o token de acesso ao SUAP.
     * 
     * @param string $matricula A matrícula SUAP do usuário.
     * @param string $senha A senha SUAP do usuário.
     * 
     * @return string O token de acesso gerado pelo SUAP.
     */
    private function criarTokenSUAP($matricula, $senha): string {
        # Envia matrícula e senha no corpo da requisição
        $params = [
            'form_params' => [
                'username' => $matricula,
                'password' => $senha
            ]
        ];

        # Envia requisição ao SUAP para gerar o token de acesso
        $resp = $this->suap->post(
            '/api/v2/autenticacao/token/',
            $params
        );

        # Decodifica os dados da resposta JSON
        $resp_json = json_decode($resp->getBody());
        # Pega o token de acesso
        $token = $resp_json->access;

        return $token;
    }


    /**
     * Pega os dados do usuário no SUAP.
     * 
     * @return array Os dados do usuário no SUAP.
     */
    private function getDadosUsuarioSUAP(): array {
        $res = json_decode(
            $this->suap->get(
                'minhas-informacoes/meus-dados/',
                ['headers' => ['Authorization' => "Bearer $this->suap_token"]]
            )->getBody()->getContents(),
            associative: true
        );

        $dados = [
            'nome' => $res['nome_usual'],
            'matricula' => $res['matricula']
        ];

        return $dados;
    }
}