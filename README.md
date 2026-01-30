# Memora Movie - PHP

Aplicação **PHP pura** para criação de filmes cinematográficos a partir de memórias pessoais.

## Requisitos

- PHP 8.2+
- SQLite3 (ou MySQL para produção)

## Como Rodar

### Desenvolvimento Local

```bash
# Iniciar servidor PHP
php -S localhost:8080 index.php
```

Acesse: **http://localhost:8080**

### Admin

Acesse: **http://localhost:8080/admin**

Credenciais padrão:
- Usuário: `admin`
- Senha: `admin123`

## Estrutura

```
memora-movie/
├── api/                    # Backend API REST
│   ├── index.php           # Router da API
│   ├── config.sqlite.php   # Configuração SQLite
│   └── controllers/        # Controllers PHP
├── views/                  # Frontend PHP
│   ├── layout/             # Base, Navbar, Footer
│   ├── components/         # Componentes reutilizáveis
│   │   ├── ui/             # Button, Modal
│   │   └── sections/       # Hero, Pricing, FAQ, etc.
│   ├── pages/              # Páginas públicas
│   └── admin/              # Painel administrativo
├── public/                 # Assets estáticos
│   └── assets/
│       └── js/app.js
├── storage/                # Banco SQLite
├── index.php               # Entry point / Router
└── .htaccess               # Configuração Apache
```

## Tecnologias

- **Backend**: PHP 8.2+ puro (sem frameworks)
- **Frontend**: HTML5 + Tailwind CSS (via CDN)
- **JavaScript**: Vanilla JS ES6+
- **Banco de Dados**: SQLite (dev) / MySQL (prod)
- **API**: REST JSON

## Deploy (Apache)

O arquivo `.htaccess` já está configurado para redirecionar todas as requisições para `index.php`.

## Licença

Proprietário - Memora Movie Studios
