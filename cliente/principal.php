<?php

require 'vendor/autoload.php';
require 'cliente_academia.php';
require_once __DIR__ . '/../academia-api/app/Models/Aluno.php';
require_once __DIR__ . '/../academia-api/app/Models/Treino.php';
use App\Models\Treino;
use App\Models\Aluno;

use Illuminate\Database\Capsule\Manager as Capsule;

/* Inicialização do Eloquent para SQLite */
$capsule = new Capsule;
$capsule->addConnection([
    'driver'   => 'sqlite',
    'database' => __DIR__ . '/../academia-api/database/database.sqlite',
    'prefix'   => '',
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();


/* CONSTANTES */

const OP_SAIR = 'Sair';
const OP_CANCELAR = 'Cancelar';
const OP_ESCREVER = 'Cadastrar o Treino';
const OP_EDITAR  = 'Editar Treino';
const OP_EXCLUIR = 'Excluir o Treino';
const OP_INVALIDA = 'Operação inválida';



/* CLASSES */

/**
 * Interface CLI para a Academia.
 */
class InterfaceAcademia {

    /**
     * @param ClienteAcademia cliente_academia - Cliente da API da Academia.
     * @param string temp_msg - Uma mensagem temporária a ser exibida uma vez.
     */
    public function __construct(
        private ClienteAcademia $cliente_academia,
        private string $temp_msg = ''
    ) {}


    /**
     * Exibe o menu principal em loop.
     */
    public function menuPrincipal() {
        do {
            $this->limparTela();

            $this->exibirTitulo();
        
            $treinos = $this->cliente_academia->getTreinos();
            $this->exibirTreinos($treinos);
        
            $this->exibirMensagemTemporaria();
            
            echo "\n";
            $operacao = $this->menuOperacoes();
        
            switch ($operacao) {
                case OP_SAIR:
                    # Não faz nada, apenas sai
                    break;
                case OP_ESCREVER:
                    $t = $this->menuEscreverTreino();
                    $this->cliente_academia->criarTreino($t);
                    break;
                case OP_EDITAR:
                    $dadosEdicao = $this->menuEditarTreino();
                    $id = $dadosEdicao['id'];
                    $resposta = $this->cliente_academia->editarTreino($id, $dadosEdicao['treino']);
                    $this->exibirErroNaResposta($resposta);
                    break;
                case OP_EXCLUIR:
                    $id = $this->menuExcluirTreino();
                    $resposta = $this->cliente_academia->excluirTreino($id);
                    $this->exibirErroNaResposta($resposta);
                    break;
            }
        } while ($operacao != OP_SAIR);
        
        $this->tchau();
    }


    /**
     * Limpa a tela do terminal.
     */
    private function limparTela() {
        echo "\0343c";
    }

    
    /**
     * Exibe o título da aplicação.
     */
    public function exibirTitulo() {
        echo
        "\r---------------------------------------------------------------------
        \r                            Academia
        \r---------------------------------------------------------------------
        ";
    }


    /**
     * Exibe a lista de treinos.
     * 
     * @param array treinos - lista de treinos.
     */
    public function exibirTreinos($treinos) {
        foreach ($treinos as $t) {
            echo "
            \r#$t->id treino criado em $t->created_at
            \r";
        }
    }
    
    
    /**
     * Exibe a lista de operações disponíveis e retorna a que o usuário
     * escolher.
     */
    public function menuOperacoes(): string {
        echo "Operações:\n";
        $operacoes = [
            1 => OP_ESCREVER,
            2 => OP_EDITAR,
            3 => OP_EXCLUIR,
            0 => OP_SAIR
        ];
        foreach ($operacoes as $i => $op) {
            echo "[$i] $op\n";
        }

        $escolhida = (int) readline('O que você deseja fazer? ');

        if ($escolhida >= count($operacoes) || $escolhida < 0) {
            $this->temp_msg = 'Operação inválida';
            return OP_INVALIDA;
        }
        
        return $operacoes[$escolhida];
    }

    public function listarAlunos()
    {
        $alunos = Aluno::all();

        foreach ($alunos as $aluno) {
            echo "ID: {$aluno->id} Nome: {$aluno->nome}\n";
        }
    }
    public function listarTreinos()
    {
        $treinos = Treino::all();

        foreach ($treinos as $t) {
            echo "ID: {$t->id} Peito: {$t->chest_day} Perna: {$t->leg_day} Costa: {$t->back_day}\n";
        }
    }

   
  
    public function menuEscreverTreino() {
        $t = [];
        $t['chest_day'] = readline('Escreva o treino de peito: ');
        $t['leg_day'] = readline('Escreva o treino de perna: ');
        $t['back_day'] = readline('Escreva o treino de costa: ');
        $this->listarAlunos();
        $t['aluno_id'] = readline('digite o id do aluno que voçe deseja atribuir o treino: ');

      
        return $t;
    }


    /**
     * Exibe menu para ler o id de uma treino a ser excluída.
     */
    public function menuExcluirTreino() {
        $id = readline('Digite o #id do treino que você deseja excluir: ');
        return $id;
    }

    
    /**
     * Exibe menu para ler o id de uma treino a ser editado.
     */
    public function menuEditarTreino() {
        echo "\r---------------------------------------------------------------------
        \r                            Treinos Cadastrados

        ";

        $this->listarTreinos();

        echo "\r---------------------------------------------------------------------
        
        \r
        ";
        $id = readline('Digite o #id do treino que você deseja editar: ');

        // Recupera os dados atuais do treino
        $treinoAtual = Treino::find($id);

        // Verifica se o treino foi encontrado
        if (!$treinoAtual) {
            echo "Treino $id não encontrado.\n";

            return call_user_func([$this, 'menuEditarTreino']);
        }

        // Solicita as alterações ao usuário
        $novosDados = [];

        // Se o usuário deixar em branco, atribui o valor atual do treino
        $novosDados['chest_day'] = readline('Escreva o novo treino de peito (deixe em branco para manter o atual): ') ?: $treinoAtual->chest_day;
        $novosDados['leg_day'] = readline('Escreva o novo treino de perna (deixe em branco para manter o atual): ') ?: $treinoAtual->leg_day;
        $novosDados['back_day'] = readline('Escreva o novo treino de costa (deixe em branco para manter o atual): ') ?: $treinoAtual->back_day;

        // Lista os alunos disponíveis
        $this->listarAlunos();
        $novosDados['aluno_id'] = readline('Digite o id do aluno que deseja atribuir o treino (deixe em branco para manter o atual): ') ?: $treinoAtual->aluno_id;

        return [
            'id' => $id,
            'treino' => $novosDados,
        ];
    }


    /**
     * Exibe uma mensagem temporária, que é apagada em seguida.
     */
    public function exibirMensagemTemporaria() {
        if ($this->temp_msg != '') {
            echo "\n$this->temp_msg\n";
            $this->temp_msg = '';
        }
    }


    /**
     * Exibe uma possível a mensagem de reposta de erro.
     * Se não houver erro, nada é exibido.
     */
    public function exibirErroNaResposta($resposta) {
        if ($resposta->getStatusCode() != 200) {
            
            $msg = json_decode($resposta->getBody());
            $this->temp_msg = "[$msg->tipo] $msg->conteudo";
        }
    }


    /**
     * Exibe uma mensagem de despedida.
     */
    public function tchau() {
        echo "\nObrigado por usar a Academia \n\n";
    }
}



/* PROGRAMA PRINCIPAL */


$cliente_academia = new ClienteAcademia('http://localhost:8000/api/');
$interface = new InterfaceAcademia($cliente_academia);
$interface->menuPrincipal();
