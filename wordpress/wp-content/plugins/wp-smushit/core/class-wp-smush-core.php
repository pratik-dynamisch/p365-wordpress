<?php
/**
 * WP_Smush_Core class: WP_Smush_Core class.
 *
 * @since 2.9.0
 * @package WP_Smush
 */

/**
 * Class WP_Smush_Core
 */
class WP_Smush_Core {

	/**
	 * DB option name.
	 */
	const OPTION_NAME = 'smush_option';

	/**
	 * S3 module
	 *
	 * @var WP_Smush_S3
	 */
	public $s3;

	/**
	 * NextGen module.
	 *
	 * @var WP_Smush_Nextgen
	 */
	public $nextgen;

	/**
	 * Modules array.
	 *
	 * @var WP_Smush_Modules
	 */
	public $mod;

	/**
	 * Plugin options.
	 *
	 * @var null|array
	 */
	protected $options = null;

	/**
	 * Default options and values go here.
	 *
	 * @var array $defaults
	 */
	protected $defaults = array(
		'version' => WP_SMUSH_VERSION, // This one should not change.
	);

	/**
	 * Allowed mime types of image.
	 *
	 * @var array $mime_types
	 */
	public static $mime_types = array(
		'image/jpg',
		'image/jpeg',
		'image/x-citrix-jpeg',
		'image/gif',
		'image/png',
		'image/x-png',
	);

	/**
	 * List of pages where smush needs to be loaded.
	 *
	 * @var array $pages
	 */
	public static $pages = array(
		'nggallery-manage-images',
		'gallery_page_nggallery-manage-gallery',
		'gallery_page_wp-smush-nextgen-bulk',
		'post',
		'post-new',
		'upload',
		'toplevel_page_smush-network',
		'toplevel_page_smush',
	);

	/**
	 * List of smush settings pages.
	 *
	 * @var array $plugin_pages
	 */
	public static $plugin_pages = array(
		'gallery_page_wp-smush-nextgen-bulk',
		'toplevel_page_smush-network',
		'toplevel_page_smush',
	);

	/**
	 * List of featurws/settings that are free.
	 *
	 * @var array $basic_features
	 */
	public static $basic_features = array(
		'networkwide',
		'auto',
		'strip_exif',
		'resize',
		'gutenberg',
	);

	/**
	 * Link to upgrade.
	 *
	 * @var string $upgrade_url
	 */
	public $upgrade_url = 'https://premium.wpmudev.org/project/wp-smush-pro/';

	/**
	 * Settings array.
	 *
	 * @var array Settings
	 */
	public $settings;

	/**
	 * Stores the stats for all the images.
	 *
	 * @var array $stats
	 */
	public $stats;

	/**
	 * Attachment IDs.
	 *
	 * @var array $attachments
	 */
	public $attachments = array();

	/**
	 * Attachment IDs which are smushed.
	 *
	 * @var array $smushed_attachments
	 */
	public $smushed_attachments = array();

	/**
	 * Unsmushed image IDs.
	 *
	 * @var array $unsmushed_attachments
	 */
	public $unsmushed_attachments = array();

	/**
	 * Smushed attachments out of total attachments.
	 *
	 * @var int $smushed_count
	 */
	public $smushed_count;

	/**
	 * Smushed attachments out of total attachments.
	 *
	 * @var int $remaining_count
	 */
	public $remaining_count;

	/**
	 * Super Smushed attachments count.
	 *
	 * @var int $super_smushed
	 */
	public $super_smushed;

	/**
	 * Total count of attachments for smushing.
	 *
	 * @var int $total_count
	 */
	public $total_count;

	/**
	 * Image ids that needs to be resmushed.
	 *
	 * @var array $resmush_ids
	 */
	public $resmush_ids = array();

	/**
	 * Smushed attachments from selected directories.
	 *
	 * @var array $dir_stats
	 */
	public $dir_stats;

	/**
	 * Limit for allowed number of images per bulk request.
	 *
	 * This is enforced at api level too.
	 *
	 * @var int $max_free_bulk
	 */
	public static $max_free_bulk = 50;

	/**
	 * WP_Smush_Core constructor.
	 *
	 * @since 2.9.0
	 */
	public function __construct() {
		spl_autoload_register( array( $this, 'autoload' ) );

		$this->init();

		if ( is_admin() ) {
			add_action( 'admin_init', array( 'WP_Smush_Installer', 'upgrade_settings' ) );
		}

		// Enqueue scripts and initialize variables.
		add_action( 'admin_init', array( $this, 'admin_init' ) );

		// Send Smush stats for PRO members.
		add_filter( 'wpmudev_api_project_extra_data-912164', array( $this, 'send_smush_stats' ) );

		/**
		 * Load NextGen Gallery, instantiate the Async class. if hooked too late or early, auto Smush doesn't
		 * work, also load after settings have been saved on init action.
		 */
		add_action( 'plugins_loaded', array( $this, 'load_libs' ), 90 );
	}

	/**
	 * Autoloader.
	 *
	 * @since 2.9.0
	 *
	 * @param string $class_name  Class name to autoload.
	 */
	public function autoload( $class_name ) {
		// Parse only Smush dependencies.
		if ( 0 !== strpos( $class_name, 'WP_Smush' ) ) {
			return;
		}

		$class_parts = explode( '_', $class_name );

		if ( ! $class_parts ) {
			return;
		}

		// Convert all to lower case.
		$class_parts = array_map( 'strtolower', $class_parts );

		// Build path.
		$filename = implode( '-', $class_parts );
		$file     = WP_SMUSH_DIR . "core/modules/class-{$filename}.php";

		if ( is_readable( $file ) ) {
			/* @noinspection PhpIncludeInspection */
			require_once $file;
		}
	}

	/**
	 * Enqueue scripts and initialize variables.
	 */
	public function admin_init() {
		$this->init_settings();

		// Handle notice dismiss.
		$this->dismiss_smush_upgrade();

		// Perform migration if required.
		$this->migrate();

		// Initialize variables.
		$this->initialise();

		// Localize version, update.
		$this->get_options();

		// Load integrations.
		$this->load_integrations();
	}

	/**
	 * Manually Dismiss Smush Upgrade notice
	 */
	private function dismiss_smush_upgrade() {
		if ( isset( $_GET['remove_smush_upgrade_notice'] ) && 1 == $_GET['remove_smush_upgrade_notice'] ) {
			WP_Smush::get_instance()->admin()->ajax->dismiss_upgrade_notice( false );
		}
	}

	/**
	 * Migrates smushit api message to the latest structure
	 *
	 * @todo move to installer class
	 */
	private function migrate() {
		if ( ! version_compare( WP_SMUSH_VERSION, '1.7.1', 'lt' ) ) {
			return;
		}

		// Meta key to save migrated version.
		$migrated_version_key = 'wp-smush-migrated-version';

		$migrated_version = get_site_option( $migrated_version_key );

		if ( WP_SMUSH_VERSION === $migrated_version ) {
			return;
		}

		global $wpdb;

		$results = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM {$wpdb->postmeta} WHERE meta_key=%s AND meta_value LIKE %s",
				'_wp_attachment_metadata',
				'%wp_smushit%'
			)
		);

		if ( count( $results ) < 1 ) {
			return;
		}

		$migrator = new WP_Smush_Migrate();
		foreach ( $results as $attachment_meta ) {
			$migrated_message = $migrator->migrate_api_message( maybe_unserialize( $attachment_meta->meta_value ) );
			if ( array() !== $migrated_message ) {
				update_post_meta( $attachment_meta->post_id, WP_Smushit::$smushed_meta_key, $migrated_message );
			}
		}

		update_site_option( $migrated_version_key, WP_SMUSH_VERSION );
	}

	/**
	 * Initialise the setting variables
	 */
	public function initialise() {
		$settings = WP_Smush_Settings::get_instance();
		// Check if lossy enabled.
		$this->mod->smush->lossy_enabled = WP_Smush::is_pro() && $settings->get( 'lossy' );

		// Check if Smush original enabled.
		$this->mod->smush->smush_original = WP_Smush::is_pro() && $settings->get( 'original' );

		// Check whether to keep EXIF data or not.
		$this->mod->smush->keep_exif = ! $settings->get( 'strip_exif' );
	}

	/**
	 * Store/Perform updates as per the plugin version
	 *
	 * @return array|mixed|null
	 *
	 * Source: Stackoverflow
	 * https://wordpress.stackexchange.com/a/49797/32466
	 */
	private function get_options() {
		// Already did the checks.
		if ( isset( $this->options ) ) {
			return $this->options;
		}

		// First call, get the options.
		$options = get_option( self::OPTION_NAME );

		// Options exist.
		if ( false !== $options ) {
			$new_version = version_compare( $options['version'], WP_SMUSH_VERSION, '!=' );
			// $desync      = array_diff_key( $this->defaults, $options ) !== array_diff_key( $options, $this->defaults );
			// update options if version changed
			if ( $new_version ) {
				$new_options = array();

				// Check for new options and set defaults if necessary.
				foreach ( $this->defaults as $option => $value ) {
					$new_options[ $option ] = isset( $options[ $option ] ) ? $options[ $option ] : $value;
				}

				// Update version info.
				$new_options['version'] = WP_SMUSH_VERSION;

				update_option( self::OPTION_NAME, $new_options );
				$this->options = $new_options;
			} else {
				$this->options = $options;
			}
			// New install (plugin was just activated).
		} else {
			// Store the version details.
			update_option( self::OPTION_NAME, $this->defaults );
			$this->options = $this->defaults;
		}

		return $this->options;
	}

	/**
	 * Load integrations class.
	 *
	 * @since 2.8.0
	 */
	private function load_integrations() {
		/* @noinspection PhpIncludeInspection */
		require_once WP_SMUSH_DIR . 'core/integrations/class-wp-smush-common.php';

		new WP_Smush_Common();
	}

	/**
	 * Initialize modules.
	 *
	 * @since 2.9.0
	 */
	private function init() {
		/* @noinspection PhpIncludeInspection */
		require_once WP_SMUSH_DIR . 'core/class-wp-smush-modules.php';
		/* @noinspection PhpIncludeInspection */
		require_once WP_SMUSH_DIR . 'core/modules/abstract-wp-smush-module.php';

		$this->mod = new WP_Smush_Modules();

		new WP_Smush_Auto_Resize();
		new WP_Smush_Rest();
	}

	/**
	 * Load plugin modules.
	 */
	public function load_libs() {
		/* @noinspection PhpIncludeInspection */
		require_once WP_SMUSH_DIR . 'core/integrations/abstract-wp-smush-integration.php';

		$this->s3_libraries();
		$this->wp_smush_async();

		// Load Nextgen lib, and initialize wp smush async class.
		$this->load_nextgen();
		$this->load_gutenberg();
	}

	/**
	 * Initialize S3 integration libraries.
	 */
	private function s3_libraries() {
		/* @noinspection PhpIncludeInspection */
		require_once WP_SMUSH_DIR . 'core/integrations/class-wp-smush-s3.php';

		if ( class_exists( 'AS3CF_Plugin_Compatibility' ) ) {
			/* @noinspection PhpIncludeInspection */
			require_once WP_SMUSH_DIR . 'core/integrations/s3/class-wp-smush-s3-compat.php';
		}

		$this->s3 = new WP_Smush_S3();
	}

	/**
	 * Check if NextGen is active or not
	 * Include and instantiate classes
	 */
	private function load_nextgen() {
		/* @noinspection PhpIncludeInspection */
		require_once WP_SMUSH_DIR . 'core/integrations/class-wp-smush-nextgen.php';

		// Load only if integration is enabled and PRO user.
		if ( WP_Smush_Settings::get_instance()->get( 'nextgen' ) && WP_Smush::is_pro() ) {
			/* @noinspection PhpIncludeInspection */
			require_once WP_SMUSH_DIR . 'core/integrations/nextgen/class-wp-smush-nextgen-admin.php';
			/* @noinspection PhpIncludeInspection */
			require_once WP_SMUSH_DIR . 'core/integrations/nextgen/class-wp-smush-nextgen-stats.php';
			/* @noinspection PhpIncludeInspection */
			include_once WP_SMUSH_DIR . 'app/class-wp-smush-nextgen.php';
		}

		if ( ! is_object( $this->nextgen ) ) {
			$this->nextgen = new WP_Smush_Nextgen();
		}
	}

	/**
	 * Load Gutenberg integration.
	 *
	 * @since 2.8.1
	 */
	private function load_gutenberg() {
		/* @noinspection PhpIncludeInspection */
		require_once WP_SMUSH_DIR . 'core/integrations/class-wp-smush-gutenberg.php';

		new WP_Smush_Gutenberg();
	}

	/**
	 * Initialize the Smush Async class.
	 */
	private function wp_smush_async() {
		// Don't load the Async task, if user not logged in or not in backend.
		if ( ! is_admin() || ! is_user_logged_in() ) {
			return;
		}

		// Check if Async is disabled.
		if ( defined( 'WP_SMUSH_ASYNC' ) && ! WP_SMUSH_ASYNC ) {
			return;
		}

		// Instantiate class.
		new WP_Smush_Async();
		new WP_Smush_Async_Editor();
	}

	/**
	 * Init settings.
	 */
	private function init_settings() {
		$this->settings = array(
			'networkwide' => array(
				'label'       => esc_html__( 'Use network settings for all the sub-sites.', 'wp-smushit' ),
				'short_label' => esc_html__( 'Multisite Control', 'wp-smushit' ),
				'desc'        => esc_html__( 'Choose whether you want to use network settings for all sub-sites or whether sub-site admins can control Smush’s settings.', 'wp-smushit' ),
			),
			'auto'        => array(
				'label'       => esc_html__( 'Automatically smush my images on upload', 'wp-smushit' ),
				'short_label' => esc_html__( 'Automatic Smush', 'wp-smushit' ),
				'desc'        => esc_html__( 'When you upload images to your site, Smush will automatically optimize and compress them for you.', 'wp-smushit' ),
			),
			'lossy'       => array(
				'label'       => esc_html__( 'Super-smush my images', 'wp-smushit' ),
				'short_label' => esc_html__( 'Super-smush', 'wp-smushit' ),
				'desc'        => esc_html__( 'Optimize images up to 2x more than regular smush with our multi-pass lossy compression.', 'wp-smushit' ),
			),
			'strip_exif'  => array(
				'label'       => esc_html__( 'Strip my image metadata', 'wp-smushit' ),
				'short_label' => esc_html__( 'Metadata', 'wp-smushit' ),
				'desc'        => esc_html__( 'Whenever you take a photo, your camera stores metadata, such as focal length, date, time and location, within the image.', 'wp-smushit' ),
			),
			'resize'      => array(
				'label'       => esc_html__( 'Resize my full size images', 'wp-smushit' ),
				'short_label' => esc_html__( 'Image resizing', 'wp-smushit' ),
				'desc'        => esc_html__( 'Detect unnecessarily large oversize images on your pages to reduce their size and decrease load times.', 'wp-smushit' ),
			),
			'detection'   => array(
				'label'       => esc_html__( 'Detect and show incorrectly sized images', 'wp-smushit' ),
				'short_label' => esc_html__( 'Image resizing', 'wp-smushit' ),
				'desc'        => esc_html__( 'This will add functionality to your website that highlights images that are either too large or too small for their containers. Note: The highlighting will only be visible to administrators – visitors won’t see the highlighting.', 'wp-smushit' ),
			),
			'original'    => array(
				'label'       => esc_html__( 'Smush my original full size images', 'wp-smushit' ),
				'short_label' => esc_html__( 'Original images', 'wp-smushit' ),
				'desc'        => esc_html__( 'Choose how you want Smush to handle the original image file when you run a bulk smush.', 'wp-smushit' ),
			),
			'backup'      => array(
				'label'       => esc_html__( 'Store a copy of my full size images', 'wp-smushit' ),
				'short_label' => esc_html__( 'Original images', 'wp-smushit' ),
				'desc'        => esc_html__( 'Save a copy of your original full-size images separately so you can restore them at any point. Note: Keeping a copy of your original files can significantly increase the size of your uploads folder by nearly twice as much.', 'wp-smushit' ),
			),
			'png_to_jpg'  => array(
				'label'       => esc_html__( 'Auto-convert PNGs to JPEGs (lossy)', 'wp-smushit' ),
				'short_label' => esc_html__( 'PNG to JPEG conversion', 'wp-smushit' ),
				'desc'        => esc_html__( 'When you compress a PNG, Smush will check if converting it to JPEG could further reduce its size.', 'wp-smushit' ),
			),
			'accessible_colors'  => array(
				'label'       => esc_html__( 'Enable high contrast mode', 'wp-smushit' ),
				'short_label' => esc_html__( 'Color Accessibility', 'wp-smushit' ),
				'desc'        => esc_html__( 'Increase the visibility and accessibility of elements and components to meet WCAG AAA requirements.', 'wp-smushit' ),
			),
			'usage'       => array(
				'label'       => esc_html__( 'Help us make Smush better by allowing usage tracking', 'wp-smushit' ),
				'short_label' => esc_html__( 'Usage Tracking', 'wp-smushit' ),
				'desc'        => esc_html__( 'Help make Smush better by letting our designers learn how you’re using the plugin.', 'wp-smushit' ),
			),
		);

		/**
		 * Allow to add other settings via filtering the variable
		 *
		 * Like Nextgen and S3 integration
		 */
		$this->settings = apply_filters( 'wp_smush_settings', $this->settings );

		// Initialize Image dimensions.
		$this->mod->smush->image_sizes = $this->image_dimensions();
	}

	/**
	 * Localize translations.
	 */
	public function localize() {
		global $current_screen;

		$current_page = ! empty( $current_screen ) ? $current_screen->base : '';

		$handle = 'smush-admin';

		$wp_smush_msgs = array(
			'resmush'                 => esc_html__( 'Super-Smush', 'wp-smushit' ),
			'smush_now'               => esc_html__( 'Smush Now', 'wp-smushit' ),
			'error_in_bulk'           => esc_html__( '{{smushed}}/{{total}} images were successfully compressed, {{errors}} encountered issues.', 'wp-smushit' ),
			'all_resmushed'           => esc_html__( 'All images are fully optimized.', 'wp-smushit' ),
			'restore'                 => esc_html__( 'Restoring image..', 'wp-smushit' ),
			'smushing'                => esc_html__( 'Smushing image..', 'wp-smushit' ),
			'checking'                => esc_html__( 'Checking images..', 'wp-smushit' ),
			'membership_valid'        => esc_html__( 'We successfully verified your membership, all the Pro features should work completely. ', 'wp-smushit' ),
			'membership_invalid'      => esc_html__( "Your membership couldn't be verified.", 'wp-smushit' ),
			'missing_path'            => esc_html__( 'Missing file path.', 'wp-smushit' ),
			// Used by Directory Smush.
			'unfinished_smush_single' => esc_html__( 'image could not be smushed.', 'wp-smushit' ),
			'unfinished_smush'        => esc_html__( 'images could not be smushed.', 'wp-smushit' ),
			'already_optimised'       => esc_html__( 'Already Optimized', 'wp-smushit' ),
			'ajax_error'              => esc_html__( 'Ajax Error', 'wp-smushit' ),
			'all_done'                => esc_html__( 'All Done!', 'wp-smushit' ),
			'sync_stats'              => esc_html__( 'Give us a moment while we sync the stats.', 'wp-smushit' ),
			// Button text.
			'resmush_check'           => esc_html__( 'RE-CHECK IMAGES', 'wp-smushit' ),
			'resmush_complete'        => esc_html__( 'CHECK COMPLETE', 'wp-smushit' ),
			// Progress bar text.
			'progress_smushed'        => esc_html__( 'images optimized', 'wp-smushit' ),
			'directory_url'           => admin_url( 'admin.php?page=smush&view=directory' ),
			'bulk_resume'             => esc_html__( 'Resume scan', 'wp-smushit' ),
			'bulk_stop'               => esc_html__( 'Stop current bulk smush process.', 'wp-smushit' ),
		);

		wp_localize_script( $handle, 'wp_smush_msgs', $wp_smush_msgs );

		// Load the stats on selected screens only.
		if ( 'toplevel_page_smush' === $current_page ) {
			// Get resmush list, If we have a resmush list already, localize those IDs.
			if ( $resmush_ids = get_option( 'wp-smush-resmush-list' ) ) {
				// Get the attachments, and get lossless count.
				$this->resmush_ids = $resmush_ids;
			}

			// Setup all the stats.
			$this->setup_global_stats( true );

			// Localize smushit_IDs variable, if there are fix number of IDs.
			$this->unsmushed_attachments = ! empty( $_REQUEST['ids'] ) ? array_map( 'intval', explode( ',', $_REQUEST['ids'] ) ) : array();

			if ( empty( $this->unsmushed_attachments ) ) {
				// Get attachments if all the images are not smushed.
				$this->unsmushed_attachments = $this->remaining_count > 0 ? $this->mod->db->get_unsmushed_attachments() : array();
				$this->unsmushed_attachments = ! empty( $this->unsmushed_attachments ) && is_array( $this->unsmushed_attachments ) ? array_values( $this->unsmushed_attachments ) : $this->unsmushed_attachments;
			}

			// Array of all smushed, unsmushed and lossless IDs.
			$data = array(
				'count_supersmushed' => $this->super_smushed,
				'count_smushed'      => $this->smushed_count,
				'count_total'        => $this->total_count,
				'count_images'       => $this->stats['total_images'],
				'count_resize'       => $this->stats['resize_count'],
				'unsmushed'          => $this->unsmushed_attachments,
				'resmush'            => $this->resmush_ids,
				'size_before'        => $this->stats['size_before'],
				'size_after'         => $this->stats['size_after'],
				'savings_bytes'      => $this->stats['bytes'],
				'savings_resize'     => $this->stats['resize_savings'],
				'savings_conversion' => $this->stats['conversion_savings'],
				'savings_dir_smush'  => $this->dir_stats,
			);
		} else {
			$data = array(
				'count_supersmushed' => '',
				'count_smushed'      => '',
				'count_total'        => '',
				'count_images'       => '',
				'unsmushed'          => '',
				'resmush'            => '',
				'savings_bytes'      => '',
				'savings_resize'     => '',
				'savings_conversion' => '',
				'savings_supersmush' => '',
				'pro_savings'        => '',
			);
		} // End if().

		// Check if scanner class is available.
		$scanner_ready = isset( $this->mod->dir->scanner );

		$data['dir_smush'] = array(
			'currentScanStep' => $scanner_ready ? $this->mod->dir->scanner->get_current_scan_step() : 0,
			'totalSteps'      => $scanner_ready ? $this->mod->dir->scanner->get_scan_steps() : 0,
		);

		$data['resize_sizes'] = $this->get_max_image_dimensions();

		// Convert it into ms.
		$data['timeout'] = WP_SMUSH_TIMEOUT * 1000;

		wp_localize_script( $handle, 'wp_smushit_data', $data );

		// Check if settings were changed for a multisite, and localize whether to run re-check on page load.
		if ( is_multisite() && WP_Smush_Settings::get_instance()->is_network_enabled() && ! is_network_admin() ) {
			// If not same, Set a variable to run re-check on page load.
			if ( get_site_option( WP_SMUSH_PREFIX . 'run_recheck', false ) ) {
				wp_localize_script( $handle, 'wp_smush_run_re_check', array( 1 ) );
			}
		}
	}

	/**
	 * Check bulk sent count, whether to allow further smushing or not
	 *
	 * @param bool   $reset  To hard reset the transient.
	 * @param string $key    Transient Key - bulk_sent_count/dir_sent_count.
	 *
	 * @return bool
	 */
	public static function check_bulk_limit( $reset = false, $key = 'bulk_sent_count' ) {
		$transient_name = WP_SMUSH_PREFIX . $key;

		$bulk_sent_count = (int) get_transient( $transient_name );

		// Check if bulk smush limit is less than limit.
		if ( ! $bulk_sent_count || $bulk_sent_count < self::$max_free_bulk ) {
			$continue = true;
		} elseif ( $bulk_sent_count === self::$max_free_bulk ) {
			// If user has reached the limit, reset the transient.
			$continue = false;
			$reset    = true;
		} else {
			$continue = false;
		}

		// If we need to reset the transient.
		if ( $reset ) {
			set_transient( $transient_name, 0, 60 );
		}

		return $continue;
	}

	/**
	 * Return Global stats
	 *
	 * Stats sent
	 *
	 *  array( 'total_images','bytes', 'human', 'percent')
	 *
	 * @return array|bool|mixed
	 */
	public function send_smush_stats() {
		$stats = $this->global_stats();

		$required_stats = array( 'total_images', 'bytes', 'human', 'percent' );

		$stats = is_array( $stats ) ? array_intersect_key( $stats, array_flip( $required_stats ) ) : array();

		return $stats;
	}

	/**
	 * Get registered image sizes with dimension
	 */
	public function image_dimensions() {
		global $_wp_additional_image_sizes;
		$additional_sizes = get_intermediate_image_sizes();
		$sizes            = array();

		if ( empty( $additional_sizes ) ) {
			return $sizes;
		}

		// Create the full array with sizes and crop info.
		foreach ( $additional_sizes as $_size ) {
			if ( in_array( $_size, array( 'thumbnail', 'medium', 'large' ), true ) ) {
				$sizes[ $_size ]['width']  = get_option( $_size . '_size_w' );
				$sizes[ $_size ]['height'] = get_option( $_size . '_size_h' );
				$sizes[ $_size ]['crop']   = (bool) get_option( $_size . '_crop' );
			} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
				$sizes[ $_size ] = array(
					'width'  => $_wp_additional_image_sizes[ $_size ]['width'],
					'height' => $_wp_additional_image_sizes[ $_size ]['height'],
					'crop'   => $_wp_additional_image_sizes[ $_size ]['crop'],
				);
			}
		}
		// Medium Large.
		if ( ! isset( $sizes['medium_large'] ) || empty( $sizes['medium_large'] ) ) {
			$width  = intval( get_option( 'medium_large_size_w' ) );
			$height = intval( get_option( 'medium_large_size_h' ) );

			$sizes['medium_large'] = array(
				'width'  => $width,
				'height' => $height,
			);
		}

		return $sizes;
	}

	/**
	 * Runs the expensive queries to get our global smush stats
	 *
	 * @param bool $force_update  Whether to force update the global stats or not.
	 */
	public function setup_global_stats( $force_update = false ) {
		// Set directory smush status.
		$this->dir_stats = WP_Smush_Dir::should_continue() ? $this->mod->dir->total_stats() : array();

		// Setup Attachments and total count.
		$this->mod->db->total_count( true );

		$this->stats = $this->global_stats( $force_update );

		if ( empty( $this->smushed_attachments ) ) {
			// Get smushed attachments.
			$this->smushed_attachments = $this->mod->db->smushed_count( true, $force_update );
		}

		// Get supersmushed images count.
		if ( empty( $this->super_smushed ) ) {
			$this->super_smushed = $this->mod->db->super_smushed_count();
		}

		// Set pro savings.
		$this->set_pro_savings();

		// Set smushed count.
		$this->smushed_count   = ! empty( $this->smushed_attachments ) ? count( $this->smushed_attachments ) : 0;
		$this->remaining_count = $this->remaining_count();
	}

	/**
	 * Get all the attachment meta, sum up the stats and return
	 *
	 * @param bool $force_update     Whether to forcefully update the cache.
	 *
	 * @return array|bool|mixed
	 *
	 * @todo: remove id from global stats stored in db
	 */
	public function global_stats( $force_update = false ) {
		if ( ! $force_update && $stats = get_option( 'smush_global_stats' ) ) {
			if ( ! empty( $stats ) && isset( $stats['size_before'] ) ) {
				if ( isset( $stats['id'] ) ) {
					unset( $stats['id'] );
				}

				return $stats;
			}
		}

		global $wpdb;

		$smush_data = array(
			'size_before' => 0,
			'size_after'  => 0,
			'percent'     => 0,
			'human'       => 0,
			'bytes'       => 0,
		);

		/**
		 * Allows to set a limit of mysql query
		 * Default value is 2000
		 */
		$limit      = $this->mod->db->query_limit();
		$offset     = 0;
		$query_next = true;

		$supersmushed_count         = 0;
		$smush_data['total_images'] = 0;

		while ( $query_next ) {
			$global_data = $wpdb->get_results( $wpdb->prepare( "SELECT post_id, meta_value FROM $wpdb->postmeta WHERE meta_key=%s LIMIT $offset, $limit", WP_Smushit::$smushed_meta_key ) );
			if ( ! empty( $global_data ) ) {
				foreach ( $global_data as $data ) {
					// Skip attachment, if not in attachment list.
					if ( ! in_array( $data->post_id, $this->attachments ) ) {
						continue;
					}

					$smush_data['id'][] = $data->post_id;
					if ( ! empty( $data->meta_value ) ) {
						$meta = maybe_unserialize( $data->meta_value );
						if ( ! empty( $meta['stats'] ) ) {

							// Check for lossy compression.
							if ( 1 == $meta['stats']['lossy'] ) {
								$supersmushed_count++;
							}

							// If the image was optimised.
							if ( ! empty( $meta['stats'] ) && $meta['stats']['size_before'] >= $meta['stats']['size_after'] ) {
								// Total Image Smushed.
								$smush_data['total_images'] += ! empty( $meta['sizes'] ) ? count( $meta['sizes'] ) : 0;

								$smush_data['size_before'] += ! empty( $meta['stats']['size_before'] ) ? (int) $meta['stats']['size_before'] : 0;
								$smush_data['size_after']  += ! empty( $meta['stats']['size_after'] ) ? (int) $meta['stats']['size_after'] : 0;
							}
						}
					}
				}
			}

			$smush_data['bytes'] = $smush_data['size_before'] - $smush_data['size_after'];

			// Update the offset.
			$offset += $limit;

			// Compare the Offset value to total images.
			if ( ! empty( $this->total_count ) && $this->total_count <= $offset ) {
				$query_next = false;
			} elseif ( ! $global_data ) {
				// If we didn' got any results.
				$query_next = false;
			}
		}

		// Add directory smush image bytes.
		if ( ! empty( $this->dir_stats['bytes'] ) && $this->dir_stats['bytes'] > 0 ) {
			$smush_data['bytes'] += $this->dir_stats['bytes'];
		}
		// Add directory smush image total size.
		if ( ! empty( $this->dir_stats['orig_size'] ) && $this->dir_stats['orig_size'] > 0 ) {
			$smush_data['size_before'] += $this->dir_stats['orig_size'];
		}
		// Add directory smush saved size.
		if ( ! empty( $this->dir_stats['image_size'] ) && $this->dir_stats['image_size'] > 0 ) {
			$smush_data['size_after'] += $this->dir_stats['image_size'];
		}
		// Add directory smushed images.
		if ( ! empty( $this->dir_stats['optimised'] ) && $this->dir_stats['optimised'] > 0 ) {
			$smush_data['total_images'] += $this->dir_stats['optimised'];
		}

		// Resize Savings.
		$smush_data['resize_count']   = $this->mod->db->resize_savings( false, false, true );
		$resize_savings               = $this->mod->db->resize_savings( false );
		$smush_data['resize_savings'] = ! empty( $resize_savings['bytes'] ) ? $resize_savings['bytes'] : 0;

		// Conversion Savings.
		$conversion_savings               = $this->mod->db->conversion_savings( false );
		$smush_data['conversion_savings'] = ! empty( $conversion_savings['bytes'] ) ? $conversion_savings['bytes'] : 0;

		if ( ! isset( $smush_data['bytes'] ) || $smush_data['bytes'] < 0 ) {
			$smush_data['bytes'] = 0;
		}

		// Add the resize savings to bytes.
		$smush_data['bytes']       += $smush_data['resize_savings'];
		$smush_data['size_before'] += $resize_savings['size_before'];
		$smush_data['size_after']  += $resize_savings['size_after'];

		// Add Conversion Savings.
		$smush_data['bytes']       += $smush_data['conversion_savings'];
		$smush_data['size_before'] += $conversion_savings['size_before'];
		$smush_data['size_after']  += $conversion_savings['size_after'];

		if ( $smush_data['size_before'] > 0 ) {
			$smush_data['percent'] = ( $smush_data['bytes'] / $smush_data['size_before'] ) * 100;
		}

		// Round off precentage.
		$smush_data['percent'] = round( $smush_data['percent'], 1 );

		$smush_data['human'] = size_format( $smush_data['bytes'], 1 );

		// Setup Smushed attachment IDs.
		$this->smushed_attachments = ! empty( $smush_data['id'] ) ? $smush_data['id'] : '';

		// Super Smushed attachment count.
		$this->super_smushed = $supersmushed_count;

		// Remove ids from stats.
		unset( $smush_data['id'] );

		// Update cache.
		update_option( 'smush_global_stats', $smush_data, false );

		return $smush_data;
	}

	/**
	 * Set pro savings stats if not premium user.
	 *
	 * For non-premium users, show expected avarage savings based
	 * on the free version savings.
	 */
	public function set_pro_savings() {
		// No need this already premium.
		if ( WP_Smush::is_pro() ) {
			return;
		}

		// Initialize.
		$this->stats['pro_savings'] = array(
			'percent' => 0,
			'savings' => 0,
		);

		// Default values.
		$savings       = $this->stats['percent'] > 0 ? $this->stats['percent'] : 0;
		$savings_bytes = $this->stats['human'] > 0 ? $this->stats['bytes'] : '0';
		$orig_diff     = 2.22058824;
		if ( ! empty( $savings ) && $savings > 49 ) {
			$orig_diff = 1.22054412;
		}
		// Calculate Pro savings.
		if ( ! empty( $savings ) ) {
			$savings       = $orig_diff * $savings;
			$savings_bytes = $orig_diff * $savings_bytes;
		}

		// Set pro savings in global stats.
		if ( $savings > 0 ) {
			$this->stats['pro_savings'] = array(
				'percent' => number_format_i18n( $savings, 1 ),
				'savings' => size_format( $savings_bytes, 1 ),
			);
		}
	}

	/**
	 * Returns remaining count
	 *
	 * @return int
	 */
	private function remaining_count() {
		// Check if the resmush count is equal to remaining count.
		$resmush_count   = count( $this->resmush_ids );
		$remaining_count = $this->total_count - $this->smushed_count;
		if ( $resmush_count > 0 && $resmush_count === $this->smushed_count ) {
			return $resmush_count + $remaining_count;
		}

		return $remaining_count;
	}

	/**
	 * Get the Maximum Width and Height settings for WrodPress
	 *
	 * @return array, Array of Max. Width and Height for image.
	 */
	public function get_max_image_dimensions() {
		global $_wp_additional_image_sizes;

		$width = $height = 0;
		$limit = 9999; // Post-thumbnail.

		$image_sizes = get_intermediate_image_sizes();

		// If image sizes are filtered and no image size list is returned.
		if ( empty( $image_sizes ) ) {
			return array(
				'width'  => $width,
				'height' => $height,
			);
		}

		// Create the full array with sizes and crop info.
		foreach ( $image_sizes as $size ) {
			if ( in_array( $size, array( 'thumbnail', 'medium', 'medium_large', 'large' ), true ) ) {
				$size_width  = get_option( "{$size}_size_w" );
				$size_height = get_option( "{$size}_size_h" );
			} elseif ( isset( $_wp_additional_image_sizes[ $size ] ) ) {
				$size_width  = $_wp_additional_image_sizes[ $size ]['width'];
				$size_height = $_wp_additional_image_sizes[ $size ]['height'];
			}

			// Skip if no width and height.
			if ( ! isset( $size_width, $size_height ) ) {
				continue;
			}

			// If within te limit, check for a max value.
			if ( $size_width <= $limit ) {
				$width = max( $width, $size_width );
			}

			if ( $size_height <= $limit ) {
				$height = max( $height, $size_height );
			}
		}

		return array(
			'width'  => $width,
			'height' => $height,
		);
	}

	/**
	 * Update the image smushed count in transient
	 *
	 * @param string $key  Database key.
	 */
	public static function update_smush_count( $key = 'bulk_sent_count' ) {
		$transient_name = WP_SMUSH_PREFIX . $key;

		$bulk_sent_count = get_transient( $transient_name );

		// If bulk sent count is not set.
		if ( false === $bulk_sent_count ) {
			// Start transient at 0.
			set_transient( $transient_name, 1, 200 );
		} elseif ( $bulk_sent_count < self::$max_free_bulk ) {
			// If lte $this->max_free_bulk images are sent, increment.
			set_transient( $transient_name, $bulk_sent_count + 1, 200 );
		}
	}

}
