-- Criação da tabela Aluno
CREATE TABLE Aluno (
    id SERIAL PRIMARY KEY,
    nome TEXT NOT NULL,
    idade INTEGER NOT NULL,
    casa TEXT NOT NULL,
    ano INTEGER NOT NULL
);

-- Criação da tabela Materia
CREATE TABLE Materia (
    id SERIAL PRIMARY KEY,
    nome TEXT NOT NULL,
    ano INTEGER NOT NULL
);

-- Criação da tabela Professor
CREATE TABLE Professor (
    id SERIAL PRIMARY KEY,
    nome TEXT NOT NULL,
    materia_id INTEGER REFERENCES Materia(id)
);

-- Criação da tabela Funcionario
CREATE TABLE Funcionario (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    cargo VARCHAR(255) NOT NULL
);

-- Criação da tabela TacaDasCasas
CREATE TABLE TacaDasCasas (
    id     SERIAL  PRIMARY KEY,
    casa   TEXT    NOT NULL UNIQUE,
    pontos INTEGER NOT NULL DEFAULT 0
);

-- Inserção inicial das casas com pontos na tabela TacaDasCasas
INSERT INTO TacaDasCasas (casa, pontos) VALUES
('Lufa-Lufa', 60),
('Grifinória', 40),
('Sonserina', 35),
('Corvinal', 20);

-- Inserção de dados de exemplo na tabela Aluno
INSERT INTO Aluno (nome, idade, casa, ano) VALUES
('Harry Potter', 11, 'Grifinória', 1),
('Hermione Granger', 11, 'Grifinória', 1),
('Ron Weasley', 11, 'Grifinória', 1),
('Draco Malfoy', 11, 'Sonserina', 1),
('Cedrico Diggory', 14, 'Lufa-Lufa', 4);

-- Inserção de dados de exemplo na tabela Professor
INSERT INTO Professor (nome, materia_id) VALUES
('Minerva McGonagall', 1),  -- Transfiguração (1º ano)
('Severo Snape', 2),        -- Poções (1º ano)
('Filius Flitwick', 3),     -- Feitiços (1º ano)
('Pomona Sprout', 4),      -- Herbologia (1º ano)
('Remo Lupin', 8);         -- Defesa Contra as Artes das Trevas (3º ano)

-- Inserção de dados de exemplo na tabela Materia
INSERT INTO Materia (nome, ano) VALUES
('Transfiguração', 1),
('Poções', 1),
('Feitiços', 1),
('Herbologia', 1),
('História da Magia', 1),
('Astronomia', 2),
('Runas Antigas', 3),
('Defesa Contra as Artes das Trevas', 3),
('Trato das Criaturas Mágicas', 3),
('Adivinhação', 4),
('Feitiços Avançados', 4);

-- Inserção de dados de exemplo na tabela Professor
INSERT INTO Funcionario (nome, cargo) VALUES
('Alvo Dumbledore', 'Diretor de Hogwarts'),
('Minerva McGonagall', 'Diretora-adjunta'),
('Rubeus Hagrid', 'Guarda-caça e Guardião das Chaves e Terrenos de Hogwarts');
