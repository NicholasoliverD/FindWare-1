<?php
require_once __DIR__ . '/../Models/Produto.php';

class MapaController extends Controller
{
    public function index(): void
    {
        $produtoModel = new Produto();
        $busca = trim($_GET['busca'] ?? '');
        $produto = null;
        $erro = null;

        if ($busca === '') {
            $erro = 'Volte para a tela de localização e informe um produto ou código.';
        } else {
            $produto = $produtoModel->buscarPorTermoOuCodigo($busca);

            if (!$produto) {
                $erro = 'Nenhum produto foi encontrado com a busca informada.';
            }
        }

        $this->view('mapa/index', compact('produto', 'erro', 'busca'));
    }
}
