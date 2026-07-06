<?php
require_once __DIR__ . '/../Models/Produto.php';

class ProdutoController extends Controller
{
    public function cadastrarForm(): void
    {
        $this->view('produtos/cadastrar');
    }

    public function salvar(): void
    {
        $produtoModel = new Produto();

        $dados = [
            'nome' => trim($_POST['nome'] ?? ''),
            'codigo' => trim($_POST['codigo'] ?? ''),
            'codigo_barras' => trim($_POST['codigo_barras'] ?? ''),
            'corredor' => strtoupper(trim($_POST['corredor'] ?? '')),
            'prateleira' => trim($_POST['prateleira'] ?? ''),
            'nivel' => trim($_POST['nivel'] ?? ''),
            'estoque' => intval($_POST['estoque'] ?? 0),
        ];

        foreach (['nome', 'codigo', 'codigo_barras', 'corredor', 'prateleira', 'nivel'] as $campo) {
            if ($dados[$campo] === '') {
                $this->view('mensagem', [
                    'status' => 'erro',
                    'titulo' => 'Campos obrigatórios',
                    'mensagem' => 'Preencha todos os campos antes de cadastrar o produto.',
                    'botoes' => [
                        ['rota' => 'produtos/cadastrar', 'texto' => 'Voltar ao Cadastro'],
                        ['rota' => 'menu', 'texto' => 'Voltar ao Menu', 'secundario' => true],
                    ],
                ]);
                return;
            }
        }

        $produtoModel->criar($dados);

        $this->view('mensagem', [
            'status' => 'sucesso',
            'titulo' => 'Produto cadastrado com sucesso!',
            'mensagem' => "O produto {$dados['nome']} foi registrado no estoque do FindWare.",
            'resumo' => [
                'Produto' => $dados['nome'],
                'Código' => $dados['codigo'],
                'Estoque' => $dados['estoque'],
                'Localização' => $dados['corredor'] . '-' . $dados['prateleira'] . '-N' . $dados['nivel'],
            ],
            'botoes' => [
                ['rota' => 'produtos/cadastrar', 'texto' => 'Cadastrar Outro Produto'],
                ['rota' => 'menu', 'texto' => 'Voltar ao Menu', 'secundario' => true],
            ],
        ]);
    }

    public function buscarForm(): void
    {
        $this->view('produtos/buscar');
    }

    public function resultado(): void
    {
        $produtoModel = new Produto();
        $busca = trim($_GET['busca'] ?? '');
        $produtos = $busca !== '' ? $produtoModel->buscarPorTermo($busca) : [];

        $this->view('produtos/resultado', compact('busca', 'produtos'));
    }

    public function localizarForm(): void
    {
        $this->view('produtos/localizar');
    }

    public function perdidoForm(): void
    {
        $produtoModel = new Produto();
        $busca = trim($_GET['busca'] ?? '');
        $produto = $busca !== '' ? $produtoModel->buscarPorTermoOuCodigo($busca) : null;

        $this->view('produtos/perdido', compact('busca', 'produto'));
    }

    public function atualizarLocalizacao(): void
    {
        $produtoModel = new Produto();

        $codigo = trim($_POST['codigo_barras'] ?? '');
        $corredor = strtoupper(trim($_POST['corredor'] ?? ''));
        $prateleira = trim($_POST['prateleira'] ?? '');
        $nivel = trim($_POST['nivel'] ?? '');

        $produtoModel->atualizarLocalizacao($codigo, $corredor, $prateleira, $nivel);

        $this->view('mensagem', [
            'status' => 'sucesso',
            'titulo' => 'Localização atualizada com sucesso!',
            'mensagem' => 'O novo endereço do produto foi salvo no sistema.',
            'resumo' => [
                'Código' => $codigo,
                'Novo corredor' => $corredor,
                'Nova prateleira' => $prateleira,
                'Novo nível' => $nivel,
            ],
            'botoes' => [
                ['rota' => 'mapa?busca=' . urlencode($codigo), 'texto' => 'Ver no Mapa'],
                ['rota' => 'menu', 'texto' => 'Voltar ao Menu', 'secundario' => true],
            ],
        ]);
    }

    public function excluir(): void
    {
        $produtoModel = new Produto();
        $id = intval($_GET['id'] ?? 0);

        if ($id > 0) {
            $produtoModel->excluir($id);
        }

        $this->view('mensagem', [
            'status' => 'sucesso',
            'titulo' => 'Produto excluído com sucesso!',
            'mensagem' => 'O produto foi removido do cadastro do FindWare.',
            'botoes' => [
                ['rota' => 'dashboard', 'texto' => 'Voltar ao Dashboard'],
                ['rota' => 'menu', 'texto' => 'Voltar ao Menu', 'secundario' => true],
            ],
        ]);
    }

    public function buscarPorCodigoJson(): void
    {
        $produtoModel = new Produto();
        $codigo = $_GET['codigo'] ?? '';
        $produto = $codigo !== '' ? $produtoModel->buscarPorCodigo($codigo) : null;

        if ($produto) {
            $this->json(['encontrado' => true] + $produto);
        } else {
            $this->json(['encontrado' => false]);
        }
    }
}
