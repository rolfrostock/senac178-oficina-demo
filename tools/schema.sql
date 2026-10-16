CREATE TABLE clientes (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(80) NOT NULL,
    contato VARCHAR(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE solicitacoes (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT UNSIGNED NULL,
    equipamento VARCHAR(100) NOT NULL,
    assunto VARCHAR(160) NOT NULL,
    status ENUM('Recebido', 'Em diagnóstico', 'Concluído') NOT NULL DEFAULT 'Recebido',
    criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_solicitacao_cliente FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO clientes (nome, contato) VALUES
('Marina Costa (fictício)', 'Contato de exemplo A'),
('Rafael Lima (fictício)', 'Contato de exemplo B'),
('Bruno Santos (fictício)', 'Contato de exemplo C');
INSERT INTO solicitacoes (cliente_id, equipamento, assunto, status) VALUES
(1, 'Notebook Lenovo', 'Notebook não liga', 'Recebido'),
(2, 'Desktop montado', 'Substituir memória RAM', 'Em diagnóstico'),
(NULL, 'Notebook Dell', 'Verificar falha de inicialização', 'Recebido');
