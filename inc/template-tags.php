<?php
/**
 * Small template helpers.
 */

defined( 'ABSPATH' ) || exit;

/**
 * A Site Setting, via assemble-core. The theme still renders if the plugin is
 * inactive: it then gets $fallback.
 *
 * @param mixed $fallback
 * @return mixed
 */
function assemble_setting( string $key, $fallback = null ) {
	return function_exists( 'assemble_site_setting' ) ? assemble_site_setting( $key, $fallback ) : $fallback;
}

/**
 * Turn a site-relative URL from Site Settings into an absolute one; leave
 * absolute URLs alone.
 */
function assemble_url( string $url ): string {
	if ( '' === $url ) {
		return '';
	}

	return str_starts_with( $url, '/' ) ? home_url( $url ) : $url;
}

/** An icon from the guide's sprite (assets/svg/icons.svg), e.g. assemble_icon( 'search' ). */
function assemble_icon( string $name, string $class = '' ): string {
	return sprintf(
		'<svg class="icon%s" aria-hidden="true" focusable="false"><use href="#icon-%s"></use></svg>',
		$class ? ' ' . esc_attr( $class ) : '',
		esc_attr( $name )
	);
}

/** Print the icon sprite once, at the top of <body>. */
function assemble_icon_sprite(): void {
	$file = get_template_directory() . '/assets/svg/icons.svg';
	if ( is_readable( $file ) ) {
		echo file_get_contents( $file ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped, WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- theme-owned static SVG.
	}
}
add_action( 'wp_body_open', 'assemble_icon_sprite', 1 );

/** The Assemble logo as inline SVG, so it takes the surrounding text colour. */
function assemble_logo(): string {
	$file = get_template_directory() . '/assets/svg/assemble-logo.svg';
	if ( ! is_readable( $file ) ) {
		return esc_html( get_bloginfo( 'name' ) );
	}

	$svg = (string) file_get_contents( $file ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- theme-owned static SVG.

	return (string) preg_replace( '/^<svg /', '<svg class="brand-logo" role="img" aria-label="Assemble" focusable="false" ', $svg, 1 );
}
