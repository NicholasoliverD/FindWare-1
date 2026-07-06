<?php
require_once __DIR__ . '/../Models/Produto.php';
require_once __DIR__ . '/../Models/Movimentacao.php';

class MovimentacaoController extends Controller
{
    public function recebimentoForm(): void
    {
        $codigo = $_GET['codigo'] ?? '';
        $this->view('movimentacoes/recebimento', compact('codigo'));
    }

    public function salvarRecebimento(): void
    {
        $produtoModel = new Produto();
        $movimentacaoModel = new Movimentacao();

        $codigo = trim($_POST['codigo_barras'] ?? '');
        $produto = trim($_POST['produto'] ?? '');
        $quantidade = intval($_POST['quantidade'] ?? 0);
        $corredor = strtoupper(trim($_POST['corredor'] ?? ''));
        $prateleira = trim($_POST['prateleira'] ?? '');
        $nivel = trim($_POST['nivel'] ?? '');
        $localizacao = "{$corredor}-{$prateleira}-N{$nivel}";

        $produtoModel->entradaOuCriar($codigo, $produto, $quantidade, $corredor, $prateleira, $nivel);
        $movimentacaoModel->registrar($codigo, $produto, $quantidade, $localizacao, 'ENTRADA');

        $this->view('mensagem', [
            'status' => 'sucesso',
            'titulo' => 'Recebimento salvo com sucesso!',
            'mensagem' => 'A entrada do produto foi registrada no estoque do FindWare.',
            'resumo' => [
                'Produto' => $produto,
                'Código' => $codigo,
                'Quantidade' => $quantidade,
                'Localização' => $localizacao,
            ],
            'botoes' => [
                ['rota' => 'movimentacoes/recebimento', 'texto' => 'Novo Recebimento'],
                ['rota' => 'movimentacoes/historico', 'texto' => 'Ver Movimentações', 'secundario' => true],
                ['rota' => 'menu', 'texto' => 'Voltar ao Menu', 'secundario' => true],
            ],
        ]);
    }

    public function saidaForm(): void
    {
        $produtoModel = new Produto();
        $codigo = $_GET['codigo'] ?? '';
        $produto = $codigo !== '' ? $produtoModel->buscarPorCodigo($codigo) : null;

        $this->view('movimentacoes/saida', compact('codigo', 'produto'));
    }

    public function salvarSaida(): void
    {
        $produtoModel = new Produto();
        $movimentacaoModel = new Movimentacao();

        $codigo = trim($_POST['codigo_barras'] ?? '');
        $produtoNome = trim($_POST['produto'] ?? '');
        $quantidade = intval($_POST['quantidade'] ?? 0);
        $motivo = trim($_POST['motivo'] ?? '');

        $resultado = $produtoModel->darSaida($codigo, $quantidade);

        if (!$resultado['ok']) {
            $this->view('mensagem', [
                'status' => 'erro',
                'titulo' => $resultado['motivo'] === 'estoque_insuficiente' ? 'Estoque insuficiente' : 'Produto não encontrado',
                'mensagem' => $resultado['erro'],
                'botoes' => [
                    ['rota' => 'movimentacoes/saida', 'texto' => 'Tentar Novamente'],
                    ['rota' => 'menu', 'texto' => 'Voltar ao Menu', 'secundario' => true],
                ],
            ]);
            return;
        }

        $estoqueAnterior = (int) $resultado['produto']['estoque'];
        $movimentacaoModel->registrar($codigo, $produtoNome, $quantidade, $motivo, 'SAIDA');

        $this->view('mensagem', [
            'status' => 'sucesso',
            'titulo' => 'Saída registrada com sucesso!',
            'mensagem' => 'A saída do produto foi registrada no sistema.',
            'resumo' => [
                'Produto' => $produtoNome,
                'Código' => $codigo,
                'Quantidade retirada' => $quantidade,
                'Motivo' => $motivo,
                'Estoque anterior' => $estoqueAnterior,
                'Estoque atual' => $estoqueAnterior - $quantidade,
            ],
            'botoes' => [
                ['rota' => 'movimentacoes/saida', 'texto' => 'Nova Saída'],
                ['rota' => 'movimentacoes/historico', 'texto' => 'Ver Movimentações', 'secundario' => true],
                ['rota' => 'menu', 'texto' => 'Voltar ao Menu', 'secundario' => true],
            ],
        ]);
    }

    public function historico(): void
    {
        $movimentacaoModel = new Movimentacao();
        $movimentacoes = $movimentacaoModel->todas();

        $this->view('movimentacoes/historico', compact('movimentacoes'));
    }
}
