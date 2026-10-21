<div align="center">

TeamGestor
Gerenciamento simples de equipes esportivas
Sistema web desenvolvido como projeto escolar para reunir, em uma única interface, telas e recursos de apoio à organização de uma equipe esportiva.

PHP · HTML · CSS · JavaScript · JSON
</div>

Sobre o projeto
O TeamGestor é uma aplicação web de caráter educacional voltada à organização de equipes esportivas. O projeto apresenta áreas distintas para administradores e atletas, com páginas para consultar informações e acessar funcionalidades relacionadas à rotina da equipe.

A proposta é manter a aplicação simples e fácil de executar localmente. Os dados de exemplo e os registros que precisam ser gravados são tratados por arquivos JSON e estruturas PHP — não é necessário configurar MySQL ou outro banco de dados.
Status: projeto escolar / demonstração. Não foi desenvolvido nem revisado para uso em produção.

Funcionalidades
Acesso e cadastro
- Tela de login.
- Formulários de cadastro de atletas e administradores.
- Tela de alteração e recuperação de senha para demonstração.
- Verificação de e-mails duplicados nos cadastros implementados.
Área do administrador
- Painel administrativo.
- Telas de gerenciamento de atletas.
- Agenda e partidas.
- Comunicados.
- Interface para permissões.
Área do atleta
- Painel do atleta e perfil.
- Consulta de agenda e plano de treino.
- Visualização de comunicados.
- Formulário para justificar ausência.
Observação: algumas telas representam fluxos demonstrativos. Nem todas as operações são persistidas em arquivo; consulte a seção Limitações conhecidas.
Tecnologias utilizadas
Tecnologia	Uso no projeto
PHP	Lógica das páginas, validações e montagem das respostas
HTML	Estrutura das telas, organizada na pasta html/
CSS	Estilos e apresentação visual
JavaScript	Interações no navegador
JSON	Armazenamento local de alguns cadastros e contas de demonstração


O projeto não utiliza framework ou banco de dados relacional como requisito para sua execução básica.
Executar localmente
Requisitos
- PHP instalado; ou
- XAMPP com Apache e PHP.
Opção 1 — servidor embutido do PHP
1. Clone o repositório:
   git clone https://github.com/chucruwt/TeamGestor.git
2. Entre na pasta do projeto:
   cd TeamGestor
3. Inicie o servidor de desenvolvimento:
   php -S localhost:8000
4. Abra no navegador:
   http://localhost:8000
O servidor embutido do PHP é indicado somente para testes locais. Ele não aplica automaticamente as regras de acesso do Apache configuradas para proteger a pasta dados/.
Opção 2 — XAMPP
1. Instale e inicie o XAMPP.
2. Copie a pasta TeamGestor para o diretório htdocs.
3. Inicie o Apache.
4. Acesse http://localhost/TeamGestor/.
Se você renomear a pasta do projeto, ajuste o endereço no navegador de acordo com o novo nome.
Estrutura do projeto
TeamGestor/
├── css/                 # Folhas de estilo
├── dados/               # Arquivos JSON usados pelos cadastros
├── html/                # Estrutura HTML das páginas
├── img/                 # Imagens, ícones e recursos visuais
├── js/                  # JavaScript do navegador
├── agenda_adm.php       # Agenda na área administrativa
├── agenda_atleta.php    # Agenda na área do atleta
├── atletas_adm.php      # Gerenciamento de atletas
├── cadastro_admin.php   # Cadastro de administrador
├── cadastro_atleta.php  # Cadastro de atleta
├── comunicados_adm.php  # Comunicados do administrador
├── comunicados_atleta.php
├── dados.php            # Dados de exemplo em estruturas PHP
├── index.php            # Ponto de entrada
├── json.php              # Leitura e gravação de arquivos JSON
├── login.php             # Fluxo de login
├── painel_adm.php        # Painel administrativo
├── painel_atleta.php     # Painel do atleta
├── partidas_adm.php      # Tela de partidas
├── plano_treino.php      # Plano de treino
├── template.php          # Funções para preencher os modelos HTML
├── LEIAME.txt            # Documentação complementar existente
└── README.md             # Documentação do projeto
A estrutura acima destaca os diretórios e arquivos principais; outros arquivos PHP complementam as páginas e os fluxos da aplicação.
Separação entre PHP e HTML
Nas páginas que seguem esse padrão, o arquivo .php contém a lógica e o arquivo correspondente em html/ contém a marcação da tela. O PHP preenche marcadores e blocos do HTML antes de devolver a página ao navegador.

Essa organização facilita a alteração da apresentação sem misturar toda a marcação com a lógica da aplicação.
Armazenamento dos dados
O TeamGestor não exige um banco de dados. Os registros de algumas operações são guardados em arquivos JSON dentro de dados/.
- usuarios.json: contas usadas no login.
- cadastros_atletas.json: informações enviadas pelo cadastro de atletas.
- cadastros_admins.json: informações enviadas pelo cadastro de administradores.
Os nomes acima refletem a estrutura documentada no projeto. Os arquivos precisam existir e estar acessíveis para escrita quando a aplicação precisar gravar novos registros.

Faça uma cópia de segurança da pasta dados/ antes de apagar ou editar seus arquivos. Ao editar JSON manualmente, mantenha a sintaxe válida.
Limitações conhecidas
Este projeto foi criado para fins educacionais e demonstração local. Entre as limitações documentadas estão:
- Sem banco de dados: o armazenamento é feito em arquivos JSON e estruturas PHP.
- Persistência parcial: contas e cadastros são gravados em JSON, mas várias ações demonstrativas — como determinadas alterações, aprovações, comunicados, eventos e permissões — podem não persistir após navegar ou recarregar a página.
- Segurança de senhas: a documentação atual informa que as senhas são armazenadas em texto puro nos arquivos JSON. Isso não é apropriado para contas reais.
- Sessões e autorização: a implementação documentada não estabelece uma sessão de login segura; a existência de telas separadas não deve ser considerada controle de acesso suficiente.
- Recuperação de senha: o código de recuperação é fixo para demonstração.
- Servidor embutido: o comando php -S é destinado a desenvolvimento local e não reproduz as proteções de configuração do Apache.
Não use o projeto para armazenar dados pessoais reais nem o publique como serviço acessível pela internet sem revisar e corrigir esses pontos. Uma versão destinada a uso real precisaria, no mínimo, de armazenamento seguro de senhas, sessões e autorização adequadas, validação de entradas e configuração segura de acesso aos dados.
Objetivo educacional
O projeto serve como exercício prático de desenvolvimento web, incluindo:
- organização de páginas PHP;
- formulários e tratamento de dados;
- estruturas condicionais e laços;
- uso de vetores e matrizes;
- leitura e gravação de JSON;
- separação entre lógica e apresentação.
Licença
Este repositório contém um arquivo LICENSE. Consulte-o para ver os termos de uso e distribuição aplicáveis ao projeto.
<div align="center">

Desenvolvido como projeto escolar para praticar conceitos básicos de desenvolvimento web.
</div>
