# Braço Robótico

Projeto desenvolvido para a **Feira Tecnológica**, integrando um protótipo de braço robótico com Arduino e uma aplicação web para apresentar o dispositivo, organizar informações técnicas e interagir com os visitantes.

## Integrantes

- Lucas Braga
- Gustavo Lacerda
- Gustavo Ferroni
- Daniel Achcar

**Turma:** 2º DS AMS

## Apresentação em vídeo

**Vídeo:** [Adicionar o link do vídeo da equipe](https://drive.google.com/file/d/1PuRy4u3qnEBqX7c2bDlFUAIVCbg44fNA/view?usp=sharing)

> Após publicar o vídeo, substitua o texto acima pelo endereço público do YouTube.

## Projeto e simulação

- **Tinkercad:** [Acessar o projeto do braço robótico](https://www.tinkercad.com/things/5DKR8zgIWQp-braco?sharecode=Ym4qbowYRLuL_HKy80PfK6PDOhIyJoJqjXYvjDnkGhg)
- **Repositório:** [Adicionar o link público deste repositório](https://github.com/GGustavo-lac/Bra-o-robotico/tree/main)

## Capturas de tela do site

As imagens abaixo ficam na pasta `prints/`, na raiz deste repositório. Extraia os arquivos do pacote de capturas e coloque-os nessa pasta mantendo os nomes originais.

### Capturas da aplicação

![Captura 01 — site](<prints/Captura de tela 2026-11-22 145441.png>)

![Captura 02 — site](<prints/Captura de tela 2026-11-22 145503.png>)

![Captura 03 — site](<prints/Captura de tela 2026-11-22 145518.png>)

![Captura 04 — site](<prints/Captura de tela 2026-11-22 145525.png>)

![Captura 05 — site](<prints/Captura de tela 2026-11-22 145535.png>)

![Captura 06 — site](<prints/Captura de tela 2026-11-22 145554.png>)

![Captura 07 — site](<prints/Captura de tela 2026-11-22 145603.png>)

## Sobre o projeto

O projeto combina o desenvolvimento de um dispositivo físico — um braço robótico controlado por Arduino — com uma aplicação web em PHP. O site funciona como apoio à apresentação do protótipo na Feira Tecnológica, reunindo explicações sobre o funcionamento, informações dos componentes e recursos de interação com os visitantes.

O circuito e o modelo podem ser consultados pela simulação no Tinkercad, disponível no link desta documentação.

## Funcionalidades da aplicação web

- Apresentação do projeto e do funcionamento do braço robótico.
- Catálogo de componentes para consulta das peças do protótipo.
- Área de perguntas enviadas pelos visitantes e gerenciamento das respostas.
- Avaliações para registrar a opinião do público.
- Área administrativa para gerenciar os registros do sistema.
- Operações CRUD (**Create, Read, Update e Delete**) nas entidades principais de gerenciamento, incluindo componentes e perguntas.

## Tecnologias utilizadas

- **PHP** — lógica da aplicação e serviços.
- **MySQL** — persistência dos dados em banco relacional.
- **PDO** — comunicação entre PHP e MySQL.
- **HTML e CSS** — estrutura e apresentação das páginas.
- **JavaScript** — interações da interface.
- **Arduino** — controle eletrônico do protótipo físico.
- **Tinkercad** — simulação e visualização do circuito.

## Organização do projeto

A aplicação utiliza uma organização baseada em serviços: as páginas de entrada e apresentação ficam separadas das classes responsáveis pelas regras de negócio e do acesso ao banco de dados.

- `public/` — páginas públicas, arquivos estáticos e pontos de entrada da aplicação.
- `src/` — classes PHP e serviços responsáveis pelas operações do sistema.
- `config/` — configurações de conexão e ambiente.
- `database/` — scripts SQL para criação e carga inicial do banco, quando presentes no projeto.
- `prints/` — capturas de tela utilizadas nesta documentação.

## Banco de dados e modelo lógico

O sistema utiliza um banco relacional MySQL para persistir os dados. As entidades principais contemplam componentes, categorias, articulações, perguntas e avaliações. Os relacionamentos são definidos no esquema SQL do projeto.

Consulte os scripts da pasta `database/` e a documentação do modelo lógico, quando disponível, para visualizar as tabelas, chaves e relacionamentos implementados.

## Executar localmente

1. Instale o [XAMPP](https://www.apachefriends.org/) e inicie Apache e MySQL.
2. Coloque a pasta do projeto em `C:\xampp\htdocs\`.
3. Configure a conexão com o banco de dados no arquivo local de configuração, seguindo o exemplo fornecido no projeto.
4. Crie o banco e importe os scripts SQL disponibilizados na pasta `database/`, caso ainda não tenha feito essa etapa.
5. Acesse `http://localhost/braco-robotico-feira/public/` no navegador.

> **Atenção:** não publique senhas, credenciais locais ou arquivos de configuração que contenham dados sensíveis no repositório público. Cada integrante deve configurar o ambiente local conforme sua instalação do XAMPP.

## Entrega da Feira Tecnológica

- [ ] Repositório público no GitHub com o código-fonte e este README.
- [ ] Capturas de tela do site disponíveis na pasta `prints/`.
- [ ] Vídeo completo da equipe publicado no YouTube.
- [ ] Link do vídeo preenchido na seção **Apresentação em vídeo**.

---

**Feito pela equipe do Braço Robótico — 2º DS AMS.**
