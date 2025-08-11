# Emanda – Featured Image in RSS

Garante imagem de capa consistente no RSS padrão do WordPress: prefixa a imagem destacada no conteúdo do feed e, opcionalmente, adiciona `<media:content>` e `<enclosure>`. Inclui fallbacks e remoção de emojis no feed.

[![WordPress tested up to](https://img.shields.io/badge/WordPress-6.6-blue.svg)](https://wordpress.org/plugins/)
[![License: GPL v2](https://img.shields.io/badge/License-GPLv2-blue.svg)](https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html)

## Recursos
- Prefixa **imagem destacada** no `content:encoded` do feed.
- Adiciona **Media RSS** (`<media:content>`) e/ou **`<enclosure>`** (opcional).
- **Remove emojis** no feed e `<img class="wp-smiley">` (opcional).
- **Fallbacks**: 1ª imagem do conteúdo ou uma **URL padrão**.
- Escolha de **tamanho** da imagem.

> **Não cria um novo feed** — atua no RSS padrão (`/feed/`).

## Instalação
1. Faça upload do ZIP em **Plugins → Adicionar novo → Fazer upload do plugin**.
2. Ative.
3. Vá em **Configurações → RSS – Imagem Destacada** para ajustar as opções.

## Desenvolvimento
Requisitos: PHP 7.2+, WordPress 5.0+

Estrutura principal:
```
emanda-featured-image-rss.php
uninstall.php
readme.txt
languages/
```

### Internacionalização
- Domínio de texto: `emanda-featured-image-rss`
- Arquivos em `languages/` (inclui `.pot`).

## Publicação automática no WordPress.org (GitHub Actions)
Este repositório inclui um workflow que envia para o **repositório SVN do WordPress.org** quando você cria uma **tag** no formato `v*` (ex.: `v1.1.0`).

### Passos
1. **Repositório público** no GitHub (necessário).
2. Crie **Secrets** no GitHub (Settings → Secrets → Actions):
   - `SVN_USERNAME` → seu usuário do WordPress.org
   - `SVN_PASSWORD` → sua senha do WordPress.org
3. Ajuste o `slug` no workflow se necessário (padrão: `emanda-featured-image-rss`).
4. Faça uma tag e push:
   ```bash
   git tag v1.1.0
   git push origin v1.1.0
   ```

### O que o workflow faz
- Gera o pacote ignorando arquivos dev via `.distignore`.
- Publica no `trunk/` e cria a tag correspondente no SVN do WordPress.org.
- Publica assets contidos em `.wordpress-org/` (banners e ícones).

## Licença
GPLv2 ou posterior.
