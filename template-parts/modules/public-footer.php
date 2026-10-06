<?php
/**
 * Module: public-footer (web-style-guide.html → Web modules → Site chrome).
 * Four columns from Site Settings; stacks to two, then one, on smaller screens.
 */

defined( 'ABSPATH' ) || exit;

$assemble_columns = (array) assemble_setting( 'footer_columns', array() );
?>
<footer class="site-footer" data-area="neutral">
	<div class="wrap">
		<div class="footer-grid">
			<?php foreach ( $assemble_columns as $assemble_column ) : ?>
				<div>
					<h2 class="eyebrow"><?php echo esc_html( (string) ( $assemble_column['heading'] ?? '' ) ); ?></h2>
					<ul class="footer-links">
						<?php foreach ( (array) ( $assemble_column['links'] ?? array() ) as $assemble_link ) : ?>
							<?php
							$assemble_label = (string) ( $assemble_link[0] ?? '' );
							$assemble_href  = assemble_url( (string) ( $assemble_link[1] ?? '' ) );
							?>
							<li>
								<?php if ( $assemble_href ) : ?>
									<a href="<?php echo esc_url( $assemble_href ); ?>"<?php echo str_starts_with( $assemble_href, home_url() ) ? '' : ' rel="noopener"'; ?>><?php echo esc_html( $assemble_label ); ?></a>
								<?php else : ?>
									<?php echo esc_html( $assemble_label ); ?>
								<?php endif; ?>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endforeach; ?>
		</div>
		<div class="footer-bottom">
			<span>&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?> Assemble</span>
		</div>
	</div>
</footer>
