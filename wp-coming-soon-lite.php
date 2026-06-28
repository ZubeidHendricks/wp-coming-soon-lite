<?php
/**
 * Plugin Name:       Coming Soon & Maintenance Mode Lite
 * Plugin URI:        https://zubeidhendricks.dev/wp-plugins/coming-soon-lite
 * Description:        Show a clean, branded "coming soon" or "maintenance" page to visitors while you build — admins still see the site.
 * Version:           1.0.0
 * Requires at least: 5.8
 * Requires PHP:      7.2
 * Author:            Zubeid Hendricks
 * Author URI:        https://zubeidhendricks.dev
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       coming-soon-lite
 *
 * @package ComingSoonLite
 */

defined( 'ABSPATH' ) || exit;

define( 'COMING_SOON_LITE_VERSION', '1.0.0' );

require_once __DIR__ . '/includes/factory-core.php';

/**
 * Coming Soon & Maintenance Mode Lite.
 */
final class ComingSoonLite extends ZubFactory_Plugin {

	protected function configure() {
		$this->slug    = 'coming-soon-lite';
		$this->title   = 'Coming Soon';
		$this->version = COMING_SOON_LITE_VERSION;
	}

	protected function settings_fields() {
		return array(
			'enabled'  => array(
				'label'    => __( 'Status', 'coming-soon-lite' ),
				'type'     => 'checkbox',
				'cb_label' => __( 'Enable coming-soon / maintenance mode', 'coming-soon-lite' ),
				'default'  => 0,
			),
			'mode'     => array(
				'label'   => __( 'Mode', 'coming-soon-lite' ),
				'type'    => 'select',
				'options' => array(
					'coming'      => __( 'Coming Soon (HTTP 200)', 'coming-soon-lite' ),
					'maintenance' => __( 'Maintenance (HTTP 503)', 'coming-soon-lite' ),
				),
				'default' => 'coming',
			),
			'headline' => array(
				'label'   => __( 'Headline', 'coming-soon-lite' ),
				'type'    => 'text',
				'default' => 'We’ll be right back.',
			),
			'message'  => array(
				'label'   => __( 'Message', 'coming-soon-lite' ),
				'type'    => 'textarea',
				'default' => 'Our site is getting a fresh coat of paint. Check back soon.',
			),
			'bg'       => array(
				'label'   => __( 'Background colour', 'coming-soon-lite' ),
				'type'    => 'color',
				'default' => '#0f172a',
			),
			'logo'     => array(
				'label' => __( 'Logo URL', 'coming-soon-lite' ),
				'type'  => 'text',
				'desc'  => __( 'Show your logo above the headline.', 'coming-soon-lite' ),
				'pro'   => true,
			),
		);
	}

	protected function hooks() {
		add_action( 'template_redirect', array( $this, 'maybe_show' ) );
		add_action( 'admin_bar_menu', array( $this, 'admin_bar' ), 100 );
	}

	/** Should the splash show for this request? */
	private function is_active() {
		return (bool) $this->option( 'enabled', 0 );
	}

	private function can_bypass() {
		return is_user_logged_in() && current_user_can( 'edit_themes' );
	}

	public function maybe_show() {
		if ( ! $this->is_active() || $this->can_bypass() || is_admin() ) {
			return;
		}
		// Never block the login page.
		if ( isset( $GLOBALS['pagenow'] ) && 'wp-login.php' === $GLOBALS['pagenow'] ) {
			return;
		}

		if ( 'maintenance' === $this->option( 'mode', 'coming' ) ) {
			status_header( 503 );
			header( 'Retry-After: 3600' );
		}
		nocache_headers();

		$this->render_splash();
		exit;
	}

	private function render_splash() {
		$headline = $this->option( 'headline', 'We’ll be right back.' );
		$message  = $this->option( 'message', '' );
		$bg       = $this->option( 'bg', '#0f172a' ) ?: '#0f172a';
		$logo     = ZubFactory_Upsell::is_pro( $this->slug ) ? $this->option( 'logo', '' ) : '';
		?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo esc_html( get_bloginfo( 'name' ) . ' — ' . $headline ); ?></title>
	<style>
		html,body{height:100%;margin:0}
		body{display:flex;align-items:center;justify-content:center;
			background:<?php echo esc_attr( $bg ); ?>;color:#fff;
			font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial,sans-serif}
		.box{max-width:640px;padding:48px 24px;text-align:center}
		.box img{max-width:160px;margin:0 0 32px}
		.box h1{font-size:clamp(28px,6vw,48px);margin:0 0 16px;font-weight:700}
		.box p{font-size:18px;line-height:1.6;opacity:.8;margin:0}
	</style>
</head>
<body>
	<div class="box">
		<?php if ( $logo ) : ?>
			<img src="<?php echo esc_url( $logo ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
		<?php endif; ?>
		<h1><?php echo esc_html( $headline ); ?></h1>
		<?php if ( $message ) : ?>
			<p><?php echo nl2br( esc_html( $message ) ); ?></p>
		<?php endif; ?>
	</div>
</body>
</html>
		<?php
	}

	/** Warn the admin (in the toolbar) that the site is hidden from visitors. */
	public function admin_bar( $bar ) {
		if ( ! $this->is_active() || ! current_user_can( 'edit_themes' ) ) {
			return;
		}
		$bar->add_node(
			array(
				'id'    => 'coming-soon-lite-active',
				'title' => '⚠ ' . __( 'Coming Soon is ON', 'coming-soon-lite' ),
				'href'  => admin_url( 'options-general.php?page=coming-soon-lite' ),
				'meta'  => array( 'title' => __( 'Visitors see the coming-soon page', 'coming-soon-lite' ) ),
			)
		);
	}
}

add_action(
	'plugins_loaded',
	function () {
		( new ComingSoonLite( __FILE__ ) )->boot();
	}
);
