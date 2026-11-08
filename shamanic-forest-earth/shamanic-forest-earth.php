<?php
/**
 * Plugin Name: Shamanic Forest & Earth
 * Description: Reversible Forest & Earth design system and homepage for Shamanic Reiki Healing Ireland. Designed for the NativeChurch theme. 3.3: top bar, service strip, dated events, review slider, inline newsletter; 3.3.1: consistent styling on every page, booking banner, shamanic artwork.
 * Version: 3.4.6
 * Author: SmartSite
 * License: GPL-2.0-or-later
 * Text Domain: shamanic-forest-earth
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

define( 'SFE33_VERSION', '3.4.6' );
define( 'SFE33_FORM_VER', 2 );
/* Artwork generated for the site and stored in its Media Library (IDs). */
define( 'SFE33_IMG_HERO', 20986 );
define( 'SFE33_IMG_ABOUT', 20987 );
define( 'SFE33_IMG_EVENTS', '20988,20989,20990' );
define( 'SFE33_URL', plugin_dir_url( __FILE__ ) );
define( 'SFE33_PATH', plugin_dir_path( __FILE__ ) );

/* Settings keys from 3.2 (sfe32_*) are reused so existing choices carry over. New 3.3 keys use sfe33_*. */
function sfe33_defaults() {
    return array(
        'sfe32_enabled'          => 1,
        'sfe32_homepage_enabled' => 1,
        'sfe32_newsletter_url'   => home_url( '/newsletter-signup/' ),
        'sfe33_topbar_enabled'   => 1,
        'sfe33_phone'            => '+353 87 929 7869',
        'sfe33_email'            => 'shamanic.reiki.healing@gmail.com',
        'sfe33_facebook'         => 'https://www.facebook.com/ShamanicreikihealingIreland/',
        'sfe33_instagram'        => '',
        'sfe33_youtube'          => '',
        'sfe33_book_url'         => '',
        'sfe33_events'           => '',
        'sfe33_mailpoet_form'    => '',
        'sfe33_hero_image'       => '',
        'sfe33_about_image'      => '',
        'sfe33_cta_enabled'      => 1,
        'sfe33_footer_enabled'   => 1,
        'sfe33_hide_likes'       => 1,
        'sfe33_drop_theme_fonts' => 1,
        'sfe33_mailpoet_list'    => '',
    );
}

function sfe33_opt( $key ) {
    $d = sfe33_defaults();
    return get_option( $key, isset( $d[ $key ] ) ? $d[ $key ] : '' );
}

register_activation_hook( __FILE__, function() {
    foreach ( sfe33_defaults() as $key => $value ) {
        if ( false === get_option( $key, false ) ) {
            add_option( $key, $value );
        }
    }
});

add_filter( 'body_class', function( $classes ) {
    if ( is_front_page() && ! in_array( 'home', $classes, true ) ) {
        $classes[] = 'home';
    }
    if ( sfe33_opt( 'sfe32_enabled' ) ) {
        $classes[] = 'sfe-skin';
        if ( sfe33_opt( 'sfe33_topbar_enabled' ) ) { $classes[] = 'sfe-has-topbar'; }
        if ( sfe33_opt( 'sfe33_footer_enabled' ) ) { $classes[] = 'sfe-custom-footer'; }
        if ( sfe33_opt( 'sfe33_hide_likes' ) ) { $classes[] = 'sfe-no-likes'; }
    }
    return $classes;
}, 20 );

add_action( 'wp_enqueue_scripts', function() {
    if ( ! sfe33_opt( 'sfe32_enabled' ) ) { return; }
    wp_enqueue_style( 'sfe-fonts', 'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;1,500&family=Lato:wght@400;700&display=swap', array(), null );
    wp_enqueue_style( 'sfe-forest-earth', SFE33_URL . 'assets/css/forest-earth.css', array(), SFE33_VERSION );
    $band = sfe33_image_url( 'sfe33_hero_image', SFE33_IMG_HERO, 'large' );
    if ( $band ) {
        $u = esc_url_raw( $band );
        wp_add_inline_style( 'sfe-forest-earth', 'body.sfe-skin:not(.home) .page-header{background-image:linear-gradient(90deg,rgba(9,29,19,.92),rgba(11,34,23,.72) 60%,rgba(9,29,19,.45)),url("' . $u . '")!important;background-size:cover!important;background-position:center 45%!important}' );
    }
    if ( is_front_page() && sfe33_opt( 'sfe32_homepage_enabled' ) ) {
        wp_enqueue_script( 'sfe-forest-earth', SFE33_URL . 'assets/js/forest-earth.js', array(), SFE33_VERSION, true );
    }
}, 99 );

/* Critical CSS printed early so icons never render oversized while stylesheets load. */
add_action( 'wp_head', function() {
    if ( ! sfe33_opt( 'sfe32_enabled' ) ) { return; }
    echo '<style id="sfe-critical">.sfe-icon{width:1em;height:1em;max-width:48px;max-height:48px;flex:0 0 auto}.sfe-about__tree{max-width:230px;height:auto}.sfe-footer__trees{display:block;width:100%;height:60px}.sfe-topbar{background:#0f2519;color:#eae4d4;font:13px/1.4 sans-serif}.sfe-topbar a{color:#eae4d4;text-decoration:none}body.sfe-custom-footer footer.site-footer,body.sfe-custom-footer .site-footer-bottom{display:none!important}body.sfe-no-likes .sharedaddy.sd-like,body.sfe-no-likes .sd-like,body.sfe-no-likes .jetpack-likes-widget-wrapper,body.sfe-no-likes .wp-block-jetpack-like{display:none!important}</style>' . "\n";
}, 1 );

add_filter( 'template_include', function( $template ) {
    if ( is_admin() || ! is_front_page() ) { return $template; }
    if ( ! sfe33_opt( 'sfe32_enabled' ) || ! sfe33_opt( 'sfe32_homepage_enabled' ) ) { return $template; }
    $custom_template = SFE33_PATH . 'templates/forest-earth-home.php';
    return file_exists( $custom_template ) ? $custom_template : $template;
}, 99 );

add_shortcode( 'shamanic_forest_homepage', 'sfe33_render_homepage' );

function sfe33_page_url( $slug, $fallback = '/' ) {
    $page = get_page_by_path( $slug );
    return $page ? get_permalink( $page ) : home_url( $fallback );
}

function sfe33_book_url() {
    $url = sfe33_opt( 'sfe33_book_url' );
    return $url ? $url : sfe33_page_url( 'contact', '/contact/' );
}

/* Image from settings URL, else the bundled Media Library artwork, else empty. */
function sfe33_image_url( $option_key, $attachment_id, $size = 'large' ) {
    $url = $option_key ? sfe33_opt( $option_key ) : '';
    if ( $url ) { return $url; }
    if ( $attachment_id && 'attachment' === get_post_type( $attachment_id ) ) {
        $src = wp_get_attachment_image_url( $attachment_id, $size );
        if ( $src ) { return $src; }
    }
    return '';
}

/* ---------- Icons (inline SVG, no external requests) ---------- */
function sfe33_icon( $name ) {
    $icons = array(
        'leaf'     => '<path d="M20 4C9 4 4 10 4 20c10 0 16-5 16-16Z"/><path d="M4 20 14 10"/><path d="M9 15h4M11 13v-3"/>',
        'lotus'    => '<path d="M12 20c-4-2-6-5-6-9 3 1 5 3 6 6 1-3 3-5 6-6 0 4-2 7-6 9Z"/><path d="M12 17c-1.5-3-1.5-7 0-11 1.5 4 1.5 8 0 11Z"/><path d="M3 13c1 4 4 7 9 7s8-3 9-7"/>',
        'spiral'   => '<circle cx="8" cy="15" r="4"/><circle cx="16" cy="15" r="4"/><circle cx="12" cy="8" r="4"/><circle cx="8" cy="15" r="1.6"/><circle cx="16" cy="15" r="1.6"/><circle cx="12" cy="8" r="1.6"/>',
        'mountain' => '<path d="M2 19 9 8l4 6 3-4 6 9Z"/><path d="m7.5 10.5 1.5 1.5 1.5-1.5"/><path d="M15 11.5l1 1 1-1"/>',
        'phone'    => '<path d="M5 3h4l2 5-2.5 1.5a11 11 0 0 0 6 6L16 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 5a2 2 0 0 1 2-2Z"/>',
        'mail'     => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/>',
        'pin'      => '<path d="M12 21s-7-6.2-7-12a7 7 0 0 1 14 0c0 5.8-7 12-7 12Z"/><circle cx="12" cy="9" r="2.5"/>',
        'calendar' => '<rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 10h18M8 3v4M16 3v4"/>',
        'facebook' => '<path d="M14 8h3V4h-3a4 4 0 0 0-4 4v3H7v4h3v6h4v-6h3l1-4h-4V8Z"/>',
        'instagram'=> '<rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r=".8"/>',
        'youtube'  => '<rect x="2" y="5" width="20" height="14" rx="4"/><path d="m10 9 5 3-5 3Z"/>',
        'arrow'    => '<path d="M5 12h14M13 6l6 6-6 6"/>',
    );
    if ( ! isset( $icons[ $name ] ) ) { return ''; }
    return '<svg class="sfe-icon sfe-icon--' . esc_attr( $name ) . '" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' . $icons[ $name ] . '</svg>';
}

/* ---------- Top bar (all pages) ---------- */
function sfe33_topbar_html() {
    $phone = trim( (string) sfe33_opt( 'sfe33_phone' ) );
    $email = trim( (string) sfe33_opt( 'sfe33_email' ) );
    $social = array(
        'facebook'  => sfe33_opt( 'sfe33_facebook' ),
        'instagram' => sfe33_opt( 'sfe33_instagram' ),
        'youtube'   => sfe33_opt( 'sfe33_youtube' ),
    );
    ob_start(); ?>
    <div class="sfe-topbar" role="region" aria-label="<?php esc_attr_e( 'Contact information', 'shamanic-forest-earth' ); ?>">
      <div class="sfe-topbar__inner">
        <div class="sfe-topbar__contact">
          <?php if ( $phone ) : ?>
            <a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>"><?php echo sfe33_icon( 'phone' ); // phpcs:ignore ?><span><?php echo esc_html( $phone ); ?></span></a>
          <?php endif; ?>
          <?php if ( $email && is_email( $email ) ) : ?>
            <a href="mailto:<?php echo esc_attr( antispambot( $email ) ); ?>"><?php echo sfe33_icon( 'mail' ); // phpcs:ignore ?><span><?php echo esc_html( antispambot( $email ) ); ?></span></a>
          <?php endif; ?>
        </div>
        <div class="sfe-topbar__right">
          <?php foreach ( $social as $network => $url ) : if ( ! $url ) { continue; } ?>
            <a class="sfe-topbar__social" href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( ucfirst( $network ) ); ?>"><?php echo sfe33_icon( $network ); // phpcs:ignore ?></a>
          <?php endforeach; ?>
          <a class="sfe-topbar__book" href="<?php echo esc_url( sfe33_book_url() ); ?>"><?php esc_html_e( 'Book a Session', 'shamanic-forest-earth' ); ?></a>
        </div>
      </div>
    </div>
    <?php
    return ob_get_clean();
}

$GLOBALS['sfe33_topbar_done'] = false;
add_action( 'wp_body_open', function() {
    if ( ! sfe33_opt( 'sfe32_enabled' ) || ! sfe33_opt( 'sfe33_topbar_enabled' ) ) { return; }
    echo sfe33_topbar_html(); // phpcs:ignore
    $GLOBALS['sfe33_topbar_done'] = true;
}, 5 );

/* Older themes may not call wp_body_open(); move the bar to the top of <body> instead. */
add_action( 'wp_footer', function() {
    if ( $GLOBALS['sfe33_topbar_done'] || ! sfe33_opt( 'sfe32_enabled' ) || ! sfe33_opt( 'sfe33_topbar_enabled' ) ) { return; }
    echo '<template id="sfe-topbar-tpl">' . sfe33_topbar_html() . '</template>'; // phpcs:ignore
    echo '<script>(function(){var t=document.getElementById("sfe-topbar-tpl");if(!t||document.querySelector(".sfe-topbar"))return;document.body.insertBefore(t.content.cloneNode(true),document.body.firstChild);})();</script>';
}, 5 );

/* ---------- Data helpers ---------- */

/* Upcoming events from the settings box. One per line: YYYY-MM-DD | Title | Location | URL | Short description */
function sfe33_upcoming_events( $limit = 3 ) {
    $raw = (string) sfe33_opt( 'sfe33_events' );
    $today = current_time( 'Y-m-d' );
    $events = array();
    foreach ( preg_split( '/\r\n|\r|\n/', $raw ) as $line ) {
        $parts = array_map( 'trim', explode( '|', $line ) );
        if ( count( $parts ) < 2 || ! preg_match( '/^\d{4}-\d{2}-\d{2}$/', $parts[0] ) ) { continue; }
        if ( $parts[0] < $today ) { continue; }
        $events[] = array(
            'date'     => $parts[0],
            'title'    => $parts[1],
            'location' => isset( $parts[2] ) ? $parts[2] : '',
            'url'      => ! empty( $parts[3] ) ? $parts[3] : sfe33_book_url(),
            'text'     => isset( $parts[4] ) ? $parts[4] : '',
        );
    }
    usort( $events, function( $a, $b ) { return strcmp( $a['date'], $b['date'] ); } );
    return array_slice( $events, 0, $limit );
}

/* Real reviews from the WP Customer Reviews plugin (post type wpcr3_review). */
function sfe33_reviews( $limit = 6 ) {
    if ( ! post_type_exists( 'wpcr3_review' ) ) { return array(); }
    $q = new WP_Query( array(
        'post_type'      => 'wpcr3_review',
        'post_status'    => 'publish',
        'posts_per_page' => 20,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'no_found_rows'  => true,
    ) );
    $out = array();
    foreach ( $q->posts as $p ) {
        $rating = (int) get_post_meta( $p->ID, 'wpcr3_review_rating', true );
        if ( $rating && $rating < 4 ) { continue; }
        $text = trim( wp_strip_all_tags( $p->post_content ) );
        if ( '' === $text ) { continue; }
        $name = trim( (string) get_post_meta( $p->ID, 'wpcr3_review_name', true ) );
        $out[] = array(
            'text' => wp_trim_words( $text, 34, '…' ),
            'name' => $name,
        );
        if ( count( $out ) >= $limit ) { break; }
    }
    return $out;
}

/* MailPoet form: the one chosen in settings, otherwise the first enabled form. */
function sfe33_mailpoet_form_id() {
    $id = absint( sfe33_opt( 'sfe33_mailpoet_form' ) );
    if ( $id ) { return $id; }
    if ( sfe33_mailpoet_api() ) { return 0; } /* built-in form + MailPoet API is preferred */
    if ( ! shortcode_exists( 'mailpoet_form' ) ) { return 0; }
    global $wpdb;
    $table = $wpdb->prefix . 'mailpoet_forms';
    if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table ) ) !== $table ) { return 0; }
    $found = $wpdb->get_var( "SELECT id FROM {$table} WHERE deleted_at IS NULL AND (status = 'enabled' OR status IS NULL) ORDER BY id ASC LIMIT 1" ); // phpcs:ignore
    return absint( $found );
}

/* ---------- Homepage ---------- */
function sfe33_render_homepage() {
    if ( ! sfe33_opt( 'sfe32_enabled' ) ) { return ''; }

    $services = array(
        array( 'title' => 'Shamanic Healing', 'tag' => 'Release • Restore • Reconnect', 'url' => sfe33_page_url( 'shamanic-reiki-healing', '/shamanic-reiki-healing/' ), 'icon' => 'leaf' ),
        array( 'title' => 'Reiki Healing & Courses', 'tag' => 'Balance • Heal • Empower', 'url' => sfe33_page_url( 'reiki', '/reiki/' ), 'icon' => 'lotus' ),
        array( 'title' => 'Shamanic Journey', 'tag' => 'Guidance • Insight • Transformation', 'url' => sfe33_page_url( 'shamanic-journey', '/shamanic-journey/' ), 'icon' => 'spiral' ),
        array( 'title' => 'Counselling & Support', 'tag' => 'Clarity • Healing • Personal Growth', 'url' => sfe33_page_url( 'shamanic-counselling-coaching', '/shamanic-counselling-coaching/' ), 'icon' => 'mountain' ),
    );

    $events = sfe33_upcoming_events( 3 );
    $event_query = null;
    if ( ! $events && post_type_exists( 'event' ) ) {
        /* Only future events: NativeChurch stores the start date in imic_event_start_dt. Past events are never shown. */
        $event_query = new WP_Query( array(
            'post_type' => 'event', 'post_status' => 'publish', 'posts_per_page' => 3, 'no_found_rows' => true, 'ignore_sticky_posts' => true,
            'meta_key' => 'imic_event_start_dt', 'orderby' => 'meta_value', 'order' => 'ASC',
            'meta_query' => array( array( 'key' => 'imic_event_start_dt', 'value' => current_time( 'Y-m-d' ), 'compare' => '>=', 'type' => 'DATE' ) ),
        ) );
    }
    $reviews = sfe33_reviews( 6 );
    $blog_query = new WP_Query( array( 'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => 3, 'ignore_sticky_posts' => true, 'no_found_rows' => true ) );
    $form_id = sfe33_mailpoet_form_id();
    $newsletter_url = sfe33_opt( 'sfe32_newsletter_url' );
    if ( ! is_string( $newsletter_url ) || ! wp_http_validate_url( $newsletter_url ) ) {
        $newsletter_url = sfe33_page_url( 'newsletter-signup' );
    }
    $hero_img  = sfe33_image_url( 'sfe33_hero_image', SFE33_IMG_HERO, 'full' );
    $about_img = sfe33_image_url( 'sfe33_about_image', SFE33_IMG_ABOUT, 'medium_large' );
    $event_imgs = array();
    foreach ( array_map( 'absint', explode( ',', SFE33_IMG_EVENTS ) ) as $eid ) {
        $u = sfe33_image_url( '', $eid, '600x400' );
        if ( $u ) { $event_imgs[] = $u; }
    }
    $events_url = sfe33_page_url( 'events', '/events/' );

    ob_start(); ?>
    <main class="sfe-home" aria-label="<?php esc_attr_e( 'Shamanic Reiki homepage', 'shamanic-forest-earth' ); ?>">

      <section class="sfe-hero"<?php if ( $hero_img ) { echo ' style="background-image:url(' . esc_url( $hero_img ) . ')"'; } ?>>
        <div class="sfe-hero__shade"></div>
        <div class="sfe-wrap sfe-hero__inner">
          <h1><?php esc_html_e( 'Reconnect With Your Inner Wisdom', 'shamanic-forest-earth' ); ?></h1>
          <p class="sfe-hero__lead"><?php esc_html_e( 'Shamanic Healing • Reiki • Spiritual Growth', 'shamanic-forest-earth' ); ?><br><?php esc_html_e( 'Personal Transformation', 'shamanic-forest-earth' ); ?></p>
          <div class="sfe-divider" aria-hidden="true"><span></span><?php echo sfe33_icon( 'lotus' ); // phpcs:ignore ?><span></span></div>
          <div class="sfe-actions">
            <a class="sfe-button sfe-button--gold" href="<?php echo esc_url( sfe33_book_url() ); ?>"><?php esc_html_e( 'Book a Healing Session', 'shamanic-forest-earth' ); ?> <?php echo sfe33_icon( 'arrow' ); // phpcs:ignore ?></a>
            <a class="sfe-button sfe-button--outline" href="<?php echo esc_url( $events_url ); ?>"><?php esc_html_e( 'Explore Our Events', 'shamanic-forest-earth' ); ?></a>
          </div>
        </div>
      </section>

      <section class="sfe-strip" aria-label="<?php esc_attr_e( 'Healing services', 'shamanic-forest-earth' ); ?>">
        <div class="sfe-wrap sfe-strip__grid">
          <?php foreach ( $services as $s ) : ?>
            <a class="sfe-strip__item" href="<?php echo esc_url( $s['url'] ); ?>">
              <?php echo sfe33_icon( $s['icon'] ); // phpcs:ignore ?>
              <strong><?php echo esc_html( $s['title'] ); ?></strong>
              <span><?php echo esc_html( $s['tag'] ); ?></span>
            </a>
          <?php endforeach; ?>
        </div>
      </section>

      <section class="sfe-section sfe-events">
        <div class="sfe-wrap">
          <div class="sfe-heading">
            <h2 class="sfe-ornate"><?php esc_html_e( 'Upcoming Events', 'shamanic-forest-earth' ); ?></h2>
          </div>
          <?php if ( $events ) : ?>
            <div class="sfe-event-grid">
              <?php foreach ( $events as $ei => $e ) : $ts = strtotime( $e['date'] ); $eimg = $event_imgs ? $event_imgs[ $ei % count( $event_imgs ) ] : ''; ?>
                <article class="sfe-event-card">
                  <div class="sfe-event-card__top<?php echo $eimg ? ' has-image' : ''; ?>"<?php if ( $eimg ) { echo ' style="background-image:url(' . esc_url( $eimg ) . ')"'; } ?>>
                    <span class="sfe-date"><small><?php echo esc_html( strtoupper( date_i18n( 'D', $ts ) ) ); ?></small><b><?php echo esc_html( date_i18n( 'j', $ts ) ); ?></b><small><?php echo esc_html( strtoupper( date_i18n( 'M', $ts ) ) ); ?></small></span>
                    <?php echo sfe33_icon( 'calendar' ); // phpcs:ignore ?>
                  </div>
                  <div class="sfe-event-card__body">
                    <h3><?php echo esc_html( $e['title'] ); ?></h3>
                    <?php if ( $e['location'] ) : ?><p class="sfe-event-card__loc"><?php echo sfe33_icon( 'pin' ); // phpcs:ignore ?><?php echo esc_html( $e['location'] ); ?></p><?php endif; ?>
                    <?php if ( $e['text'] ) : ?><p><?php echo esc_html( $e['text'] ); ?></p><?php endif; ?>
                    <a class="sfe-button sfe-button--olive" href="<?php echo esc_url( $e['url'] ); ?>"><?php esc_html_e( 'View Event & Book', 'shamanic-forest-earth' ); ?> <?php echo sfe33_icon( 'arrow' ); // phpcs:ignore ?></a>
                  </div>
                </article>
              <?php endforeach; ?>
            </div>
          <?php elseif ( $event_query && $event_query->have_posts() ) : ?>
            <div class="sfe-event-grid">
              <?php $ei = 0; while ( $event_query->have_posts() ) : $event_query->the_post();
                $raw = (string) get_post_meta( get_the_ID(), 'imic_event_start_dt', true ); $ts = $raw ? strtotime( $raw ) : 0;
                $eimg = has_post_thumbnail() ? get_the_post_thumbnail_url( null, 'medium_large' ) : ( $event_imgs ? $event_imgs[ $ei % count( $event_imgs ) ] : '' ); $ei++; ?>
                <article class="sfe-event-card">
                  <a class="sfe-event-card__top<?php echo $eimg ? ' has-image' : ''; ?>" href="<?php the_permalink(); ?>"<?php if ( $eimg ) { echo ' style="background-image:url(' . esc_url( $eimg ) . ')"'; } ?>>
                    <?php if ( $ts ) : ?><span class="sfe-date"><small><?php echo esc_html( strtoupper( date_i18n( 'D', $ts ) ) ); ?></small><b><?php echo esc_html( date_i18n( 'j', $ts ) ); ?></b><small><?php echo esc_html( strtoupper( date_i18n( 'M', $ts ) ) ); ?></small></span><?php endif; ?>
                    <?php echo sfe33_icon( 'calendar' ); // phpcs:ignore ?>
                  </a>
                  <div class="sfe-event-card__body">
                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    <p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 20 ) ); ?></p>
                    <a class="sfe-button sfe-button--olive" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Event details', 'shamanic-forest-earth' ); ?> <?php echo sfe33_icon( 'arrow' ); // phpcs:ignore ?></a>
                  </div>
                </article>
              <?php endwhile; wp_reset_postdata(); ?>
            </div>
          <?php else : ?>
            <div class="sfe-events__empty">
              <p><?php esc_html_e( 'New workshops and gatherings are announced regularly. Get in touch or join the newsletter to hear about the next dates.', 'shamanic-forest-earth' ); ?></p>
            </div>
          <?php endif; ?>
          <p class="sfe-events__more"><a class="sfe-button sfe-button--gold" href="<?php echo esc_url( $events_url ); ?>"><?php esc_html_e( 'View All Events', 'shamanic-forest-earth' ); ?> <?php echo sfe33_icon( 'arrow' ); // phpcs:ignore ?></a></p>
        </div>
      </section>

      <section class="sfe-about">
        <div class="sfe-about__grid">
          <div class="sfe-about__image"<?php if ( $about_img ) { echo ' style="background-image:url(' . esc_url( $about_img ) . ')"'; } ?> role="img" aria-label="<?php esc_attr_e( 'Sunlight in a peaceful forest', 'shamanic-forest-earth' ); ?>"></div>
          <div class="sfe-about__copy">
            <p class="sfe-eyebrow sfe-eyebrow--gold"><?php esc_html_e( 'ABOUT SHAMANIC REIKI HEALING', 'shamanic-forest-earth' ); ?></p>
            <h2><?php esc_html_e( 'A Path of Healing, Balance and Transformation', 'shamanic-forest-earth' ); ?></h2>
            <p><?php esc_html_e( 'I offer Shamanic Healing, Reiki, Shamanic Journey and counselling to support your physical, emotional, mental and spiritual well-being. My work is rooted in nature, ancient wisdom and the belief in your own innate healing power.', 'shamanic-forest-earth' ); ?></p>
            <a class="sfe-button sfe-button--dark" href="<?php echo esc_url( sfe33_page_url( 'about-us', '/about-us/' ) ); ?>"><?php esc_html_e( 'Learn More About My Work', 'shamanic-forest-earth' ); ?> <?php echo sfe33_icon( 'arrow' ); // phpcs:ignore ?></a>
          </div>
          <svg class="sfe-about__tree" width="200" height="200" viewBox="0 0 120 120" aria-hidden="true" focusable="false"><g fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"><circle cx="60" cy="60" r="56"/><circle cx="60" cy="60" r="51"/><path d="M60 104V62M60 78c-8-6-18-6-26-2M60 72c9-7 20-8 28-3M60 66c-6-10-16-14-26-12M60 66c7-11 17-15 27-12M60 62c-2-12-9-20-18-24M60 62c3-12 10-20 19-23M60 58V30M48 104c4-4 8-6 12-6s8 2 12 6M42 104c6-7 12-10 18-10s12 3 18 10"/><circle cx="60" cy="48" r="28" stroke-dasharray="2 5"/></g></svg>
        </div>
      </section>

      <?php if ( $reviews ) : ?>
      <section class="sfe-quotes" aria-roledescription="carousel" aria-label="<?php esc_attr_e( 'Client feedback', 'shamanic-forest-earth' ); ?>">
        <div class="sfe-wrap sfe-quotes__inner">
          <button class="sfe-quotes__nav sfe-quotes__prev" type="button" aria-label="<?php esc_attr_e( 'Previous', 'shamanic-forest-earth' ); ?>">&#8249;</button>
          <div class="sfe-quotes__track">
            <?php foreach ( $reviews as $i => $r ) : ?>
              <figure class="sfe-quote<?php echo 0 === $i ? ' is-active' : ''; ?>" <?php echo 0 === $i ? '' : 'hidden'; ?>>
                <blockquote>&ldquo;<?php echo esc_html( $r['text'] ); ?>&rdquo;</blockquote>
                <?php if ( $r['name'] ) : ?><figcaption>&mdash; <?php echo esc_html( $r['name'] ); ?></figcaption><?php endif; ?>
              </figure>
            <?php endforeach; ?>
          </div>
          <button class="sfe-quotes__nav sfe-quotes__next" type="button" aria-label="<?php esc_attr_e( 'Next', 'shamanic-forest-earth' ); ?>">&#8250;</button>
          <div class="sfe-quotes__dots" role="tablist">
            <?php foreach ( $reviews as $i => $r ) : ?>
              <button type="button" class="<?php echo 0 === $i ? 'is-active' : ''; ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Review %d', 'shamanic-forest-earth' ), $i + 1 ) ); ?>"></button>
            <?php endforeach; ?>
          </div>
          <a class="sfe-quotes__all" href="<?php echo esc_url( sfe33_page_url( 'clients-feedback', '/clients-feedback/' ) ); ?>"><?php esc_html_e( 'Read all reviews', 'shamanic-forest-earth' ); ?></a>
        </div>
      </section>
      <?php endif; ?>

      <section class="sfe-section sfe-blog">
        <div class="sfe-wrap sfe-blog__layout">
          <div class="sfe-blog__main">
            <h2 class="sfe-ornate"><?php esc_html_e( 'Latest from the Blog', 'shamanic-forest-earth' ); ?></h2>
            <?php if ( $blog_query->have_posts() ) : ?>
            <div class="sfe-blog-grid">
              <?php while ( $blog_query->have_posts() ) : $blog_query->the_post(); ?>
                <article class="sfe-blog-card">
                  <a class="sfe-blog-card__image" href="<?php the_permalink(); ?>"><?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'medium_large', array( 'loading' => 'lazy' ) ); } ?></a>
                  <div class="sfe-blog-card__body">
                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    <p class="sfe-blog-card__date"><?php echo sfe33_icon( 'calendar' ); // phpcs:ignore ?><?php echo esc_html( get_the_date() ); ?></p>
                    <p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 18 ) ); ?></p>
                    <a class="sfe-text-link" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read More', 'shamanic-forest-earth' ); ?> <?php echo sfe33_icon( 'arrow' ); // phpcs:ignore ?></a>
                  </div>
                </article>
              <?php endwhile; ?>
            </div>
            <?php endif; wp_reset_postdata(); ?>
          </div>
          <aside class="sfe-newsbox">
            <?php echo sfe33_icon( 'mail' ); // phpcs:ignore ?>
            <h3><?php esc_html_e( 'Join Our Newsletter', 'shamanic-forest-earth' ); ?></h3>
            <p><?php esc_html_e( 'Receive event updates, healing tips and inspiration straight to your inbox.', 'shamanic-forest-earth' ); ?></p>
            <?php if ( $form_id || sfe33_mailpoet_api() ) : ?>
              <div class="sfe-newsbox__form"><?php echo sfe33_newsletter_form_html( $form_id, true ); // phpcs:ignore ?></div>
            <?php else : ?>
              <a class="sfe-button sfe-button--gold" href="<?php echo esc_url( $newsletter_url ); ?>"><?php esc_html_e( 'Subscribe', 'shamanic-forest-earth' ); ?></a>
            <?php endif; ?>
          </aside>
        </div>
      </section>
    </main>
    <?php
    return ob_get_clean();
}

/* ---------- Settings ---------- */
add_action( 'admin_menu', function() {
    add_options_page( 'Shamanic Forest & Earth', 'Forest & Earth', 'manage_options', 'shamanic-forest-earth', 'sfe33_settings_page' );
});

add_action( 'admin_init', function() {
    $bool = array( 'type' => 'boolean', 'sanitize_callback' => 'rest_sanitize_boolean' );
    $url  = array( 'type' => 'string', 'sanitize_callback' => 'esc_url_raw' );
    $text = array( 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field' );
    register_setting( 'sfe_settings', 'sfe32_enabled', $bool );
    register_setting( 'sfe_settings', 'sfe32_homepage_enabled', $bool );
    register_setting( 'sfe_settings', 'sfe33_topbar_enabled', $bool );
    register_setting( 'sfe_settings', 'sfe33_cta_enabled', $bool );
    register_setting( 'sfe_settings', 'sfe33_footer_enabled', $bool );
    register_setting( 'sfe_settings', 'sfe33_hide_likes', $bool );
    register_setting( 'sfe_settings', 'sfe33_drop_theme_fonts', $bool );
    register_setting( 'sfe_settings', 'sfe32_newsletter_url', $url );
    register_setting( 'sfe_settings', 'sfe33_phone', $text );
    register_setting( 'sfe_settings', 'sfe33_email', array( 'type' => 'string', 'sanitize_callback' => 'sanitize_email' ) );
    register_setting( 'sfe_settings', 'sfe33_facebook', $url );
    register_setting( 'sfe_settings', 'sfe33_instagram', $url );
    register_setting( 'sfe_settings', 'sfe33_youtube', $url );
    register_setting( 'sfe_settings', 'sfe33_book_url', $url );
    register_setting( 'sfe_settings', 'sfe33_hero_image', $url );
    register_setting( 'sfe_settings', 'sfe33_about_image', $url );
    register_setting( 'sfe_settings', 'sfe33_mailpoet_form', array( 'type' => 'integer', 'sanitize_callback' => 'absint' ) );
    register_setting( 'sfe_settings', 'sfe33_mailpoet_list', array( 'type' => 'integer', 'sanitize_callback' => 'absint' ) );
    register_setting( 'sfe_settings', 'sfe33_events', array( 'type' => 'string', 'sanitize_callback' => 'sanitize_textarea_field' ) );
});

function sfe33_settings_page() {
    if ( ! current_user_can( 'manage_options' ) ) { return; }
    $field = function( $key, $label, $type = 'text', $help = '' ) {
        printf( '<tr><th scope="row"><label for="%1$s">%2$s</label></th><td><input type="%3$s" id="%1$s" name="%1$s" value="%4$s" class="regular-text">%5$s</td></tr>',
            esc_attr( $key ), esc_html( $label ), esc_attr( $type ), esc_attr( sfe33_opt( $key ) ),
            $help ? '<p class="description">' . esc_html( $help ) . '</p>' : '' );
    };
    $check = function( $key, $label ) {
        printf( '<p><label><input type="hidden" name="%1$s" value="0"><input type="checkbox" name="%1$s" value="1" %2$s> %3$s</label></p>',
            esc_attr( $key ), checked( (bool) sfe33_opt( $key ), true, false ), esc_html( $label ) );
    };
    ?>
    <div class="wrap">
      <h1>Shamanic Forest &amp; Earth <?php echo esc_html( SFE33_VERSION ); ?></h1>
      <p>Forest &amp; Earth visual layer for the NativeChurch theme. Pages, menus and theme files are not edited. Deactivate the plugin to roll back.</p>
      <form method="post" action="options.php">
        <?php settings_fields( 'sfe_settings' ); ?>
        <h2>Switches</h2>
        <?php $check( 'sfe32_enabled', 'Enable Forest & Earth styling' ); ?>
        <?php $check( 'sfe32_homepage_enabled', 'Show the Forest & Earth homepage (untick to restore the original NativeChurch homepage)' ); ?>
        <?php $check( 'sfe33_topbar_enabled', 'Show the top contact bar on every page' ); ?>
        <?php $check( 'sfe33_footer_enabled', 'Use the Forest & Earth footer (untick to restore the theme footer and its widgets)' ); ?>
        <?php $check( 'sfe33_hide_likes', 'Hide the Jetpack “Like this” buttons' ); ?>
        <?php $check( 'sfe33_drop_theme_fonts', 'Speed: do not load the theme’s old Google Fonts (Roboto, Volkhov, Anaheim…) – Forest & Earth uses its own' ); ?>
        <?php $check( 'sfe33_cta_enabled', 'Show the “Book a Healing Session” banner at the end of pages and posts' ); ?>

        <h2>Contact &amp; social</h2>
        <table class="form-table" role="presentation">
          <?php $field( 'sfe33_phone', 'Phone' ); ?>
          <?php $field( 'sfe33_email', 'Email', 'email' ); ?>
          <?php $field( 'sfe33_facebook', 'Facebook URL', 'url', 'Leave empty to hide the icon.' ); ?>
          <?php $field( 'sfe33_instagram', 'Instagram URL', 'url', 'Leave empty to hide the icon.' ); ?>
          <?php $field( 'sfe33_youtube', 'YouTube URL', 'url', 'Leave empty to hide the icon.' ); ?>
          <?php $field( 'sfe33_book_url', '“Book a Session” link', 'url', 'Empty = the Contact page.' ); ?>
        </table>

        <h2>Upcoming events</h2>
        <p>One event per line: <code>YYYY-MM-DD | Title | Location | Link (optional) | Short description</code><br>Past dates disappear automatically. If the box is empty, published Event posts are shown without dates.</p>
        <textarea name="sfe33_events" rows="6" class="large-text code" placeholder="2026-12-06 | Shamanic Journey Evening | Ballincollig, Cork | | An evening of drumming and inner journeying."><?php echo esc_textarea( sfe33_opt( 'sfe33_events' ) ); ?></textarea>

        <h2>Newsletter</h2>
        <table class="form-table" role="presentation">
          <?php $field( 'sfe33_mailpoet_form', 'MailPoet form ID (optional)', 'number', 'Empty = the built-in Forest & Earth signup form is used (recommended). Enter a MailPoet form ID only to use that form instead.' ); ?>
          <?php $field( 'sfe33_mailpoet_list', 'MailPoet list ID for signups', 'number', 'Empty = the first standard MailPoet list (e.g. “Newsletter mailing list”).' ); ?>
          <?php $field( 'sfe32_newsletter_url', 'Newsletter page (fallback)', 'url', 'Used for the Subscribe button when no MailPoet form is available.' ); ?>
        </table>

        <h2>Images</h2>
        <table class="form-table" role="presentation">
          <?php $field( 'sfe33_hero_image', 'Hero background image URL', 'url', 'Empty = the Forest & Earth artwork (Media Library). Use a Media Library URL, ideally 2000px wide.' ); ?>
          <?php $field( 'sfe33_about_image', '“About” image URL', 'url', 'Empty = the Forest & Earth artwork. A real photo of you works best here.' ); ?>
        </table>
        <?php submit_button( 'Save Forest & Earth settings' ); ?>
      </form>
      <p><strong>Shortcode:</strong> <code>[shamanic_forest_homepage]</code> · Reviews are read from WP Customer Reviews (4–5 stars).</p>
    </div>
    <?php
}

/* ---------- Booking banner at the end of pages and posts (3.3.1) ---------- */
add_filter( 'the_content', function( $content ) {
    if ( is_admin() || is_feed() || ! is_singular( array( 'page', 'post' ) ) || is_front_page() ) { return $content; }
    if ( ! in_the_loop() || ! is_main_query() ) { return $content; }
    if ( ! sfe33_opt( 'sfe32_enabled' ) || ! sfe33_opt( 'sfe33_cta_enabled' ) ) { return $content; }
    $slug = (string) get_post_field( 'post_name', get_the_ID() );
    $skip = array( 'contact', 'newsletter-signup', 'privacy-policy', 'complaints-and-returns', 'delivery-and-shipping-information', 'links', 'blog', 'clients-feedback' );
    if ( in_array( $slug, $skip, true ) || 0 === strpos( $slug, 'unsubscribe' ) ) { return $content; }
    ob_start(); ?>
    <aside class="sfe-cta" aria-label="<?php esc_attr_e( 'Book a session', 'shamanic-forest-earth' ); ?>">
      <div>
        <h3><?php esc_html_e( 'Ready to begin your healing journey?', 'shamanic-forest-earth' ); ?></h3>
        <p><?php esc_html_e( 'Sessions in Ballincollig, Cork and online. Get in touch to ask a question or book a time.', 'shamanic-forest-earth' ); ?></p>
      </div>
      <div class="sfe-cta__actions">
        <a class="sfe-cta__btn" href="<?php echo esc_url( sfe33_book_url() ); ?>"><?php esc_html_e( 'Book a Healing Session', 'shamanic-forest-earth' ); ?> <?php echo sfe33_icon( 'arrow' ); // phpcs:ignore ?></a>
        <?php $tel = preg_replace( '/[^0-9+]/', '', (string) sfe33_opt( 'sfe33_phone' ) ); if ( $tel ) : ?>
          <a class="sfe-cta__btn sfe-cta__btn--ghost" href="tel:<?php echo esc_attr( $tel ); ?>"><?php echo sfe33_icon( 'phone' ); // phpcs:ignore ?> <?php esc_html_e( 'Call', 'shamanic-forest-earth' ); ?></a>
        <?php endif; ?>
      </div>
    </aside>
    <?php
    return $content . ob_get_clean();
}, 20 );

/* ---------- Forest & Earth footer (3.3.2) ----------
 * Replaces the NativeChurch footer on the front end. The theme's footer widgets are not rendered
 * (so their external scripts no longer load) but stay saved under Appearance > Widgets.
 * Untick "Use the Forest & Earth footer" in settings to restore the original footer. */
add_filter( 'sidebars_widgets', function( $sidebars ) {
    if ( is_admin() || ! sfe33_opt( 'sfe32_enabled' ) || ! sfe33_opt( 'sfe33_footer_enabled' ) ) { return $sidebars; }
    if ( isset( $sidebars['footer-sidebar'] ) ) { $sidebars['footer-sidebar'] = array(); }
    return $sidebars;
} );

function sfe33_footer_html() {
    $phone = trim( (string) sfe33_opt( 'sfe33_phone' ) );
    $email = trim( (string) sfe33_opt( 'sfe33_email' ) );
    $sessions = array(
        'Shamanic Reiki Healing' => 'shamanic-reiki-healing',
        'Soul Retrieval'         => 'soul-retrieval',
        'Shamanic Journey'       => 'shamanic-journey',
        'Reiki'                  => 'reiki',
        'Tuning Forks Healing'   => 'tuning-forks-healing',
        'Distance Healing'       => 'distance-healing-online',
        'Vedic Astrology'        => 'discover-your-cosmic-blueprint-through-vedic-astrology',
    );
    $explore = array(
        'About'            => 'about-us',
        'Courses'          => 'courses',
        'Events'           => 'events',
        'Blog'             => 'blog',
        'Clients Feedback' => 'clients-feedback',
        'Contact'          => 'contact',
    );
    $social = array( 'facebook' => sfe33_opt( 'sfe33_facebook' ), 'instagram' => sfe33_opt( 'sfe33_instagram' ), 'youtube' => sfe33_opt( 'sfe33_youtube' ) );
    ob_start(); ?>
    <footer class="sfe-footer" role="contentinfo">
      <svg class="sfe-footer__trees" viewBox="0 0 1440 90" preserveAspectRatio="none" width="1440" height="90" aria-hidden="true" focusable="false"><path fill="currentColor" d="M0 90 L0 70 L6 72 L10 59 L8 59 L13 45 L12 46 L16 52 L20 46 L19 45 L24 59 L22 59 L26 72 L37 74 L39 64 L38 64 L41 53 L40 54 L44 60 L47 54 L46 53 L49 64 L48 64 L50 74 L60 66 L63 44 L62 46 L65 23 L64 24 L68 28 L71 24 L70 23 L74 46 L72 44 L75 66 L88 66 L92 50 L90 51 L96 34 L94 35 L99 44 L104 35 L102 34 L108 51 L106 50 L110 66 L123 72 L126 48 L124 49 L128 23 L127 24 L130 20 L133 24 L132 23 L136 49 L134 48 L137 72 L147 67 L150 51 L148 52 L152 35 L151 36 L154 44 L157 36 L156 35 L160 52 L158 51 L161 67 L174 66 L178 42 L176 43 L182 17 L180 18 L186 20 L191 18 L189 17 L195 43 L193 42 L197 66 L213 69 L218 58 L215 59 L221 48 L219 49 L225 60 L231 49 L229 48 L235 59 L232 58 L237 69 L253 72 L258 64 L255 65 L261 57 L259 57 L265 68 L271 57 L269 57 L275 65 L272 64 L277 72 L290 66 L292 50 L291 51 L294 34 L293 35 L296 44 L300 35 L299 34 L302 51 L301 50 L303 66 L316 70 L320 57 L318 57 L324 43 L322 44 L328 52 L333 44 L331 43 L337 57 L335 57 L339 70 L354 74 L358 61 L356 61 L361 47 L360 48 L364 52 L369 48 L368 47 L373 61 L371 61 L375 74 L387 74 L390 55 L388 56 L392 36 L391 37 L394 36 L398 37 L397 36 L400 56 L399 55 L402 74 L412 69 L416 58 L414 59 L418 48 L417 49 L420 60 L424 49 L423 48 L427 59 L425 58 L429 69 L441 74 L445 64 L443 64 L448 53 L446 54 L450 60 L455 54 L453 53 L458 64 L456 64 L460 74 L472 69 L475 61 L473 62 L477 54 L476 54 L479 68 L482 54 L481 54 L485 62 L483 61 L486 69 L498 71 L503 46 L501 48 L506 22 L504 23 L510 20 L515 23 L513 22 L518 48 L516 46 L521 71 L535 71 L540 43 L537 45 L543 15 L541 17 L546 10 L551 17 L549 15 L555 45 L552 43 L557 71 L570 68 L574 52 L572 53 L577 36 L575 37 L580 44 L584 37 L582 36 L587 53 L585 52 L589 68 L601 70 L604 60 L603 60 L607 49 L605 50 L610 60 L614 50 L612 49 L616 60 L615 60 L618 70 L632 71 L636 43 L634 45 L640 15 L638 17 L643 10 L648 17 L646 15 L652 45 L650 43 L654 71 L669 67 L674 48 L671 49 L677 29 L675 30 L680 36 L685 30 L683 29 L689 49 L686 48 L691 67 L703 68 L706 44 L704 45 L708 19 L707 20 L710 20 L714 20 L713 19 L716 45 L715 44 L718 68 L729 73 L733 60 L731 60 L736 46 L735 47 L739 52 L743 47 L742 46 L747 60 L745 60 L749 73 L762 67 L766 59 L764 60 L769 52 L768 52 L772 68 L777 52 L776 52 L781 60 L779 59 L783 67 L798 71 L802 49 L800 51 L806 28 L804 29 L810 28 L815 29 L813 28 L819 51 L817 49 L821 71 L836 73 L840 45 L838 47 L843 17 L841 19 L846 10 L850 19 L848 17 L853 47 L851 45 L855 73 L867 70 L870 60 L868 60 L872 49 L871 50 L874 60 L877 50 L876 49 L880 60 L878 60 L881 70 L893 66 L898 56 L896 56 L901 45 L899 46 L904 60 L910 46 L908 45 L913 56 L911 56 L916 66 L929 70 L933 42 L931 44 L936 14 L934 16 L938 10 L943 16 L941 14 L946 44 L944 42 L948 70 L961 66 L965 44 L963 46 L968 23 L966 24 L971 28 L976 24 L974 23 L979 46 L977 44 L981 66 L995 68 L1000 46 L997 48 L1003 25 L1001 26 L1006 28 L1011 26 L1009 25 L1015 48 L1012 46 L1017 68 L1029 66 L1032 38 L1030 40 L1034 10 L1033 12 L1036 10 L1040 12 L1039 10 L1042 40 L1041 38 L1044 66 L1055 68 L1058 49 L1056 50 L1060 30 L1059 31 L1063 36 L1067 31 L1066 30 L1070 50 L1068 49 L1071 68 L1083 72 L1086 48 L1085 49 L1089 23 L1087 24 L1092 20 L1096 24 L1094 23 L1098 49 L1097 48 L1100 72 L1113 68 L1118 58 L1116 58 L1121 47 L1119 48 L1124 60 L1130 48 L1128 47 L1133 58 L1131 58 L1136 68 L1150 74 L1155 50 L1152 51 L1158 25 L1156 26 L1161 20 L1166 26 L1164 25 L1170 51 L1167 50 L1172 74 L1185 72 L1189 59 L1187 59 L1191 45 L1190 46 L1194 52 L1198 46 L1197 45 L1201 59 L1199 59 L1203 72 L1217 72 L1221 53 L1219 54 L1225 34 L1223 35 L1228 36 L1234 35 L1232 34 L1238 54 L1236 53 L1240 72 L1255 69 L1259 44 L1257 46 L1262 20 L1260 21 L1264 20 L1269 21 L1267 20 L1272 46 L1270 44 L1274 69 L1286 68 L1289 58 L1288 58 L1292 47 L1290 48 L1294 60 L1298 48 L1296 47 L1300 58 L1299 58 L1302 68 L1312 69 L1315 53 L1314 54 L1318 37 L1316 38 L1320 44 L1324 38 L1322 37 L1326 54 L1325 53 L1328 69 L1337 68 L1340 40 L1339 42 L1342 12 L1341 14 L1344 10 L1347 14 L1346 12 L1349 42 L1348 40 L1351 68 L1361 66 L1365 47 L1363 48 L1367 28 L1366 29 L1370 36 L1374 29 L1373 28 L1377 48 L1375 47 L1379 66 L1390 74 L1393 50 L1392 51 L1396 25 L1394 26 L1398 20 L1402 26 L1400 25 L1404 51 L1403 50 L1406 74 L1418 68 L1422 46 L1420 48 L1425 25 L1423 26 L1428 28 L1432 26 L1430 25 L1435 48 L1433 46 L1437 68 L1440 70 L1440 90Z"/></svg>
      <div class="sfe-footer__main">
        <div class="sfe-footer__brand">
          <svg class="sfe-footer__emblem" width="64" height="64" viewBox="0 0 120 120" aria-hidden="true" focusable="false"><g fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="60" cy="60" r="56"/><path d="M60 104V62M60 78c-8-6-18-6-26-2M60 72c9-7 20-8 28-3M60 66c-6-10-16-14-26-12M60 66c7-11 17-15 27-12M60 62c-2-12-9-20-18-24M60 62c3-12 10-20 19-23M60 58V30M48 104c4-4 8-6 12-6s8 2 12 6M42 104c6-7 12-10 18-10s12 3 18 10"/><circle cx="60" cy="48" r="28" stroke-dasharray="2 5"/></g></svg>
          <p class="sfe-footer__name"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></p>
          <p class="sfe-footer__about"><?php esc_html_e( 'Shamanic healing, Reiki and spiritual guidance rooted in nature and ancient wisdom. Sessions in Ballincollig, Cork and online.', 'shamanic-forest-earth' ); ?></p>
          <p class="sfe-footer__training"><?php esc_html_e( 'Trained with', 'shamanic-forest-earth' ); ?> <a href="https://www.youtube.com/watch?v=wR8xndQmEzQ" target="_blank" rel="noopener">Shamanism Ireland <?php echo sfe33_icon( 'youtube' ); // phpcs:ignore ?></a></p>
          <div class="sfe-footer__social">
            <?php foreach ( $social as $network => $url ) : if ( ! $url ) { continue; } ?>
              <a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( ucfirst( $network ) ); ?>"><?php echo sfe33_icon( $network ); // phpcs:ignore ?></a>
            <?php endforeach; ?>
            <?php if ( $email && is_email( $email ) ) : ?><a href="mailto:<?php echo esc_attr( antispambot( $email ) ); ?>" aria-label="Email"><?php echo sfe33_icon( 'mail' ); // phpcs:ignore ?></a><?php endif; ?>
          </div>
        </div>
        <nav class="sfe-footer__col" aria-label="<?php esc_attr_e( 'Healing sessions', 'shamanic-forest-earth' ); ?>">
          <p class="sfe-footer__title"><?php esc_html_e( 'Healing Sessions', 'shamanic-forest-earth' ); ?></p>
          <ul><?php foreach ( $sessions as $label => $slug ) : if ( ! get_page_by_path( $slug ) ) { continue; } ?><li><a href="<?php echo esc_url( sfe33_page_url( $slug ) ); ?>"><?php echo esc_html( $label ); ?></a></li><?php endforeach; ?></ul>
        </nav>
        <nav class="sfe-footer__col" aria-label="<?php esc_attr_e( 'Explore', 'shamanic-forest-earth' ); ?>">
          <p class="sfe-footer__title"><?php esc_html_e( 'Explore', 'shamanic-forest-earth' ); ?></p>
          <ul><?php foreach ( $explore as $label => $slug ) : if ( ! get_page_by_path( $slug ) ) { continue; } ?><li><a href="<?php echo esc_url( sfe33_page_url( $slug ) ); ?>"><?php echo esc_html( $label ); ?></a></li><?php endforeach; ?></ul>
        </nav>
        <div class="sfe-footer__col sfe-footer__contact">
          <p class="sfe-footer__title"><?php esc_html_e( 'Get in Touch', 'shamanic-forest-earth' ); ?></p>
          <ul>
            <?php if ( $phone ) : ?><li><a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>"><?php echo sfe33_icon( 'phone' ); // phpcs:ignore ?><?php echo esc_html( $phone ); ?></a></li><?php endif; ?>
            <?php if ( $email && is_email( $email ) ) : ?><li><a href="mailto:<?php echo esc_attr( antispambot( $email ) ); ?>"><?php echo sfe33_icon( 'mail' ); // phpcs:ignore ?><?php echo esc_html( antispambot( $email ) ); ?></a></li><?php endif; ?>
            <li><a href="https://www.google.com/maps/place/Shamanic+Reiki+Healing+Ireland/@51.888877,-8.588303,17z" target="_blank" rel="noopener"><?php echo sfe33_icon( 'pin' ); // phpcs:ignore ?><?php esc_html_e( 'Ballincollig, Co. Cork', 'shamanic-forest-earth' ); ?></a></li>
          </ul>
          <a class="sfe-footer__book" href="<?php echo esc_url( sfe33_book_url() ); ?>"><?php esc_html_e( 'Book a Healing Session', 'shamanic-forest-earth' ); ?> <?php echo sfe33_icon( 'arrow' ); // phpcs:ignore ?></a>
        </div>
      </div>
      <div class="sfe-footer__bottom">
        <p>&copy; <?php echo esc_html( current_time( 'Y' ) ); ?> <?php echo esc_html( get_bloginfo( 'name' ) ); ?>. <?php esc_html_e( 'All rights reserved.', 'shamanic-forest-earth' ); ?></p>
        <p>
          <?php if ( get_page_by_path( 'privacy-policy' ) ) : ?><a href="<?php echo esc_url( sfe33_page_url( 'privacy-policy' ) ); ?>"><?php esc_html_e( 'Privacy Policy', 'shamanic-forest-earth' ); ?></a> · <?php endif; ?>
          <a href="#top" class="sfe-footer__top" onclick="window.scrollTo({top:0,behavior:'smooth'});return false;"><?php esc_html_e( 'Back to top', 'shamanic-forest-earth' ); ?> &uarr;</a>
        </p>
      </div>
    </footer>
    <?php
    return ob_get_clean();
}

add_action( 'wp_footer', function() {
    if ( ! sfe33_opt( 'sfe32_enabled' ) || ! sfe33_opt( 'sfe33_footer_enabled' ) ) { return; }
    echo sfe33_footer_html(); // phpcs:ignore
}, 1 );

/* ---------- Booking form (3.3.4) ----------
 * Creates one Contact Form 7 form ("Forest & Earth – Booking Request") the first time it is needed,
 * then renders it with [sfe_booking_form]. The form can be edited afterwards under Contact > Contact Forms.
 */
function sfe33_booking_form_markup() {
    $sessions = array( 'Shamanic Reiki Healing', 'Soul Retrieval', 'Shamanic Journey', 'Reiki Healing', 'Reiki Course / Attunement', 'Tuning Forks Healing', 'Distance Healing', 'Shamanic Counselling / Coaching', 'Vedic Astrology Reading', 'Event or Workshop', 'Other / General question' );
    $opts = '"' . implode( '" "', $sessions ) . '"';
    $privacy = get_page_by_path( 'privacy-policy' ) ? ' (<a href="' . esc_url( sfe33_page_url( 'privacy-policy' ) ) . '" target="_blank">Privacy Policy</a>)' : '';
    return '<div class="sfe-form-grid">'
        . '<label class="sfe-field"><span class="sfe-label">Your name <em>*</em></span>[text* your-name autocomplete:name placeholder "Full name"]</label>'
        . '<label class="sfe-field"><span class="sfe-label">Email <em>*</em></span>[email* your-email autocomplete:email placeholder "you@example.com"]</label>'
        . '<label class="sfe-field"><span class="sfe-label">Phone</span>[tel your-phone autocomplete:tel placeholder "+353 …"]</label>'
        . '<label class="sfe-field"><span class="sfe-label">I am interested in</span>[select session-type include_blank ' . $opts . ']</label>'
        . '</div>'
        . '<div class="sfe-field sfe-field--full"><span class="sfe-label">Session format</span>[radio session-format use_label_element default:1 "In person" "Online / distance" "Not sure yet"]</div>'
        . '<label class="sfe-field sfe-field--full"><span class="sfe-label">Preferred days or times</span>[text preferred-time placeholder "e.g. weekday evenings or Saturday morning"]</label>'
        . '<label class="sfe-field sfe-field--full"><span class="sfe-label">Your message <em>*</em></span>[textarea* your-message x5 placeholder "Tell me a little about what brings you here or what you would like to ask."]</label>'
        . '<div class="sfe-consent">[acceptance consent] I agree that my details will be used to reply to this enquiry' . $privacy . '. [/acceptance]</div>'
        . '<div class="sfe-submit-row">[submit class:sfe-submit "Send Booking Request"]</div>';
}

function sfe33_booking_form_id() {
    if ( ! class_exists( 'WPCF7_ContactForm' ) ) { return 0; }
    $id = absint( get_option( 'sfe33_cf7_form_id' ) );
    if ( $id && 'wpcf7_contact_form' === get_post_type( $id ) && 'publish' === get_post_status( $id ) ) {
        /* One-time upgrade of the form fields created by an earlier plugin version. */
        if ( (int) get_option( 'sfe33_cf7_form_ver', 1 ) < SFE33_FORM_VER && is_admin() ) {
            $existing = WPCF7_ContactForm::get_instance( $id );
            if ( $existing ) {
                $existing->set_properties( array( 'form' => sfe33_booking_form_markup() ) );
                $existing->save();
            }
            update_option( 'sfe33_cf7_form_ver', SFE33_FORM_VER, false );
        }
        return $id;
    }
    if ( ! function_exists( 'wpcf7_contact_form' ) ) { return 0; }
    $form = WPCF7_ContactForm::get_template( array( 'title' => 'Forest & Earth – Booking Request', 'locale' => get_locale() ) );
    if ( ! $form ) { return 0; }
    $recipient = sfe33_opt( 'sfe33_email' );
    if ( ! $recipient || ! is_email( $recipient ) ) { $recipient = get_option( 'admin_email' ); }
    $host = preg_replace( '/^www\./', '', (string) wp_parse_url( home_url(), PHP_URL_HOST ) );
    $sender = '[_site_title] <wordpress@' . $host . '>';
    $props = $form->get_properties();
    $props['form'] = sfe33_booking_form_markup();
    $props['mail'] = array(
        'active' => true,
        'subject' => 'Booking request: [session-type] – [your-name]',
        'sender' => $sender,
        'recipient' => $recipient,
        'body' => "New booking request from the website\n\nName: [your-name]\nEmail: [your-email]\nPhone: [your-phone]\nInterested in: [session-type]\nFormat: [session-format]\nPreferred days/times: [preferred-time]\n\nMessage:\n[your-message]\n\n--\nSent from [_url]",
        'additional_headers' => 'Reply-To: [your-name] <[your-email]>',
        'attachments' => '', 'use_html' => false, 'exclude_blank' => true,
    );
    $props['mail_2'] = array(
        'active' => true,
        'subject' => 'Thank you for your message – [_site_title]',
        'sender' => $sender,
        'recipient' => '[your-email]',
        'body' => "Dear [your-name],\n\nThank you for getting in touch. Your request has been received and I will reply personally as soon as possible, usually within one or two days.\n\nWhat you sent:\nInterested in: [session-type]\nFormat: [session-format]\nPreferred days/times: [preferred-time]\n\n[your-message]\n\nWarm wishes,\n[_site_title]\n" . sfe33_opt( 'sfe33_phone' ) . "\n[_site_url]",
        'additional_headers' => 'Reply-To: ' . $recipient,
        'attachments' => '', 'use_html' => false, 'exclude_blank' => true,
    );
    $props['messages'] = array_merge( (array) $props['messages'], array(
        'mail_sent_ok' => 'Thank you! Your request has been sent. You will receive a confirmation email shortly.',
        'validation_error' => 'Please check the highlighted fields and try again.',
        'accept_terms' => 'Please tick the box to agree that your details can be used to reply.',
    ) );
    $form->set_properties( $props );
    $new_id = $form->save();
    if ( $new_id ) { update_option( 'sfe33_cf7_form_id', (int) $new_id, false ); update_option( 'sfe33_cf7_form_ver', SFE33_FORM_VER, false ); }
    return (int) $new_id;
}

add_action( 'admin_init', function() {
    if ( sfe33_opt( 'sfe32_enabled' ) && current_user_can( 'manage_options' ) ) { sfe33_booking_form_id(); }
} );

add_shortcode( 'sfe_booking_form', function() {
    $id = sfe33_booking_form_id();
    $phone = trim( (string) sfe33_opt( 'sfe33_phone' ) );
    $email = trim( (string) sfe33_opt( 'sfe33_email' ) );
    ob_start(); ?>
    <section class="sfe-booking" aria-labelledby="sfe-booking-title">
      <div class="sfe-booking__intro">
        <p class="sfe-booking__eyebrow"><?php esc_html_e( 'BOOK A SESSION', 'shamanic-forest-earth' ); ?></p>
        <h2 id="sfe-booking-title"><?php esc_html_e( 'Begin your healing journey', 'shamanic-forest-earth' ); ?></h2>
        <ol class="sfe-booking__steps">
          <li><span>1</span><?php esc_html_e( 'Send your request with the form', 'shamanic-forest-earth' ); ?></li>
          <li><span>2</span><?php esc_html_e( 'I reply personally to arrange a time', 'shamanic-forest-earth' ); ?></li>
          <li><span>3</span><?php esc_html_e( 'Meet in person or online', 'shamanic-forest-earth' ); ?></li>
        </ol>
        <div class="sfe-booking__direct">
          <?php if ( $phone ) : ?><a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>"><?php echo sfe33_icon( 'phone' ); // phpcs:ignore ?><?php echo esc_html( $phone ); ?></a><?php endif; ?>
          <?php if ( $email && is_email( $email ) ) : ?><a href="mailto:<?php echo esc_attr( antispambot( $email ) ); ?>"><?php echo sfe33_icon( 'mail' ); // phpcs:ignore ?><?php echo esc_html( antispambot( $email ) ); ?></a><?php endif; ?>
        </div>
      </div>
      <div class="sfe-booking__form">
        <?php
        if ( $id ) {
            /* Our markup is already structured: stop CF7 adding <p>/<br> around fields for this form only. */
            add_filter( 'wpcf7_autop_or_not', '__return_false', 99 );
            echo do_shortcode( '[contact-form-7 id="' . $id . '" html_class="sfe-cf7"]' ); // phpcs:ignore
            remove_filter( 'wpcf7_autop_or_not', '__return_false', 99 );
        } else {
            echo '<p>' . esc_html__( 'The booking form is temporarily unavailable. Please call or email using the details here.', 'shamanic-forest-earth' ) . '</p>';
        }
        ?>
      </div>
    </section>
    <?php
    return ob_get_clean();
} );

/* ---------- NativeChurch "Custom JS" guard (3.3.5) ----------
 * The theme option holds CSS rules instead of JavaScript, which throws a syntax error on every page.
 * On the front end only, drop the field when it contains CSS. The saved theme option is not changed.
 */
add_filter( 'option_imic_options', function( $opts ) {
    if ( is_admin() || ! is_array( $opts ) || empty( $opts['custom_js'] ) || ! sfe33_opt( 'sfe32_enabled' ) ) { return $opts; }
    $js = (string) $opts['custom_js'];
    if ( preg_match( '/(^|[\s;}])(body|html|img|\.[a-z][\w-]*|#[a-z][\w-]*)\s*\{|background-image\s*:|margin\s*:\s*0 auto/i', $js ) ) {
        $opts['custom_js'] = '';
    }
    return $opts;
}, 20 );

/* ---------- Jetpack Likes off (3.3.7) ---------- */
add_filter( 'wpl_is_likes_visible', function( $visible ) {
    if ( sfe33_opt( 'sfe32_enabled' ) && sfe33_opt( 'sfe33_hide_likes' ) && ! is_admin() ) { return false; }
    return $visible;
}, 99 );

/* ---------- Speed (3.4.0) ---------- */

/* Drop every Google Fonts stylesheet except our own (theme/Redux typography is overridden by Forest & Earth). */
function sfe33_drop_theme_fonts() {
    if ( is_admin() || ! sfe33_opt( 'sfe32_enabled' ) || ! sfe33_opt( 'sfe33_drop_theme_fonts' ) ) { return; }
    global $wp_styles;
    if ( ! ( $wp_styles instanceof WP_Styles ) ) { return; }
    foreach ( (array) $wp_styles->queue as $handle ) {
        if ( 'sfe-fonts' === $handle || empty( $wp_styles->registered[ $handle ] ) ) { continue; }
        $src = (string) $wp_styles->registered[ $handle ]->src;
        if ( false !== strpos( $src, 'fonts.googleapis.com' ) ) {
            wp_dequeue_style( $handle );
        }
    }
}
add_action( 'wp_enqueue_scripts', 'sfe33_drop_theme_fonts', 999 );
add_action( 'wp_print_styles', 'sfe33_drop_theme_fonts', 1 );

/* Connect to the font servers early. */
add_filter( 'wp_resource_hints', function( $urls, $relation ) {
    if ( 'preconnect' !== $relation || is_admin() || ! sfe33_opt( 'sfe32_enabled' ) ) { return $urls; }
    $urls[] = 'https://fonts.googleapis.com';
    $urls[] = array( 'href' => 'https://fonts.gstatic.com', 'crossorigin' );
    return $urls;
}, 10, 2 );

/* ---------- Newsletter page (3.4.2): [sfe_newsletter_form] ---------- */
add_shortcode( 'sfe_newsletter_form', function() {
    $form_id = sfe33_mailpoet_form_id();
    $img = sfe33_image_url( '', SFE33_IMG_ABOUT, 'medium_large' );
    ob_start(); ?>
    <section class="sfe-signup" aria-labelledby="sfe-signup-title">
      <div class="sfe-signup__art"<?php if ( $img ) { echo ' style="background-image:url(' . esc_url( $img ) . ')"'; } ?> role="img" aria-label="<?php esc_attr_e( 'Shamanic drum and feathers in a forest clearing', 'shamanic-forest-earth' ); ?>"></div>
      <div class="sfe-signup__body">
        <p class="sfe-signup__eyebrow"><?php esc_html_e( 'STAY CONNECTED', 'shamanic-forest-earth' ); ?></p>
        <h2 id="sfe-signup-title"><?php esc_html_e( 'Join the newsletter', 'shamanic-forest-earth' ); ?></h2>
        <p class="sfe-signup__lead"><?php esc_html_e( 'Receive event updates, healing tips and inspiration straight to your inbox.', 'shamanic-forest-earth' ); ?></p>
        <ul class="sfe-signup__list">
          <li><?php echo sfe33_icon( 'calendar' ); // phpcs:ignore ?><?php esc_html_e( 'New workshops, journeys and courses first', 'shamanic-forest-earth' ); ?></li>
          <li><?php echo sfe33_icon( 'leaf' ); // phpcs:ignore ?><?php esc_html_e( 'Healing tips and reflections', 'shamanic-forest-earth' ); ?></li>
          <li><?php echo sfe33_icon( 'mail' ); // phpcs:ignore ?><?php esc_html_e( 'No spam – unsubscribe any time', 'shamanic-forest-earth' ); ?></li>
        </ul>
        <div class="sfe-signup__form">
          <?php
          echo sfe33_newsletter_form_html( $form_id ); // phpcs:ignore
          ?>
        </div>
      </div>
    </section>
    <?php
    return ob_get_clean();
} );

/* ---------- Built-in newsletter signup via the MailPoet API (3.4.3) ---------- */
function sfe33_mailpoet_api() {
    if ( ! class_exists( '\MailPoet\API\API' ) ) { return null; }
    try { return \MailPoet\API\API::MP( 'v1' ); } catch ( \Throwable $e ) { return null; }
}

function sfe33_mailpoet_list_id() {
    $chosen = absint( sfe33_opt( 'sfe33_mailpoet_list' ) );
    if ( $chosen ) { return $chosen; }
    $api = sfe33_mailpoet_api();
    if ( ! $api ) { return 0; }
    try {
        foreach ( (array) $api->getLists() as $list ) {
            $type = isset( $list['type'] ) ? $list['type'] : 'default';
            if ( 'default' === $type && empty( $list['deleted_at'] ) ) { return absint( $list['id'] ); }
        }
    } catch ( \Throwable $e ) {}
    return 0;
}

function sfe33_newsletter_form_html( $form_id = 0, $compact = false ) {
    if ( $form_id ) { return do_shortcode( '[mailpoet_form id="' . absint( $form_id ) . '"]' ); }
    $status = isset( $_GET['sfe_nl'] ) ? sanitize_key( wp_unslash( $_GET['sfe_nl'] ) ) : ''; // phpcs:ignore
    $messages = array(
        'ok'      => __( 'Thank you! Please check your inbox to confirm your subscription.', 'shamanic-forest-earth' ),
        'exists'  => __( 'You are already on the list – thank you!', 'shamanic-forest-earth' ),
        'invalid' => __( 'Please enter a valid email address and tick the consent box.', 'shamanic-forest-earth' ),
        'error'   => __( 'Sorry, something went wrong. Please try again or contact me directly.', 'shamanic-forest-earth' ),
    );
    if ( ! sfe33_mailpoet_api() ) {
        return '<p class="sfe-nl__msg">' . esc_html__( 'Newsletter signup is not available right now. Please get in touch to be added to the list.', 'shamanic-forest-earth' ) . ' <a href="' . esc_url( sfe33_page_url( 'contact', '/contact/' ) ) . '">' . esc_html__( 'Contact', 'shamanic-forest-earth' ) . '</a></p>';
    }
    $privacy = get_page_by_path( 'privacy-policy' ) ? ' (<a href="' . esc_url( sfe33_page_url( 'privacy-policy' ) ) . '" target="_blank">' . esc_html__( 'Privacy Policy', 'shamanic-forest-earth' ) . '</a>)' : '';
    $uid = wp_unique_id( 'sfe-nl-' );
    ob_start(); ?>
    <form class="sfe-nl<?php echo $compact ? ' sfe-nl--compact' : ''; ?>" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
      <?php if ( $status && isset( $messages[ $status ] ) ) : ?>
        <p class="sfe-nl__msg sfe-nl__msg--<?php echo esc_attr( $status ); ?>" role="status"><?php echo esc_html( $messages[ $status ] ); ?></p>
      <?php endif; ?>
      <input type="hidden" name="action" value="sfe_subscribe">
      <input type="hidden" name="sfe_back" value="<?php echo esc_attr( esc_url_raw( home_url( add_query_arg( array(), wp_unslash( $_SERVER['REQUEST_URI'] ?? '/' ) ) ) ) ); // phpcs:ignore ?>">
      <?php wp_nonce_field( 'sfe_subscribe', 'sfe_nl_nonce' ); ?>
      <div class="sfe-nl__hp" aria-hidden="true"><label>Leave empty <input type="text" name="sfe_website" tabindex="-1" autocomplete="off"></label></div>
      <?php if ( ! $compact ) : ?>
        <label class="sfe-nl__field" for="<?php echo esc_attr( $uid ); ?>-name"><span><?php esc_html_e( 'First name', 'shamanic-forest-earth' ); ?></span>
          <input id="<?php echo esc_attr( $uid ); ?>-name" type="text" name="sfe_name" autocomplete="given-name" placeholder="<?php esc_attr_e( 'Your first name', 'shamanic-forest-earth' ); ?>"></label>
      <?php endif; ?>
      <label class="sfe-nl__field" for="<?php echo esc_attr( $uid ); ?>-email"><span><?php esc_html_e( 'Email', 'shamanic-forest-earth' ); ?> *</span>
        <input id="<?php echo esc_attr( $uid ); ?>-email" type="email" name="sfe_email" required autocomplete="email" placeholder="you@example.com"></label>
      <label class="sfe-nl__consent"><input type="checkbox" name="sfe_consent" value="1" required> <span><?php esc_html_e( 'I would like to receive the newsletter and can unsubscribe at any time', 'shamanic-forest-earth' ); ?><?php echo $privacy; // phpcs:ignore ?>.</span></label>
      <button type="submit" class="sfe-nl__submit"><?php esc_html_e( 'Subscribe', 'shamanic-forest-earth' ); ?></button>
    </form>
    <?php
    return ob_get_clean();
}

function sfe33_handle_subscribe() {
    $back = isset( $_POST['sfe_back'] ) ? esc_url_raw( wp_unslash( $_POST['sfe_back'] ) ) : home_url( '/' );
    $back = wp_validate_redirect( remove_query_arg( 'sfe_nl', $back ), home_url( '/' ) );
    $go = function( $code ) use ( $back ) { wp_safe_redirect( add_query_arg( 'sfe_nl', $code, $back ) . '#sfe-signup-title' ); exit; };
    if ( ! isset( $_POST['sfe_nl_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['sfe_nl_nonce'] ) ), 'sfe_subscribe' ) ) { $go( 'error' ); }
    if ( ! empty( $_POST['sfe_website'] ) ) { $go( 'ok' ); } /* honeypot: pretend success for bots */
    $email = isset( $_POST['sfe_email'] ) ? sanitize_email( wp_unslash( $_POST['sfe_email'] ) ) : '';
    $name  = isset( $_POST['sfe_name'] ) ? sanitize_text_field( wp_unslash( $_POST['sfe_name'] ) ) : '';
    if ( ! is_email( $email ) || empty( $_POST['sfe_consent'] ) ) { $go( 'invalid' ); }
    $api = sfe33_mailpoet_api();
    $list = sfe33_mailpoet_list_id();
    if ( ! $api || ! $list ) { $go( 'error' ); }
    $result = 'error';
    try {
        $data = array( 'email' => $email );
        if ( $name ) { $data['first_name'] = $name; }
        $api->addSubscriber( $data, array( $list ), array( 'send_confirmation_email' => true ) );
        $result = 'ok';
    } catch ( \Throwable $e ) {
        /* Already a subscriber: make sure they are on the list. */
        try {
            $sub = $api->getSubscriber( $email );
            if ( $sub && ! empty( $sub['id'] ) ) {
                $api->subscribeToList( $sub['id'], $list, array( 'send_confirmation_email' => true ) );
                $result = 'exists';
            }
        } catch ( \Throwable $e2 ) {
            if ( false !== stripos( $e2->getMessage(), 'already' ) ) { $result = 'exists'; }
        }
        if ( 'error' === $result && false !== stripos( $e->getMessage(), 'exist' ) ) { $result = 'exists'; }
    }
    $go( $result );
}
add_action( 'admin_post_sfe_subscribe', 'sfe33_handle_subscribe' );
add_action( 'admin_post_nopriv_sfe_subscribe', 'sfe33_handle_subscribe' );

/* ---------- Mobile menu: tap a parent item to open its submenu (3.4.5) ---------- */
add_action( 'wp_footer', function() {
    if ( ! sfe33_opt( 'sfe32_enabled' ) ) { return; }
    ?>
<script id="sfe-mobile-menu">
(function(){
  var mq = window.matchMedia('(max-width: 991px)');
  function parentLi(el){
    var a = el.closest ? el.closest('a') : null;
    if (!a) return null;
    var li = a.parentElement;
    if (!li || li.tagName !== 'LI') return null;
    if (!li.closest('.sf-menu, .navigation, .main-menu-wrapper, #navigation')) return null;
    var sub = null;
    for (var i = 0; i < li.children.length; i++) { if (li.children[i].tagName === 'UL') { sub = li.children[i]; break; } }
    return sub ? li : null;
  }
  document.addEventListener('click', function(e){
    if (!mq.matches) return;
    var li = parentLi(e.target);
    if (!li) return;
    var href = (li.querySelector('a') || {}).getAttribute ? li.querySelector('a').getAttribute('href') : '#';
    var isOpen = li.classList.contains('sfe-open');
    /* First tap opens; a second tap on a real link follows it, on "#" it closes. */
    if (!isOpen || !href || href === '#' || href.charAt(0) === '#') {
      e.preventDefault();
      e.stopImmediatePropagation();
      if (!isOpen) {
        var sibs = li.parentElement ? li.parentElement.children : [];
        for (var j = 0; j < sibs.length; j++) { if (sibs[j] !== li) sibs[j].classList.remove('sfe-open'); }
      }
      li.classList.toggle('sfe-open', !isOpen);
      var a = li.querySelector('a'); if (a) a.setAttribute('aria-expanded', !isOpen ? 'true' : 'false');
    }
  }, true);
})();
</script>
    <?php
}, 50 );

/* ---------- Events list page (3.4.6): [sfe_events] ---------- */
function sfe34_meta_first( $id, $keys ) {
    foreach ( $keys as $k ) {
        $v = get_post_meta( $id, $k, true );
        if ( is_array( $v ) ) { $v = reset( $v ); }
        if ( '' !== trim( (string) $v ) ) { return trim( (string) $v ); }
    }
    return '';
}

/* Collect events with their date, time and place (NativeChurch keys first, common fallbacks after). */
function sfe34_collect_events() {
    if ( ! post_type_exists( 'event' ) ) { return array(); }
    $q = new WP_Query( array( 'post_type' => 'event', 'post_status' => 'publish', 'posts_per_page' => 200, 'no_found_rows' => true, 'orderby' => 'date', 'order' => 'DESC' ) );
    $out = array();
    foreach ( $q->posts as $p ) {
        $date = sfe34_meta_first( $p->ID, array( 'imic_event_start_dt', 'event_start_date', '_EventStartDate', 'start_date' ) );
        $time = sfe34_meta_first( $p->ID, array( 'imic_event_start_tm', 'event_start_time', 'start_time' ) );
        $ts = $date ? strtotime( $date . ( $time && false === strpos( $date, ':' ) ? ' ' . $time : '' ) ) : false;
        $out[] = array(
            'post'    => $p,
            'ts'      => $ts ? $ts : 0,
            'has_time'=> (bool) $time || ( $date && false !== strpos( $date, ':' ) ),
            'place'   => sfe34_meta_first( $p->ID, array( 'imic_event_address', 'imic_event_venue', 'event_address', '_event_address', 'event_location' ) ),
        );
    }
    return $out;
}

function sfe34_event_card( $e ) {
    $p = $e['post']; $ts = $e['ts'];
    $img = has_post_thumbnail( $p ) ? get_the_post_thumbnail_url( $p, 'medium_large' ) : '';
    if ( ! $img ) {
        $ids = array_map( 'absint', explode( ',', SFE33_IMG_EVENTS ) );
        $img = sfe33_image_url( '', $ids[ $p->ID % count( $ids ) ], '600x400' );
    }
    $link = get_permalink( $p );
    $excerpt = wp_trim_words( wp_strip_all_tags( has_excerpt( $p ) ? $p->post_excerpt : strip_shortcodes( $p->post_content ) ), 24, '…' );
    ob_start(); ?>
    <article class="sfe-ev">
      <?php if ( $ts ) : ?>
        <div class="sfe-ev__date" aria-label="<?php echo esc_attr( date_i18n( 'l j F Y', $ts ) ); ?>"><small><?php echo esc_html( strtoupper( date_i18n( 'D', $ts ) ) ); ?></small><b><?php echo esc_html( date_i18n( 'j', $ts ) ); ?></b><small><?php echo esc_html( strtoupper( date_i18n( 'M', $ts ) ) ); ?></small></div>
      <?php endif; ?>
      <a class="sfe-ev__img" href="<?php echo esc_url( $link ); ?>"<?php if ( $img ) { echo ' style="background-image:url(' . esc_url( $img ) . ')"'; } ?> aria-hidden="true" tabindex="-1"></a>
      <div class="sfe-ev__body">
        <h3 class="sfe-ev__title"><a href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( get_the_title( $p ) ); ?></a></h3>
        <p class="sfe-ev__meta">
          <?php if ( $ts ) : ?><span><?php echo sfe33_icon( 'calendar' ); // phpcs:ignore ?><?php echo esc_html( date_i18n( 'l, j F Y', $ts ) . ( $e['has_time'] ? ' · ' . date_i18n( get_option( 'time_format', 'g:i a' ), $ts ) : '' ) ); ?></span><?php endif; ?>
          <?php if ( $e['place'] ) : ?><span><?php echo sfe33_icon( 'pin' ); // phpcs:ignore ?><?php echo esc_html( $e['place'] ); ?></span><?php endif; ?>
        </p>
        <?php if ( $excerpt ) : ?><p class="sfe-ev__text"><?php echo esc_html( $excerpt ); ?></p><?php endif; ?>
        <div class="sfe-ev__actions">
          <a class="sfe-ev__btn" href="<?php echo esc_url( $link ); ?>"><?php esc_html_e( 'Details', 'shamanic-forest-earth' ); ?> <?php echo sfe33_icon( 'arrow' ); // phpcs:ignore ?></a>
          <a class="sfe-ev__btn sfe-ev__btn--ghost" href="<?php echo esc_url( sfe33_book_url() ); ?>"><?php esc_html_e( 'Book a place', 'shamanic-forest-earth' ); ?></a>
        </div>
      </div>
    </article>
    <?php
    return ob_get_clean();
}

add_shortcode( 'sfe_events', function() {
    $all = sfe34_collect_events();
    $now = current_time( 'timestamp' ) - DAY_IN_SECONDS / 2;
    $upcoming = array(); $past = array(); $undated = array();
    foreach ( $all as $e ) {
        if ( ! $e['ts'] ) { $undated[] = $e; }
        elseif ( $e['ts'] >= $now ) { $upcoming[] = $e; }
        else { $past[] = $e; }
    }
    usort( $upcoming, function( $a, $b ) { return $a['ts'] - $b['ts']; } );
    usort( $past, function( $a, $b ) { return $b['ts'] - $a['ts']; } );
    $past = array_slice( $past, 0, 12 );
    ob_start(); ?>
    <section class="sfe-events-page">
      <div class="sfe-events-page__intro">
        <p class="sfe-events-page__eyebrow"><?php esc_html_e( 'GATHERINGS, CIRCLES & WORKSHOPS', 'shamanic-forest-earth' ); ?></p>
        <h2><?php esc_html_e( 'Upcoming events', 'shamanic-forest-earth' ); ?></h2>
        <p><?php esc_html_e( 'Shamanic journeys, sound healing evenings, Reiki training and ceremonies. Places are limited – book early to secure yours.', 'shamanic-forest-earth' ); ?></p>
      </div>
      <?php if ( $upcoming ) :
        $month = '';
        foreach ( $upcoming as $e ) {
            $m = date_i18n( 'F Y', $e['ts'] );
            if ( $m !== $month ) { $month = $m; echo '<h3 class="sfe-events-page__month"><span>' . esc_html( $m ) . '</span></h3>'; }
            echo sfe34_event_card( $e ); // phpcs:ignore
        }
      elseif ( $undated && ! $past ) :
        foreach ( array_slice( $undated, 0, 6 ) as $e ) { echo sfe34_event_card( $e ); } // phpcs:ignore
      else : ?>
        <div class="sfe-events-page__empty">
          <p><?php esc_html_e( 'New dates are being planned. Join the newsletter or get in touch to hear about the next events first.', 'shamanic-forest-earth' ); ?></p>
          <p><a class="sfe-ev__btn" href="<?php echo esc_url( sfe33_page_url( 'newsletter-signup' ) ); ?>"><?php esc_html_e( 'Join the newsletter', 'shamanic-forest-earth' ); ?></a> <a class="sfe-ev__btn sfe-ev__btn--ghost" href="<?php echo esc_url( sfe33_book_url() ); ?>"><?php esc_html_e( 'Contact', 'shamanic-forest-earth' ); ?></a></p>
        </div>
      <?php endif; ?>
      <?php if ( $past ) : ?>
        <details class="sfe-events-page__past">
          <summary><?php esc_html_e( 'Past events', 'shamanic-forest-earth' ); ?></summary>
          <ul>
            <?php foreach ( $past as $e ) : ?>
              <li><span><?php echo esc_html( date_i18n( 'j M Y', $e['ts'] ) ); ?></span><a href="<?php echo esc_url( get_permalink( $e['post'] ) ); ?>"><?php echo esc_html( get_the_title( $e['post'] ) ); ?></a></li>
            <?php endforeach; ?>
          </ul>
        </details>
      <?php endif; ?>
    </section>
    <?php
    return ob_get_clean();
} );
