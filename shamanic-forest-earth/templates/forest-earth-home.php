<?php
/**
 * Forest & Earth 3.3 front-page template.
 * Keeps the active NativeChurch header and footer, replacing only the homepage body.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
echo sfe33_render_homepage(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
get_footer();
