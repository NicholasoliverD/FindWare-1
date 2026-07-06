<div class="page-header">
    <h2>Recebimento de Produtos</h2>
    <p>Registre a entrada de produtos no estoque utilizando código manual, leitor ou QR Code.</p>
</div>

<div class="card">

    <form action="<?= url('movimentacoes/recebimento') ?>" method="POST">

        <div class="aviso aviso-azul">
            <label style="margin-bottom:8px;">Código de barras / QR Code</label>
            <input type="text" id="codigo_barras" name="codigo_barras" placeholder="Bipe ou leia o QR Code" value="<?= e($codigo ?? '') ?>" autofocus required style="margin-bottom:10px;">
            <button type="button" class="btn-secundario" onclick="abrirScanner()">Ler QR Code com a câmera</button>
            <div id="reader" style="max-width:340px; margin-top:14px;"></div>
        </div>

        <div class="form-grid">

            <div class="form-group full">
                <label>Produto</label>
                <input type="text" name="produto" placeholder="Nome do produto" required>
            </div>

            <div class="form-group">
                <label>Quantidade</label>
                <input type="number" name="quantidade" placeholder="Ex: 10" min="1" required>
            </div>

            <div class="form-group">
                <label>Corredor</label>
                <input type="text" name="corredor" placeholder="Ex: A" required>
            </div>

            <div class="form-group">
                <label>Prateleira</label>
                <input type="number" name="prateleira" placeholder="Ex: 3" min="1" required>
            </div>

            <div class="form-group">
                <label>Nível</label>
                <input type="number" name="nivel" placeholder="Ex: 2" min="1" required>
            </div>

        </div>

        <div class="acoes">
            <button type="submit">Salvar Recebimento</button>
            <a href="<?= url('menu') ?>" class="btn btn-secundario">Voltar ao Menu</a>
        </div>

    </form>

</div>

<script src="https://unpkg.com/html5-qrcode"></script>
<script>
let scanner;

function abrirScanner() {
    if (scanner) return;

    scanner = new Html5Qrcode('reader');
    scanner.start(
        { facingMode: 'environment' },
        { fps: 10, qrbox: 250 },
        function (codigoLido) {
            document.getElementById('codigo_barras').value = codigoLido;
            buscarProduto(codigoLido);
            scanner.stop().then(() => { scanner.clear(); scanner = null; });
        },
        function () { /* ignora erros de leitura */ }
    );
}

function buscarProduto(codigo) {
    fetch('<?= url('produtos/buscar-codigo') ?>?codigo=' + encodeURIComponent(codigo))
        .then(r => r.json())
        .then(data => {
            if (data.encontrado) {
                document.querySelector('input[name="produto"]').value = data.nome;
                document.querySelector('input[name="corredor"]').value = data.corredor;
                document.querySelector('input[name="prateleira"]').value = data.prateleira;
                document.querySelector('input[name="nivel"]').value = data.nivel;
            } else {
                alert('Produto não encontrado no cadastro.');
            }
        });
}
</script>
