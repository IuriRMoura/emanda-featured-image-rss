# Emanda – Featured Image in RSS

**Adicione automaticamente a imagem destacada ao feed RSS do WordPress.**  
Garanta que seus posts apareçam com uma imagem de capa consistente em leitores de notícias, agregadores e serviços externos — sem emojis ou imagens indesejadas.

[![WordPress tested up to](https://img.shields.io/badge/WordPress-6.6-blue.svg)](https://wordpress.org/plugins/)
[![License: GPL v2](https://img.shields.io/badge/License-GPLv2-blue.svg)](https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html)

---

## ✨ Principais Recursos
- **Imagem destacada no feed**: prefixada no `content:encoded`.
- **Media RSS opcional**: adiciona `<media:content>` e/ou `<enclosure>`.
- **Remoção de emojis** e imagens `<img class="wp-smiley">`.
- **Fallback inteligente**: usa a primeira imagem do post ou uma URL padrão definida nas configurações.
- **Controle de tamanho** da imagem diretamente pelo painel.
- **Compatível com o feed padrão** (`/feed/`) — não cria URLs extras.

---

## 📥 Instalação

1. **Baixe** o arquivo ZIP do plugin.
2. No WordPress, acesse **Plugins → Adicionar novo → Enviar plugin**.
3. **Ative** o plugin.
4. Acesse **Configurações → RSS – Imagem Destacada** para ajustar as opções.

---

## ⚙️ Configuração
- Escolha se quer incluir **Media RSS** e/ou **Enclosure**.
- Defina se quer **remover emojis** e smiles.
- Configure o **tamanho da imagem** e uma **URL de fallback**.

---

## 📂 Estrutura do Plugin
```
emanda-featured-image-rss.php
uninstall.php
readme.txt
languages/
```
- Domínio de tradução: `emanda-featured-image-rss`
- Arquivos `.pot` em `languages/` para tradução.

---

## 📜 Licença
Distribuído sob **GPL v2 ou posterior**.  
Mais informações em: [GPLv2 License](https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html)
