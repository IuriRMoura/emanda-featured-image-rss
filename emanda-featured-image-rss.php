<?php
/**
 * Plugin Name:       Emanda – Featured Image in RSS
 * Plugin URI:        https://github.com/IuriRMoura/emanda-featured-image-rss
 * Description:        Adds the post’s featured image to the default WordPress RSS feed with optional Media RSS/enclosure, fallbacks, and emoji cleanup.
 * Version:           1.1.3
 * Requires at least: 5.0
 * Requires PHP:      7.2
 * Author:            Emanda
 * Author URI:        https://www.emanda.com.br/
 * License:           GPLv2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       emanda-featured-image-rss
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Emanda_FIR_Plugin' ) ) :

final class Emanda_FIR_Plugin {
	private $opt_key = 'emanda_fir_options';

	public function defaults() {
		return array(
			'prepend_in_content' => 1,
			'add_media_content'  => 1,
			'add_enclosure'      => 1,
			'strip_emojis'       => 1,
			'strip_smileys_html' => 1,
			'fallback_first_img' => 1,
			'default_img'        => '',
			'image_size'         => 'large',
		);
	}

	public function init() {
		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
		add_filter( 'the_content_feed', array( $this, 'filter_content_feed' ), 10, 2 );
		add_action( 'rss2_ns', array( $this, 'add_media_namespace' ) );
		add_action( 'rss2_item', array( $this, 'print_media_and_enclosure' ) );
		add_action( 'init', array( $this, 'maybe_disable_emojis_in_feed' ), 1 );
	}

	public function load_textdomain() {
		load_plugin_textdomain( 'emanda-featured-image-rss', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}

	private function get_options() {
		$opts = get_option( $this->opt_key, array() );
		if ( ! is_array( $opts ) ) {
			$opts = array();
		}
		return wp_parse_args( $opts, $this->defaults() );
	}

	public function register_settings() {
		register_setting( $this->opt_key, $this->opt_key, array( $this, 'sanitize_options' ) );

		add_settings_section( 'efir_main', __( 'Configurações do RSS', 'emanda-featured-image-rss' ), '__return_false', $this->opt_key );
		add_settings_field( 'image_size', __( 'Tamanho da imagem', 'emanda-featured-image-rss' ), array( $this, 'field_image_size' ), $this->opt_key, 'efir_main' );
		add_settings_field( 'prepend_in_content', __( 'Prefixar imagem no conteúdo do feed', 'emanda-featured-image-rss' ), array( $this, 'field_prepend' ), $this->opt_key, 'efir_main' );
		add_settings_field( 'add_media_content', __( 'Incluir &lt;media:content&gt;', 'emanda-featured-image-rss' ), array( $this, 'field_media' ), $this->opt_key, 'efir_main' );
		add_settings_field( 'add_enclosure', __( 'Incluir &lt;enclosure&gt;', 'emanda-featured-image-rss' ), array( $this, 'field_enclosure' ), $this->opt_key, 'efir_main' );
		add_settings_field( 'strip_emojis', __( 'Remover emojis no feed', 'emanda-featured-image-rss' ), array( $this, 'field_emojis' ), $this->opt_key, 'efir_main' );
		add_settings_field( 'strip_smileys_html', __( 'Remover &lt;img class="wp-smiley"&gt; do conteúdo do feed', 'emanda-featured-image-rss' ), array( $this, 'field_smileys' ), $this->opt_key, 'efir_main' );
		add_settings_field( 'fallback_first_img', __( 'Fallback: usar 1ª imagem do conteúdo', 'emanda-featured-image-rss' ), array( $this, 'field_fallback' ), $this->opt_key, 'efir_main' );
		add_settings_field( 'default_img', __( 'Imagem padrão (URL)', 'emanda-featured-image-rss' ), array( $this, 'field_default_img' ), $this->opt_key, 'efir_main' );
	}

	public function field_image_size() {
		$o = $this->get_options();
		$sizes = $this->get_all_img_sizes();
		echo '<select name="' . esc_attr( $this->opt_key ) . '[image_size]">';
		foreach ( $sizes as $size ) {
			printf( '<option value="%s"%s>%s</option>', esc_attr( $size ), selected( $o['image_size'], $size, false ), esc_html( $size ) );
		}
		echo '</select>';
	}

	public function field_prepend() {
		$o = $this->get_options();
		printf( '<label><input type="checkbox" name="%s[prepend_in_content]" value="1"%s> %s</label>', esc_attr( $this->opt_key ), checked( $o['prepend_in_content'], 1, false ), esc_html__( 'Adicionar a imagem antes do conteúdo do feed.', 'emanda-featured-image-rss' ) );
	}

	public function field_media() {
		$o = $this->get_options();
		printf( '<label><input type="checkbox" name="%s[add_media_content]" value="1"%s> %s</label>', esc_attr( $this->opt_key ), checked( $o['add_media_content'], 1, false ), esc_html__( 'Imprimir <media:content> por item.', 'emanda-featured-image-rss' ) );
	}

	public function field_enclosure() {
		$o = $this->get_options();
		printf( '<label><input type="checkbox" name="%s[add_enclosure]" value="1"%s> %s</label>', esc_attr( $this->opt_key ), checked( $o['add_enclosure'], 1, false ), esc_html__( 'Imprimir <enclosure> por item.', 'emanda-featured-image-rss' ) );
	}

	public function field_emojis() {
		$o = $this->get_options();
		printf( '<label><input type="checkbox" name="%s[strip_emojis]" value="1"%s> %s</label>', esc_attr( $this->opt_key ), checked( $o['strip_emojis'], 1, false ), esc_html__( 'Desabilitar conversão de emojis em imagens quando o contexto for feed.', 'emanda-featured-image-rss' ) );
	}

	public function field_smileys() {
		$o = $this->get_options();
		printf( '<label><input type="checkbox" name="%s[strip_smileys_html]" value="1"%s> %s</label>', esc_attr( $this->opt_key ), checked( $o['strip_smileys_html'], 1, false ), esc_html__( 'Remover <img class="wp-smiley"> já gravados no HTML do post ao entregar no feed.', 'emanda-featured-image-rss' ) );
	}

	public function field_fallback() {
		$o = $this->get_options();
		printf( '<label><input type="checkbox" name="%s[fallback_first_img]" value="1"%s> %s</label>', esc_attr( $this->opt_key ), checked( $o['fallback_first_img'], 1, false ), esc_html__( 'Se não houver destacada, usar a primeira imagem do conteúdo.', 'emanda-featured-image-rss' ) );
	}

	public function field_default_img() {
		$o = $this->get_options();
		printf( '<input type="url" class="regular-text" name="%s[default_img]" value="%s" placeholder="https://.../fallback.jpg">', esc_attr( $this->opt_key ), esc_attr( $o['default_img'] ) );
		echo '<p class="description">' . esc_html__( 'Usada como último recurso, se não houver destacada nem imagem no conteúdo.', 'emanda-featured-image-rss' ) . '</p>';
	}

	public function sanitize_options( $input ) {
		$d   = $this->defaults();
		$out = array();
		$out['prepend_in_content'] = empty( $input['prepend_in_content'] ) ? 0 : 1;
		$out['add_media_content']  = empty( $input['add_media_content'] ) ? 0 : 1;
		$out['add_enclosure']      = empty( $input['add_enclosure'] ) ? 0 : 1;
		$out['strip_emojis']       = empty( $input['strip_emojis'] ) ? 0 : 1;
		$out['strip_smileys_html'] = empty( $input['strip_smileys_html'] ) ? 0 : 1;
		$out['fallback_first_img'] = empty( $input['fallback_first_img'] ) ? 0 : 1;
		$out['default_img']        = isset( $input['default_img'] ) ? esc_url_raw( $input['default_img'] ) : $d['default_img'];
		$allowed_sizes = $this->get_all_img_sizes();
		$req = isset( $input['image_size'] ) ? $input['image_size'] : '';
		$out['image_size'] = in_array( $req, $allowed_sizes, true ) ? $req : $d['image_size'];
		return $out;
	}

	public function add_settings_page() {
		add_options_page( __( 'RSS – Imagem Destacada', 'emanda-featured-image-rss' ), __( 'RSS – Imagem Destacada', 'emanda-featured-image-rss' ), 'manage_options', $this->opt_key, array( $this, 'render_settings_page' ) );
	}

	public function render_settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) return;
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'RSS – Imagem Destacada', 'emanda-featured-image-rss' ); ?></h1>
			<form method="post" action="options.php">
				<?php
				settings_fields( $this->opt_key );
				do_settings_sections( $this->opt_key );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	private function get_all_img_sizes() {
		$sizes = function_exists( 'get_intermediate_image_sizes' ) ? get_intermediate_image_sizes() : array();
		$sizes[] = 'full';
		return array_values( array_unique( $sizes ) );
	}

	public function maybe_disable_emojis_in_feed() {
		$o = $this->get_options();
		if ( empty( $o['strip_emojis'] ) ) return;
		if ( function_exists( 'is_feed' ) && is_feed() ) {
			remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
			remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
			remove_action( 'wp_print_styles', 'print_emoji_styles' );
			remove_action( 'admin_print_styles', 'print_emoji_styles' );
			remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
			remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
			remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
			add_filter( 'emoji_svg_url', '__return_false' );
		}
	}

	public function filter_content_feed( $content, $feed_type = null ) {
		$o = $this->get_options();
		if ( ! empty( $o['strip_smileys_html'] ) && function_exists( 'is_feed' ) && is_feed() ) {
			$content = preg_replace( '/<img[^>]*class=["\'][^"\']*wp-smiley[^"\']*["\'][^>]*>/i', '', $content );
			$content = preg_replace( '#<img[^>]*src=["\']https?://s\.w\.org/images/core/emoji/[^"\']+["\'][^>]*>#i', '', $content );
		}
		if ( empty( $o['prepend_in_content'] ) ) return $content;
		$post = get_post();
		if ( ! $post ) return $content;
		$img = $this->resolve_image_for_post( $post, $o['image_size'] );
		if ( ! $img || empty( $img['url'] ) ) return $content;
		$alt = get_the_title( $post );
		$html = '<p><img src="' . esc_url( $img['url'] ) . '" alt="' . esc_attr( $alt ) . '" style="margin:0 0 12px 0; height:auto; max-width:100%; display:block;" /></p>';
		return $html . "\n" . $content;
	}

	public function add_media_namespace() {
		$o = $this->get_options();
		if ( ! empty( $o['add_media_content'] ) ) {
			echo 'xmlns:media="http://search.yahoo.com/mrss/"' . "\n";
		}
	}

	public function print_media_and_enclosure() {
		global $post;
		if ( ! $post ) return;
		$o = $this->get_options();
		$img = $this->resolve_image_for_post( $post, 'full' );
		if ( ! $img || empty( $img['url'] ) ) return;
		$mime = ! empty( $img['mime'] ) ? $img['mime'] : 'image/jpeg';
		if ( ! empty( $o['add_enclosure'] ) ) {
			echo '<enclosure url="' . esc_url( $img['url'] ) . '" length="0" type="' . esc_attr( $mime ) . '" />' . "\n";
		}
		if ( ! empty( $o['add_media_content'] ) ) {
			$w = intval( $img['width'] );
			$h = intval( $img['height'] );
			echo '<media:content url="' . esc_url( $img['url'] ) . '" medium="image" width="' . $w . '" height="' . $h . '" />' . "\n";
		}
	}

	private function resolve_image_for_post( $post, $size = 'large' ) {
		if ( ! $post ) return null;
		if ( function_exists( 'has_post_thumbnail' ) && has_post_thumbnail( $post ) ) {
			$id = get_post_thumbnail_id( $post );
			$src = wp_get_attachment_image_src( $id, $size );
			if ( $src && is_array( $src ) ) {
				return array(
					'url'    => $src[0],
					'width'  => isset( $src[1] ) ? intval( $src[1] ) : 0,
					'height' => isset( $src[2] ) ? intval( $src[2] ) : 0,
					'mime'   => get_post_mime_type( $id ),
				);
			}
		}
		$o = $this->get_options();
		if ( ! empty( $o['fallback_first_img'] ) ) {
			$first = $this->extract_first_img_from_content( $post->post_content );
			if ( $first ) {
				$ft = function_exists( 'wp_check_filetype' ) ? wp_check_filetype( $first ) : null;
				return array(
					'url'    => $first,
					'width'  => 0,
					'height' => 0,
					'mime'   => ( $ft && ! empty( $ft['type'] ) ) ? $ft['type'] : 'image/jpeg',
				);
			}
		}
		if ( ! empty( $o['default_img'] ) ) {
			$ft = function_exists( 'wp_check_filetype' ) ? wp_check_filetype( $o['default_img'] ) : null;
			return array(
				'url'    => $o['default_img'],
				'width'  => 0,
				'height' => 0,
				'mime'   => ( $ft && ! empty( $ft['type'] ) ) ? $ft['type'] : 'image/jpeg',
			);
		}
		return null;
	}

	private function extract_first_img_from_content( $content ) {
		if ( empty( $content ) ) return null;
		if ( preg_match( '/<img[^>]+src=["\']([^"\']+)["\']/i', $content, $m ) ) {
			$src = esc_url_raw( $m[1] );
			if ( strpos( $src, 's.w.org/images/core/emoji/' ) !== false ) return null;
			return $src;
		}
		return null;
	}
}

endif;

function emanda_fir_boot() {
	$plugin = new Emanda_FIR_Plugin();
	$plugin->init();
}
emanda_fir_boot();

// Uninstall handled in uninstall.php
