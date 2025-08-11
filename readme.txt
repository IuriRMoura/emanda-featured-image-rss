=== Emanda – Featured Image in RSS ===
Contributors: emanda
Tags: rss, feed, featured image, media rss, enclosure, emoji
Requires at least: 5.0
Tested up to: 6.6
Requires PHP: 7.2
Stable tag: 1.1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Garante imagem de capa consistente no RSS padrão do WordPress: prefixa a imagem destacada no conteúdo do feed e, opcionalmente, adiciona `<media:content>` e `<enclosure>`. Inclui fallbacks e remoção de emojis no feed.

== Description ==
Este plugin garante que cada item do seu feed RSS tenha uma imagem consistente:

* Prefixa a **imagem destacada** no conteúdo do feed (opcional).
* Adiciona **Media RSS** (`<media:content>`) e **`<enclosure>`** (opcional).
* **Remove emojis** no feed para evitar que virem `<img>`.
* **Fallbacks**: primeira imagem do conteúdo ou **imagem padrão** definida nas opções.
* Escolha o **tamanho** da imagem (thumbnail, medium, large, full, etc).

**Não cria novo feed.** O link do feed continua o mesmo (`/feed/`).

== Installation ==
1. Faça upload do arquivo ZIP em *Plugins → Adicionar novo → Fazer upload do plugin*.
2. Ative o plugin.
3. Vá em *Configurações → RSS – Imagem Destacada* e ajuste as opções.

== Frequently Asked Questions ==
= O plugin muda a URL do feed? =
Não. Ele atua sobre o RSS padrão do WordPress.

= Preciso limpar cache? =
Se você usa plugin de cache/CDN, limpe o cache após ativar/alterar as opções.

= Funciona com qualquer tema? =
Sim. Não depende do tema.

== Screenshots ==
1. Tela de configurações do plugin com opções de imagem e tags do feed.

== Changelog ==
= 1.1.0 =
* Adiciona opção para remover `<img class="wp-smiley">` do HTML do feed.
* Pequenas melhorias de internacionalização e sanitização.

= 1.0.0 =
* Versão inicial pública.
