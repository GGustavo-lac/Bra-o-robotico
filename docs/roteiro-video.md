# Roteiro atualizado para apresentar o projeto (5–7 minutos)

| Tempo | O que mostrar | O que explicar |
|---|---|---|
| 0:00–0:45 | Protótipo físico funcionando | Objetivo do braço robótico e a relação entre automação, Arduino e movimento. |
| 0:45–1:40 | `como-funciona.php` | Potenciômetro → leitura do Arduino → conversão em ângulo → servomotor. Mostrar base, ombro, cotovelo e garra. |
| 1:40–2:30 | Tinkercad | Abrir o circuito fornecido e mostrar a placa, os quatro potenciômetros e os quatro servomotores. |
| 2:30–3:30 | `database/schema.sql` e diagrama | Explicar categorias, componentes, articulações, perguntas e avaliações; destacar as chaves estrangeiras e regras de integridade. |
| 3:30–4:40 | Site público | Navegar pelo catálogo, enviar uma pergunta sem cadastro e registrar uma avaliação anônima. |
| 4:40–5:50 | Área administrativa | Entrar com o usuário da equipe, revisar a pergunta, escrever a resposta, publicar e editar um componente. |
| 5:50–6:30 | API e encerramento | Abrir `/api/componentes.php`, explicar que ela só permite consulta pública e concluir. |

## Preparação

- [ ] Banco criado e `seed.sql` importado.
- [ ] Credenciais da equipe configuradas em `config/config.local.php`.
- [ ] Testar cadastro de pergunta, publicação, avaliação e edição de componente.
- [ ] Confirmar que as páginas administrativas redirecionam para login quando não autenticadas.
- [ ] Abrir o projeto do Tinkercad e confirmar que a simulação está compartilhada.
- [ ] Gravar a apresentação mostrando o braço real e o site, sem afirmar que o site controla o hardware.
