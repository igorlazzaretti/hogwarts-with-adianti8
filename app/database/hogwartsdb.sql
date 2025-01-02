-- Criação da tabela Aluno
CREATE TABLE Aluno (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    idade INTEGER NOT NULL,
    casa VARCHAR(50) NOT NULL,
    ano INTEGER NOT NULL
);

-- Criação da tabela Professor
CREATE TABLE Professor (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    materia_id INTEGER REFERENCES Materia(id) -- Referencia a tabela Materia
);

-- Criação da tabela Materia
CREATE TABLE Materia (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    ano INTEGER NOT NULL
);

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
('Remo Lupin', 5);         -- Defesa Contra as Artes das Trevas (3º ano)

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

-- Gemini
-- me ajude a criar um conteudo .sql para meu banco de dados para um simples ERP da escola de Hogwarts. Com Duas tabelas principais Aluno, essa com ID, nome, idade, casa de hogwarts e ano. Outra tabela de Professores com id, nome e matéria. Uma tabela com as Matérias: id, nome, ano. Algumas regras: cada ano escolar, do primeiro ao quarto, possuirá algumas matérias vinculadas a ele. Pode me ajudar a elaborar algo assim? quero utilizar no adianti 8