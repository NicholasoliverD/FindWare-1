<div class="page-header">
    <h2>Cadastrar Produto</h2>
    <p>Registre um novo item no estoque e gere automaticamente seu código para localização.</p>
</div>

<div class="card">

    <form action="<?= url('produtos/salvar') ?>" method="POST">

        <div class="form-grid">

            <div class="form-group full">
                <label>Nome do produto</label>
                <input type="text" name="nome" placeholder="Ex: Mouse Gamer" required>
            </div>

            <div class="form-group">
                <label>Código interno</label>
                <input type="text" id="codigo" name="codigo" placeholder="Ex: 789123" required oninput="gerarQRCode()">
            </div>

            <div class="form-group">
                <label>Código de barras / QR Code</label>
                <input type="text" id="codigo_barras" name="codigo_barras" placeholder="Gerado automaticamente" readonly>
            </div>

            <div class="form-group">
                <label>Quantidade em estoque</label>
                <input type="number" name="estoque" placeholder="Ex: 10" min="0" required>
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

        <div class="card" id="qrBox" style="display:none; background:var(--card-secundario);">
            <div style="display:flex; gap:20px; align-items:center;">
                <img id="qrImg" src="" style="width:120px;height:120px;border:1px solid var(--borda);border-radius:8px;background:white;padding:6px;">
                <div>
                    <strong>QR Code gerado automaticamente</strong>
                    <p style="color:var(--texto-suave); font-size:13.5px; margin-top:6px;">
                        Esse código poderá ser usado pelo scanner para entrada, saída e localização do produto.
                    </p>
                </div>
            </div>
        </div>

        <div class="acoes">
            <button type="submit">Salvar Produto</button>
            <a class="btn btn-secundario" href="<?= url('menu') ?>">Voltar ao Menu</a>
        </div>

    </form>

</div>

<script>
function gerarQRCode() {
    const codigo = document.getElementById('codigo').value.trim();
    document.getElementById('codigo_barras').value = codigo;

    const box = document.getElementById('qrBox');
    const img = document.getElementById('qrImg');

    if (codigo !== '') {
        box.style.display = 'block';
        img.src = 'https://api.qrserver.com/v1/create-qr-code/?size=160x160&data=' + encodeURIComponent(codigo);
    } else {
        box.style.display = 'none';
        img.src = '';
    }
}
</script>
