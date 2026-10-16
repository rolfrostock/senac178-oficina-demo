CREATE TABLE solicitacoes (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    cliente VARCHAR(80) NOT NULL,
    equipamento VARCHAR(100) NOT NULL,
    assunto VARCHAR(160) NOT NULL DEFAULT '',
    status ENUM('Recebido', 'Em diagnóstico', 'Concluído') NOT NULL DEFAULT 'Recebido',
    criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO solicitacoes (cliente, equipamento, assunto, status) VALUES
('Marina Costa (fictício)', 'Notebook Lenovo', 'Notebook não liga', 'Recebido'),
('Rafael Lima (fictício)', 'Desktop montado', 'Substituir memória RAM', 'Em diagnóstico'),
('Bruno Santos (fictício)', 'Notebook Dell', '', 'Recebido');
