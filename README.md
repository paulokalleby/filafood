# Filafood 🧑‍💻

🚀 **Filafood** é um SaaS **multi-tenant** projetado para gestão de hamburguerias, proporcionando funcionalidades modernas para acompanhar e gerenciar os processos de atendimento e produção.

---

## 🛠️ Recursos

- **Modelo Multi-tenant**: Arquitetura _single database_ para atender múltiplos clientes.
- **CRUD Completo**: Gerenciamento de Categorias, Produtos, Papéis e Usuários.
- **Gestão de Atendimentos**: Controle eficiente de comandas e pedidos.
- **Acompanhamento de Cozinha**: Monitoramento em tempo real do preparo dos pedidos.
- **Autenticação Segura**: Implementação de JWT com Sanctum.
- **Recuperação de Senha**: Envio de código de validação por e-mail.
- **Autorização Avançada**: ACL baseada em "Roles and Permissions".
- **Validações Robustas**: FormRequest no backend e Vuelidate no frontend.
- **Tema Personalizável**: Suporte a Dark Mode.
- **Documentação de API**: Desenvolvida com Swagger.
- **Aplicativo Mobile**: Solução prática para garçons.

---

## 🔧 Tecnologias Utilizadas

- **Linguagem**: PHP 8.3, JavaScript.
- **Frameworks**: Laravel 11, Vue.js 3, Vuetify.
- **Banco de Dados**: MySQL.
- **Cache**: Redis.
- **Ferramentas**: Docker, Laravel Sail, Swagger, Mailpit.

---

## 🚀 Como Rodar o Projeto

## 1. Clonar o Repositório

```bash
git clone git@github.com:paulokalleby/filafood.git
```

## 2. Backend

#### Navegar até o Diretório do Backend

```bash
cd filafood/filafood-api
```

#### Configurar o Ambiente

```bash
cp .env.example .env
```

#### Subir containers do projeto

```bash
./vendor/bin/sail up -d
```

#### Instalar dependências

```bash
./vendor/bin/sail composer i
```

#### Gerar a Chave do Projeto Laravel

```bash
./vendor/bin/sail artisan key:generate
```

#### Executar as Migrações e Popular o Banco de Dados

```bash
./vendor/bin/sail artisan migrate --seed
```

#### Acesse a documentação da api

[http://localhost](http://localhost)

## 3. Frontend

#### Navegar até o Diretório do Frontend

```sh
cd filafood/filafood-web
```

#### Subir aplicação em container nginx

```sh
docker-compose up -d
```

#### Acesse a aplicação frontend

[http://localhost:3001](http://localhost:3001) para visualizar o frontend.

## 🌟 Contribuições

Contribuições são bem-vindas! Sinta-se à vontade para abrir issues ou enviar pull requests para melhorias.

## 📜 Licença

Este projeto está licenciado sob a MIT License.

## 📞 Contato

### Paulo Kalleby Alves Lacerda

- [LinkedIn](linkedin.com/in/paulokalleby)
- [E-mail](mailto:paulo.devweb@gmail.com)
