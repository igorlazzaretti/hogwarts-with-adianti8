-- Criação da tabela Aluno
CREATE TABLE Aluno (
    id    SERIAL PRIMARY KEY,
    nome  TEXT NOT NULL,
    idade INTEGER NOT NULL,
    casa  TEXT NOT NULL,
    ano   INTEGER NOT NULL
);

-- Criação da tabela Materia
CREATE TABLE Materia (
    id   SERIAL PRIMARY KEY,
    nome TEXT NOT NULL,
    ano  INTEGER NOT NULL,
    assunto TEXT NOT NULL
);


-- Criação da tabela Professor
CREATE TABLE Professor (
    id          SERIAL PRIMARY KEY,
    nome        TEXT NOT NULL,
    materia_id  INTEGER REFERENCES Materia(id),
    curiosidade TEXT 
);

-- Criação da tabela Funcionario
CREATE TABLE Funcionario (
    id      SERIAL PRIMARY KEY,
    nome    VARCHAR(255) NOT NULL,
    cargo   VARCHAR(255) NOT NULL,
    funcoes TEXT NOT NULL
);

-- Criação da tabela TacaDasCasas
CREATE TABLE TacaDasCasas (
    id     SERIAL  PRIMARY KEY,
    casa   TEXT    NOT NULL UNIQUE,
    pontos INTEGER NOT NULL DEFAULT 0
);

-- Inserção inicial das casas com pontos na tabela TacaDasCasas
INSERT INTO TacaDasCasas (casa, pontos) VALUES
('Lufa-Lufa',  60),
('Grifinória', 40),
('Sonserina',  35),
('Corvinal',   20);

-- Inserção de dados de exemplo na tabela Aluno
INSERT INTO Aluno (nome, idade, casa, ano) VALUES
('Harry Potter',     11, 'Grifinória', 1),
('Hermione Granger', 11, 'Grifinória', 1),
('Ron Weasley',      11, 'Grifinória', 1),
('Draco Malfoy',     11, 'Sonserina',  1),
('Cedrico Diggory',  14, 'Lufa-Lufa',  4);

-- Inserção de dados de exemplo na tabela Materia
INSERT INTO Materia (nome, ano, assunto) VALUES
('Transfiguração',                    1, 'Transformação de objetos e seres.Exemplo: Transformar um rato em uma taça de cristal.'),
('Poções',                            1, 'Preparo de poções mágicas com diversos efeitos. Exemplos: Poção Polissuco (permite transformar-se em outra pessoa) e Poção da Sorte Felix Felicis (dá sorte ao bebedor).'),
('Feitiços',                          1, 'Lançamento de feitiços com varinhas mágicas. Exemplos: Wingardium Leviosa (faz objetos levitarem), Accio (traz objetos para perto) e Lumos (acende a ponta da varinha).'),
('Herbologia',                        1, 'Cultivo e estudo de plantas mágicas. Exemplos: Cultivo de Mandrágoras (cujo grito é fatal), Visgo do Diabo (planta carnívora perigosa), Guelricho (respirar em baixo de água).'),
('História da Magia',                 1, 'Eventos históricos do mundo bruxo. Exemplos: Rebeliões dos Duendes, Ascensão e queda de bruxos famosos, Guerras Bruxas, Criação do Ministério da Magia...'),
('Astronomia',                        2, 'Estudo dos astros e constelações. Exemplos: Identificação de estrelas e planetas, Mapeamento de constelações, Cálculo de movimentos planetários...'),
('Runas Antigas',                     3, 'Tradução e interpretação de runas mágicas. Exemplos: Alfabeto rúnico, Gramática e sintaxe de runas, Tradução de inscrições antigas...'),
('Defesa Contra as Artes das Trevas', 3, 'Defesa contra magia negra e criaturas das trevas. Exemplos: Feitiços de proteção contra Azarações, Defesa contra Dementadores, Combate a lobisomens e vampiros, Neutralização de Maldições Imperdoáveis...'),
('Trato das Criaturas Mágicas',       3, 'Exemplos: Classificação do Ministério da Magia para criaturas, Hábitos e cuidados com: Hipogrifos, Unicórnios, Acromântulas, Testrálios, Fênix...'),
('Adivinhação',                       4, 'Previsão do futuro através de diferentes métodos. Exemplos: Leitura de folhas de chá, Interpretação de bolas de cristal, Quiromancia (leitura das mãos), Cartomancia (leitura de cartas)...'),
('Feitiços Avançados',                4, 'Feitiços complexos e avançados. Exemplos: Feitiços de transformação avançada, Encantamentos complexos, Maldições e contra-maldições, Feitiços não-verbais...');

-- Inserção de dados de exemplo na tabela Professor
INSERT INTO Professor (nome, materia_id, curiosidade) VALUES
('Minerva McGonagall', 1, 'Ela é uma animaga, capaz de se transformar em um gato malhado.'), -- Transfiguração (1º ano)
('Severo Snape',       2, 'Ele inventou vários feitiços, incluindo o Sectumsempra (que corta o oponente) e o Levicorpus (que suspende a pessoa no ar pelos tornozelos).'), -- Poções (1º ano)
('Filius Flitwick',    3, 'Flitwick é um mestre em feitiços e encanta o Salão Principal com decorações mágicas incríveis para o Natal.'), -- Feitiços (1º ano)
('Pomona Sprout',      4, 'Ela cultivou as mandrágoras que foram usadas para reviver as vítimas petrificadas pelo Basilisco em "Harry Potter e a Câmara Secreta".'), -- Herbologia (1º ano)
('Remo Lupin',         8, 'Ele ajudou a criar o Mapa do Maroto junto com seus amigos James Potter, Sirius Black e Pedro Pettigrew.'); -- Defesa Contra as Artes das Trevas (3º ano)

-- Inserção de dados de exemplo na tabela Professor
INSERT INTO Funcionario (nome, cargo, funcoes) VALUES
('Alvo Dumbledore',    'Diretor de Hogwarts', 'Administração e orientação geral da escola'),
('Minerva McGonagall', 'Diretora-adjunta',    'Coordenação de professores e alunos'),
('Rubeus Hagrid',      'Guarda-caça e Guardião das Chaves e Terrenos de Hogwarts', 'Sua função é cuidar dos aniamis mágicos, das chaves e dos terrenos da escola'),
('Argo Filch',         'Zelador de Hogwarts', 'Limpeza e manutenção da escola'),
('Percy Weasley',      'Monitor-chefe da Grifinória', 'Fiscalização e organização dos alunos da Grifinória'),
('Penélope Clearwater','Monitora-chefe da Corvinal',  'Fiscalização e organização dos alunos da Corvinal'),
('Ernie Macmillan',    'Monitor-chefe da Lufa-Lufa',  'Fiscalização e organização dos alunos da Lufa-Lufa'),
('Draco Malfoy',       'Monitor-chefe da Sonserina',  'Fiscalização e organização dos alunos da Sonserina');
