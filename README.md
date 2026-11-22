# Braço Robótico — documentação do protótipo

Site de apresentação de um braço robótico didático com Arduino Uno, quatro controles por potenciômetro e quatro servomotores conforme o cadastro atual. O objetivo é explicar o projeto, documentar componentes, mostrar o circuito de simulação e reunir perguntas da feira.

> O site é informativo. Não envia comandos ao Arduino e não controla o braço físico.

## O que foi revisto

- Direção visual editorial/industrial, sem framework, fontes remotas ou animações decorativas.
- Página inicial organizada em objetivo, sequência de controle, eletrônica, articulações, circuito e limitações.
- Página de funcionamento com explicação de entrada analógica, conversão e atuação; inclui referências Arduino, repositórios GitHub e vídeos YouTube consultados.
- Catálogo ligado ao banco, em tabela pesquisável por nome, categoria e função.
- Perguntas e avaliações anônimas com formulários mais claros.
- Área da equipe continua separada e autenticada; a API pública continua somente para leitura.
- Melhorias de acessibilidade: foco visível, skip link, navegação móvel sem mouse, rótulos explícitos e suporte a `prefers-reduced-motion`.

## Estrutura

```text
braco-robotico-feira/
├── config/
│   ├── config.php
│   ├── config.local.example.php
│   └── config.local.php
├── database/
│   ├── schema.sql
│   ├── seed.sql
│   ├── migrate_v2.sql
│   └── instalar_banco_completo.sql
├── docs/
│   ├── modelo-logico.md
│   ├── roteiro-video.md
│   ├── pesquisa-e-referencias.md
│   └── img/
├── public/
│   ├── admin/
│   ├── api/
│   ├── css/style.css
│   ├── js/site.js
│   └── *.php
└── src/
```

## Instalação no XAMPP

1. Extraia a pasta `braco-robotico-feira` em `C:\xampp\htdocs\`.
2. Inicie Apache e MySQL no XAMPP.
3. A configuração local incluída usa `127.0.0.1`, porta `3307`, banco `braco_robotico`, usuário `root` e senha vazia, conforme a configuração informada neste atendimento. Se seu MySQL for diferente, ajuste `config/config.local.php`.
4. No phpMyAdmin, selecione `braco_robotico` e importe `database/instalar_banco_completo.sql`. O script cria tabelas ausentes e insere o catálogo inicial; não contém `DROP TABLE`.
5. Acesse `http://localhost/braco-robotico-feira/public/`.

### Configurar o painel administrativo

Gere um hash no terminal:

```bash
php -r "echo password_hash('SUA_SENHA_FORTE', PASSWORD_DEFAULT), PHP_EOL;"
```

Preencha `admin.user` e `admin.password_hash` em `config/config.local.php`. O painel fica desabilitado até existir um usuário e um hash válidos.

## Atualização de uma instalação antiga

1. Faça backup do banco antes de migrar.
2. Para estrutura antiga com tabela de visitantes e perguntas vinculadas a visitantes, execute uma única vez `database/migrate_v2.sql`.
3. Execute `database/seed.sql` para completar o catálogo.
4. Substitua os arquivos da aplicação, mantendo sua configuração local se já tiver usuário/hash do painel definidos.

## Banco de dados

| Tabela | Uso |
|---|---|
| `categorias` | Grupos do catálogo técnico |
| `componentes` | Peças, função, quantidade e especificação |
| `articulacoes` | Base, ombro, cotovelo e garra, relacionadas aos componentes |
| `perguntas` | Perguntas anônimas e respostas revisadas pela equipe |
| `avaliacoes` | Nota de 1 a 5, comentário opcional e data, sem identificação |

O banco alimenta conteúdo que muda durante a feira: inventário, articulações, dúvidas recebidas, respostas publicadas e avaliações. A documentação do modelo lógico está em `docs/modelo-logico.md`.

## Referências e precisão

Consulte `docs/pesquisa-e-referencias.md` para os documentos oficiais, repositórios GitHub e vídeos YouTube usados como comparação.

Os repositórios e vídeos citados são referências externas; não representam o firmware original deste protótipo. O arquivo `.ino` do projeto e o mapeamento definitivo dos pinos não vieram no pacote. Para verificar ligações, use o circuito compartilhado no Tinkercad:

https://www.tinkercad.com/things/5DKR8zgIWQp-braco?sharecode=Ym4qbowYRLuL_HKy80PfK6PDOhIyJoJqjXYvjDnkGhg

**Atenção à alimentação:** a imagem de simulação contém uma bateria de 9 V, mas ela não deve ser ligada diretamente aos servos. Confirme a tensão e a corrente exigidas pelos motores; vários servos podem precisar de fonte regulada externa com GND comum ao Arduino.

## Requisitos e segurança

- PHP 8.1+
- MySQL 8.0.16+ ou MariaDB 10.4+
- Extensões PDO e PDO MySQL
- Apache (XAMPP/Laragon)
- Senhas protegidas por `password_hash`/`password_verify`, sessão regenerada no login, CSRF nos formulários administrativos, consultas PDO preparadas e escape de saída HTML.
- Configure `public/` como document root em produção; `config/`, `database/`, `docs/` e `src/` devem ficar fora da raiz pública. Use HTTPS antes de publicar na internet.

## Validação deste pacote

Os arquivos PHP passaram por `php -l`, o JavaScript por `node --check` e as referências locais a imagens/arquivos foram verificadas. Este ambiente não dispõe de um servidor MySQL ativo nem do driver PDO MySQL, então a execução real de consultas e formulários precisa ser confirmada no XAMPP/Laragon local.
