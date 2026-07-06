<?php if ($erro): ?>

    <div class="card" style="max-width:620px;">
        <div class="page-header">
            <h2>Não foi possível localizar o produto</h2>
            <p><?= e($erro) ?></p>
        </div>
        <div class="acoes">
            <a href="<?= url('produtos/localizar') ?>" class="btn">Voltar para Localização</a>
            <a href="<?= url('menu') ?>" class="btn btn-secundario">Voltar ao Menu</a>
        </div>
    </div>

<?php else: ?>

    <div class="page-header">
        <h2>Mapa do Centro de Distribuição</h2>
        <p>A posição do produto foi destacada no mapa abaixo.</p>
    </div>

    <div class="layout-mapa">

        <div class="card">
            <div class="page-header" style="margin-bottom:16px;">
                <h2><?= e($produto['nome']) ?></h2>
                <p>Informações do produto localizado.</p>
            </div>

            <div class="info-produto">
                <p><strong>Código:</strong> <?= e($produto['codigo']) ?></p>
                <p><strong>Código de barras:</strong> <?= e($produto['codigo_barras']) ?></p>
                <p><strong>Estoque:</strong> <?= (int) $produto['estoque'] ?></p>
                <p><strong>Corredor:</strong> <?= e($produto['corredor']) ?></p>
                <p><strong>Prateleira:</strong> <?= e($produto['prateleira']) ?></p>
                <p><strong>Nível:</strong> <?= e($produto['nivel']) ?></p>
            </div>

            <div class="aviso aviso-azul" style="margin-top:18px; margin-bottom:0;">
                Use o som para auxiliar na localização do produto.
                <div style="margin-top:10px;">
                    <audio id="beep" src="<?= asset('beep.mp3') ?>"></audio>
                    <button type="button" onclick="document.getElementById('beep').play()">Testar Som</button>
                </div>
            </div>

            <div class="acoes">
                <a href="<?= url('produtos/localizar') ?>" class="btn btn-secundario">Nova Busca</a>
                <a href="<?= url('menu') ?>" class="btn btn-secundario">Voltar ao Menu</a>
            </div>
        </div>

        <div class="card">

            <div class="mapa-legenda">
                <div class="legenda-item"><div class="quadrado-legenda"></div> Posição livre</div>
                <div class="legenda-item"><div class="quadrado-legenda ativo-legenda"></div> Produto encontrado</div>
            </div>

            <div class="grade-mapa">
                <?php
                $corredores = ['A', 'B', 'C', 'D'];
                foreach ($corredores as $corredor):
                    for ($p = 1; $p <= 4; $p++):
                        $ativo = (strtoupper($corredor) === strtoupper($produto['corredor']) && (string) $p === (string) $produto['prateleira']) ? 'ativo' : '';
                ?>
                    <div class="posicao <?= $ativo ?>"><?= $corredor . $p ?></div>
                <?php
                    endfor;
                endforeach;
                ?>
            </div>

        </div>

    </div>

<?php endif; ?>
