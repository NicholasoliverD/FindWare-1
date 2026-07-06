<?php

/**
 * Model responsável pelos usuários do sistema (login/cadastro).
 */
class Usuario
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function encontrarPorEmailSenha(string $email, string $senha): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM usuarios WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $usuario = $stmt->fetch();

        if (!$usuario) {
            return null;
        }

        // Senhas novas ficam com hash (password_hash). Mantemos compatibilidade
        // com os registros antigos que ainda estão salvos em texto puro.
        if (password_verify($senha, $usuario['senha']) || $usuario['senha'] === $senha) {
            return $usuario;
        }

        return null;
    }

    public function emailExiste(string $email): bool
    {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM usuarios WHERE email = :email');
        $stmt->execute(['email' => $email]);
        return (bool) $stmt->fetchColumn();
    }

    public function criar(string $nome, string $email, string $senha): bool
    {
        $hash = password_hash($senha, PASSWORD_DEFAULT);

        $stmt = $this->pdo->prepare('INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)');
        return $stmt->execute(['nome' => $nome, 'email' => $email, 'senha' => $hash]);
    }
}
