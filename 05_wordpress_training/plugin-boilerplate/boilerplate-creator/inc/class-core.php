<?php
/**
 * Core class
 *
 * This file contains Core class which can handle whole script process
 *
 * @package    Boilerplate_Creatore
 * @author     Mehdi Soltani <soltani.n.mehdi@gmail.com>
 * @license    https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @link       https://wpwebmaster.ir
 * @since      1.0.0
 */

namespace Boilerplate_Creator\Inc;

use Boilerplate_Creator\Inc\Functions\{
	Utility, Files_Process
};
use Boilerplate_Creator\Inc\Setting;

/**
 * Class Core
 *
 * This file contains core class which can set Primary class for script
 *
 * @package    Boilerplate_Creator
 * @author     Mehdi Soltani <soltani.n.mehdi@gmail.com>
 * @property Setting       $settings
 * @property boolean       $is_in_test_mode
 * @property Files_Process $file_process
 */
class Core {
	use Utility;
	/**
	 * @var Setting $settings Primary settings for this script
	 */
	protected $settings;
	/**
	 * @var boolean $is_in_test_mode
	 */
	protected $is_in_test_mode;
	/**
	 * @var Files_Process $file_process
	 */
	protected $file_process;

	public function __construct( Setting $settings, Files_Process $files_process ) {
		/**
		 * set time zone
		 */
		$this->set_time_zone( 'Asia/Tehran' );
		$this->settings        = $settings;
		$this->file_process    = $files_process;
		$this->is_in_test_mode = false;
	}

	public function init() {


		if ( $this->is_in_test_mode ) {
			var_dump( $this );
		} else {
			$this->create_new_project();
			$this->create_new_files_and_directories();
			$this->remove_extra_files();
			$this->rename_main_plugin_file();
			$this->customize_main_plugin_file();
			$this->rename_asset_files();
			$this->customize_abstract_classes();
			$this->customize_admin_classes();
			$this->customize_config_classes();
			$this->customize_database_classes();
			$this->customize_functions_classes();
			$this->customize_hooks_classes();
			$this->customize_init_classes();
			$this->customize_page_handlers_classes();
			$this->customize_parts_classes();
			$this->customize_uninstall_classes();
			$this->customize_autoloader_class();
			$this->customize_templates_classes();
			var_dump( $this );
		}
	}

	/**
	 * Create directory for new plugin
	 */
	public function create_new_project() {
		$result = $this->file_process->make_directory_if_not_exist( '../' . $this->settings->new_path, 'for new plugin path' );
		$this->file_process->append( $result ['message'], $this->settings->main_log_file );
		$this->file_process->append_section_separator( $this->settings->main_log_file );
	}

	/**
	 * Create new directories and files for new plugin
	 */
	public function create_new_files_and_directories() {
		$result = $this->file_process->copy_directory(
			$this->settings->old_full_path,
			$this->settings->new_full_path
		);
		$this->file_process->append( $result ['message'], $this->settings->main_log_file );
		$this->file_process->append_section_separator( $this->settings->main_log_file );
	}

	/**
	 * Remove extra file in new plugin directory
	 */
	public function remove_extra_files() {
		$this->file_process->remove_file( $this->settings->new_full_path . 'LICENSE.txt' );
		$this->file_process->remove_file( $this->settings->new_full_path . '.gitignore' );
		// TODO: log this process in future
	}

	/**
	 * Rename main plugin file
	 */
	public function rename_main_plugin_file() {
		$result = $this->file_process->rename_file(
			$this->settings->new_full_path . 'plugin-name.php',
			$this->settings->new_plugin_main_file_name
		);
		$this->file_process->append( $result ['message'], $this->settings->main_log_file );
		$this->file_process->append_section_separator( $this->settings->main_log_file );
	}

	/**
	 * Change main plugin file with new values
	 */
	public function customize_main_plugin_file() {
		$search_and_replace_items = [
			[
				'search'  => $this->settings->old_plugin_name_in_header,
				'replace' => $this->settings->new_plugin_name_in_header,
			],
			[
				'search'  => $this->settings->old_plugin_description,
				'replace' => $this->settings->new_plugin_description,
			],
			[
				'search'  => $this->settings->old_plugin_version,
				'replace' => $this->settings->new_plugin_version,
			],
			[
				'search'  => $this->settings->old_namespace,
				'replace' => $this->settings->new_namespace,
			],
			[
				'search'  => $this->settings->old_namespace,
				'replace' => $this->settings->new_namespace,
			],
			[
				'search'  => $this->settings->old_link,
				'replace' => $this->settings->new_link,
			],
			[
				'search'  => $this->settings->old_author_name,
				'replace' => $this->settings->new_author_name,
			],
			[
				'search'  => $this->settings->old_author_uri,
				'replace' => $this->settings->new_author_uri,
			],
			[
				'search'  => $this->settings->old_author_email,
				'replace' => $this->settings->new_author_email,
			],
			[
				'search'  => $this->settings->old_main_plugin_name,
				'replace' => $this->settings->new_main_plugin_name,
			],
			[
				'search'  => $this->settings->old_plugin_name_main_name_const,
				'replace' => $this->settings->new_plugin_name_main_name_const,
			],
			[
				'search'  => $this->settings->old_plugin_name_const_prefix,
				'replace' => $this->settings->new_plugin_name_const_prefix,
			],
			[
				'search'  => $this->settings->old_plugin_name_method_prefix,
				'replace' => $this->settings->new_plugin_name_method_prefix,
			],


		];
		$result                   = $this->file_process->do_search_and_replace(
			$this->settings->new_plugin_main_file_name,
			$search_and_replace_items
		);

		$this->file_process->append( $result ['message'], $this->settings->main_log_file );
		$this->file_process->append_section_separator( $this->settings->main_log_file );
	}

	/**
	 * Rename asset files
	 */
	public function rename_asset_files() {
		$rename_list_items = [
			[
				'old_name' => $this->settings->new_full_path . 'assets/admin/css/plugin-name-admin.css',
				'new_name' => $this->settings->new_full_path . 'assets/admin/css/' . $this->settings->new_file_name_prefix . '-admin.css',
			],
			[
				'old_name' => $this->settings->new_full_path . 'assets/admin/css/plugin-name-admin-ver-1.css',
				'new_name' => $this->settings->new_full_path . 'assets/admin/css/' . $this->settings->new_file_name_prefix . '-admin-ver-1.css',
			],
			[
				'old_name' => $this->settings->new_full_path . 'assets/admin/js/plugin-name-admin.js',
				'new_name' => $this->settings->new_full_path . 'assets/admin/js/' . $this->settings->new_file_name_prefix . '-admin.js',
			],
			[
				'old_name' => $this->settings->new_full_path . 'assets/admin/js/plugin-name-admin-ver-1.js',
				'new_name' => $this->settings->new_full_path . 'assets/admin/js/' . $this->settings->new_file_name_prefix . '-admin-ver-1.js',
			],
			[
				'old_name' => $this->settings->new_full_path . 'assets/css/plugin-name-public.css',
				'new_name' => $this->settings->new_full_path . 'assets/css/' . $this->settings->new_file_name_prefix . '-public.css',
			],
			[
				'old_name' => $this->settings->new_full_path . 'assets/css/plugin-name-public-ver-1.css',
				'new_name' => $this->settings->new_full_path . 'assets/css/' . $this->settings->new_file_name_prefix . '-public-ver-1.css',
			],
			[
				'old_name' => $this->settings->new_full_path . 'assets/js/plugin-name-public.js',
				'new_name' => $this->settings->new_full_path . 'assets/js/' . $this->settings->new_file_name_prefix . '-public.js',
			],
			[
				'old_name' => $this->settings->new_full_path . 'assets/js/plugin-name-public-ver-1.js',
				'new_name' => $this->settings->new_full_path . 'assets/js/' . $this->settings->new_file_name_prefix . '-public-ver-1.js',
			],
			[
				'old_name' => $this->settings->new_full_path . 'languages/plugin-name.pot',
				'new_name' => $this->settings->new_full_path . 'languages/' . $this->settings->new_file_name_prefix . '.pot',
			],
		];
		$results           = $this->file_process->files_bulk_rename( $rename_list_items );
		$this->file_process->several_appends( $results, $this->settings->main_log_file );
	}

	/**
	 * Customize abstract classes and interfaces in plugin
	 */
	public function customize_abstract_classes() {
		$settings_page_search_items         = [
			[
				'search'  => $this->settings->old_plugin_name_const_prefix . '_TEXTDOMAIN',
				'replace' => $this->settings->new_plugin_name_const_prefix . '_TEXTDOMAIN',
			],
			[
				'search'  => $this->settings->old_plugin_name_short_prefix,
				'replace' => $this->settings->new_plugin_name_short_prefix,
			],
		];
		$settings_page_search_items         = array_merge( $this->settings->general_search_items, $settings_page_search_items );
		$search_and_replace_list_items = [
			[
				'file_name'    => $this->settings->new_abstract_files_full_path . 'class-admin-menu.php',
				'search_items' => $this->settings->general_search_items,
			],
			[
				'file_name'    => $this->settings->new_abstract_files_full_path . 'class-admin-sub-menu.php',
				'search_items' => $this->settings->general_search_items,
			],
			[
				'file_name'    => $this->settings->new_abstract_files_full_path . 'class-admin-notice.php',
				'search_items' => $this->settings->general_search_items,
			],
			[
				'file_name'    => $this->settings->new_abstract_files_full_path . 'class-ajax.php',
				'search_items' => $this->settings->general_search_items, //TODO: it must be change in future
			],
			[
				'file_name'    => $this->settings->new_abstract_files_full_path . 'class-custom-post-type.php',
				'search_items' => $this->settings->general_search_items,
			],
			[
				'file_name'    => $this->settings->new_abstract_files_full_path . 'class-custom-taxonomy.php',
				'search_items' => $this->settings->general_search_items,
			],
			[
				'file_name'    => $this->settings->new_abstract_files_full_path . 'class-meta-box.php',
				'search_items' => $this->settings->general_search_items,
			],
			[
				'file_name'    => $this->settings->new_abstract_files_full_path . 'class-option-menu.php',
				'search_items' => $this->settings->general_search_items,
			],
			[
				'file_name'    => $this->settings->new_abstract_files_full_path . 'class-setting-page.php',
				'search_items' => $settings_page_search_items,
			],
			[
				'file_name'    => $this->settings->new_abstract_files_full_path . 'class-shortcode.php',
				'search_items' => $this->settings->general_search_items,
			],
			[
				'file_name'    => $this->settings->new_abstract_files_full_path . 'class-simple-setting-page.php',
				'search_items' => $this->settings->general_search_items,
			],
			[
				'file_name'    => $this->settings->new_interface_files_full_path . 'class-action-hook-interface.php',
				'search_items' => $this->settings->general_search_items,
			],
			[
				'file_name'    => $this->settings->new_interface_files_full_path . 'class-action-hook-with-args-interface.php',
				'search_items' => $this->settings->general_search_items,
			],
			[
				'file_name'    => $this->settings->new_interface_files_full_path . 'class-filter-hook-interface.php',
				'search_items' => $this->settings->general_search_items,
			],
			[
				'file_name'    => $this->settings->new_interface_files_full_path . 'custom-admin-columns/class-manage-post-columns.php',
				'search_items' => $this->settings->general_search_items,
			],


		];
		$this->do_repeated_search_and_replace_items( $search_and_replace_list_items );
	}

	/**
	 * Do repeated task for search and replace items and log them
	 *
	 * @param $search_and_replace_list_items
	 */
	public function do_repeated_search_and_replace_items( $search_and_replace_list_items ) {
		$results = $this->file_process->files_bulk_search_and_replace( $search_and_replace_list_items );
		$this->file_process->several_appends( $results, $this->settings->main_log_file );
	}

	/**
	 * Customize admin classes and interfaces in plugin
	 */
	public function customize_admin_classes() {
		$meta_box_search_items         = [
			[
				'search'  => $this->settings->old_plugin_name_const_prefix . '_TEXTDOMAIN',
				'replace' => $this->settings->new_plugin_name_const_prefix . '_TEXTDOMAIN',
			],
		];
		$meta_box_search_items         = array_merge( $this->settings->general_search_items, $meta_box_search_items );
		$admin_notice_search_items     = [
			[
				'search'  => $this->settings->old_small_name_with_dash,
				'replace' => $this->settings->new_small_name_with_dash,
			],
		];
		$admin_notice_search_items     = array_merge( $this->settings->general_search_items, $admin_notice_search_items );
		$search_and_replace_list_items = [
			[
				'file_name'    => $this->settings->new_admin_files_full_path . 'class-admin-menu1.php',
				'search_items' => $this->settings->general_search_items,
			],
			[
				'file_name'    => $this->settings->new_admin_files_full_path . 'class-admin-sub-menu1.php',
				'search_items' => $this->settings->general_search_items,
			],
			[
				'file_name'    => $this->settings->new_admin_files_full_path . 'class-admin-sub-menu2.php',
				'search_items' => $this->settings->general_search_items,
			],
			[
				'file_name'    => $this->settings->new_admin_files_full_path . 'class-meta-box3.php',
				'search_items' => $meta_box_search_items,
			],
			[
				'file_name'    => $this->settings->new_admin_files_full_path . 'class-meta-box4.php',
				'search_items' => $meta_box_search_items,
			],
			[
				'file_name'    => $this->settings->new_admin_files_full_path . 'class-option-menu1.php',
				'search_items' => $this->settings->general_search_items,
			],
			[
				'file_name'    => $this->settings->new_admin_files_full_path . 'class-option-menu2.php',
				'search_items' => $this->settings->general_search_items,
			],
			[
				'file_name'    => $this->settings->new_admin_files_full_path . 'class-setting-page1.php',
				'search_items' => $this->settings->general_search_items,
			],
			[
				'file_name'    => $this->settings->new_admin_files_full_path . 'class-simple-setting-in-reading-page1.php',
				'search_items' => $meta_box_search_items,
			],
			[
				'file_name'    => $this->settings->new_admin_files_full_path . 'class-simple-setting-page1.php',
				'search_items' => $this->settings->general_search_items,
			],
			[
				'file_name'    => $this->settings->new_admin_files_full_path . 'notices/class-admin-notice1.php',
				'search_items' => $admin_notice_search_items,
			],
			[
				'file_name'    => $this->settings->new_admin_files_full_path . 'notices/class-woocommerce-deactive-notice.php',
				'search_items' => $meta_box_search_items,
			],
		];
		$this->do_repeated_search_and_replace_items( $search_and_replace_list_items );
	}

	/**
	 * Customize config classes in new plugin
	 */
	public function customize_config_classes() {
		$info_class_search_items       = [
			[
				'search'  => 'plugin_name_prefix',
				'replace' => $this->settings->new_plugin_name_method_prefix,
			],
		];
		$info_class_search_items       = array_merge( $this->settings->general_search_items, $info_class_search_items );
		$initial_values_search_items   = [
			[
				'search'  => $this->settings->old_plugin_name_const_prefix . '_TEXTDOMAIN',
				'replace' => $this->settings->new_plugin_name_const_prefix . '_TEXTDOMAIN',
			],
			[
				'search'  => 'msn-new-post-type',
				'replace' => 'msn-new-post-type1',
			],
			[
				'search'  => 'msn_plugin_boilerplate',
				'replace' => $this->settings->new_plugin_name_method_prefix,
			],
			[
				'search'  => 'msn_oop_boilerplate',
				'replace' => $this->settings->new_plugin_name_method_prefix,
			],
			[
				'search'  => 'msnshortcode1',
				'replace' => 'msnnewshortcode1',
			],
			[
				'search'  => 'msn_content_for_login_user',
				'replace' => 'msn_new_content_for_login_user',
			],
			[
				'search'  => 'msn_complete_shortcode',
				'replace' => 'msn_new_complete_shortcode',
			],
			[
				'search'  => 'name1',
				'replace' => 'newname1',
			],
			[
				'search'  => 'name1',
				'replace' => 'newname1',
			],
			[
				'search'  => 'sample-taxonomy1',
				'replace' => 'sample-new-taxonomy1',
			],
			[
				'search'  => $this->settings->old_small_name_with_dash,
				'replace' => $this->settings->new_small_name_with_dash,
			],
			[
				'search'  => $this->settings->old_plugin_name_method_prefix ,
				'replace' => $this->settings->new_plugin_name_method_prefix,
			],
		];
		$initial_values_search_items   = array_merge( $this->settings->general_search_items, $initial_values_search_items );
		$search_and_replace_list_items = [
			[
				'file_name'    => $this->settings->new_config_files_full_path . 'class-info.php',
				'search_items' => $info_class_search_items,
			],
			[
				'file_name'    => $this->settings->new_config_files_full_path . 'class-initial-value.php',
				'search_items' => $initial_values_search_items,
			],
		];
		$this->do_repeated_search_and_replace_items( $search_and_replace_list_items );

	}

	/**
	 * Customize database classes in new plugin
	 */
	public function customize_database_classes() {
		$table_class_search_items = [
			[
				'search'  => 'your_table_name_in_mysql',
				'replace' => 'new_plugin_table1',
			],
			[
				'search'  => 'has_table_name',
				'replace' => 'has_new_plugin_table1',
			],
		];
		$table_class_search_items = array_merge( $this->settings->general_search_items, $table_class_search_items );

		$search_and_replace_list_items = [
			[
				'file_name'    => $this->settings->new_database_files_full_path . 'class-table.php',
				'search_items' => $table_class_search_items,
			],
		];
		$this->do_repeated_search_and_replace_items( $search_and_replace_list_items );
	}

	/**
	 * Customize functions classes
	 */
	public function customize_functions_classes() {
		$template_builder_search_items = [
			[
				'search'  => $this->settings->old_plugin_name_const_prefix,
				'replace' => $this->settings->new_plugin_name_const_prefix,
			],
		];
		$template_builder_search_items = array_merge( $this->settings->general_search_items, $template_builder_search_items );

		$woocommerce_check_search_items = [
			[
				'search'  => $this->settings->old_plugin_name_method_prefix,
				'replace' => $this->settings->new_plugin_name_method_prefix,
			],
		];
		$woocommerce_check_search_items = array_merge( $this->settings->general_search_items, $woocommerce_check_search_items );

		$search_and_replace_list_items = [
			[
				'file_name'    => $this->settings->new_functions_files_full_path . 'class-activation-issue.php',
				'search_items' => $this->settings->general_search_items,
			],
			[
				'file_name'    => $this->settings->new_functions_files_full_path . 'class-check-type.php',
				'search_items' => $this->settings->general_search_items,
			],
			[
				'file_name'    => $this->settings->new_functions_files_full_path . 'class-check-woocommerce.php',
				'search_items' => $woocommerce_check_search_items,
			],
			[
				'file_name'    => $this->settings->new_functions_files_full_path . 'class-current-user.php',
				'search_items' => $template_builder_search_items,
			],
			[
				'file_name'    => $this->settings->new_functions_files_full_path . 'class-date.php',
				'search_items' => $this->settings->general_search_items,
			],
			[
				'file_name'    => $this->settings->new_functions_files_full_path . 'class-init-functions.php',
				'search_items' => $this->settings->general_search_items,
			],
			[
				'file_name'    => $this->settings->new_functions_files_full_path . 'class-log-in-footer.php',
				'search_items' => $this->settings->general_search_items,
			],
			[
				'file_name'    => $this->settings->new_functions_files_full_path . 'class-logger.php',
				'search_items' => $this->settings->general_search_items,
			],
			[
				'file_name'    => $this->settings->new_functions_files_full_path . 'class-template-builder.php',
				'search_items' => $template_builder_search_items,
			],
			[
				'file_name'    => $this->settings->new_functions_files_full_path . 'class-utility.php',
				'search_items' => $template_builder_search_items,
			],
		];
		$this->do_repeated_search_and_replace_items( $search_and_replace_list_items );
	}

	/**
	 * Customize hooks classes
	 */
	public function customize_hooks_classes() {
		$search_and_replace_list_items = [
			[
				'file_name'    => $this->settings->new_hooks_files_full_path . 'filters/class-custom-cron-schedule.php',
				'search_items' => $this->settings->general_search_items,
			],
		];
		$this->do_repeated_search_and_replace_items( $search_and_replace_list_items );
	}

	/**
	 * Customize init classes in plugin
	 */
	public function customize_init_classes() {
		$activator_search_items        = [
			[
				'search'  => 'PLUGIN_NAME_LOGS',
				'replace' => $this->settings->new_plugin_name_const_prefix . '_LOGS',
			],
			[
				'search'  => $this->settings->old_plugin_name_method_prefix,
				'replace' => $this->settings->new_plugin_name_method_prefix,
			],
		];
		$activator_search_items        = array_merge( $this->settings->general_search_items, $activator_search_items );
		$admin_hook_search_items       = [
			[
				'search'  => 'PLUGIN_NAME_ADMIN_CSS',
				'replace' => $this->settings->new_plugin_name_const_prefix . '_ADMIN_CSS',
			],
			[
				'search'  => 'PLUGIN_NAME_ADMIN_JS',
				'replace' => $this->settings->new_plugin_name_const_prefix . '_ADMIN_JS',
			],
			[
				'search'  => 'plugin-name-',
				'replace' => $this->settings->new_small_name_with_dash . '-',
			],
		];
		$admin_hook_search_items       = array_merge( $this->settings->general_search_items, $admin_hook_search_items );
		$public_hook_search_items      = [
			[
				'search'  => 'PLUGIN_NAME_CSS',
				'replace' => $this->settings->new_plugin_name_const_prefix . '_CSS',
			],
			[
				'search'  => 'PLUGIN_NAME_JS',
				'replace' => $this->settings->new_plugin_name_const_prefix . '_JS',
			],
			[
				'search'  => 'plugin-name-',
				'replace' => $this->settings->new_small_name_with_dash . '-',
			],
		];
		$public_hook_search_items      = array_merge( $this->settings->general_search_items, $public_hook_search_items );
		$constant_search_items         = [
			[
				'search'  => $this->settings->old_plugin_name_main_name_const,
				'replace' => $this->settings->new_plugin_name_main_name_const,
			],
			[
				'search'  => 'PLUGIN_NAME_',
				'replace' => $this->settings->new_plugin_name_const_prefix . '_',
			],
			[
				'search'  => 'plugin-name',
				'replace' => $this->settings->new_small_name_with_dash,
			],
		];
		$constant_search_items         = array_merge( $this->settings->general_search_items, $constant_search_items );
		$i18n_search_items             = [
			[
				'search'  => $this->settings->old_plugin_name_const_prefix . '_TEXTDOMAIN',
				'replace' => $this->settings->new_plugin_name_const_prefix . '_TEXTDOMAIN',
			],
		];
		$i18n_search_items             = array_merge( $this->settings->general_search_items, $i18n_search_items );
		$search_and_replace_list_items = [
			[
				'file_name'    => $this->settings->new_init_files_full_path . 'class-activator.php',
				'search_items' => $activator_search_items,
			],
			[
				'file_name'    => $this->settings->new_init_files_full_path . 'class-admin-hook.php',
				'search_items' => $admin_hook_search_items,
			],
			[
				'file_name'    => $this->settings->new_init_files_full_path . 'class-constant.php',
				'search_items' => $constant_search_items,
			],
			[
				'file_name'    => $this->settings->new_init_files_full_path . 'class-core.php',
				'search_items' => $constant_search_items,
			],
			[
				'file_name'    => $this->settings->new_init_files_full_path . 'class-i18n.php',
				'search_items' => $i18n_search_items,
			],
			[
				'file_name'    => $this->settings->new_init_files_full_path . 'class-loader.php',
				'search_items' => $this->settings->general_search_items,
			],
			[
				'file_name'    => $this->settings->new_init_files_full_path . 'class-public-hook.php',
				'search_items' => $public_hook_search_items,
			],
			[
				'file_name'    => $this->settings->new_init_files_full_path . 'class-router.php',
				'search_items' => $this->settings->general_search_items,
			],
		];
		$this->do_repeated_search_and_replace_items( $search_and_replace_list_items );
	}

	/**
	 * Customize pagehandlers classes in plugin
	 */
	public function customize_page_handlers_classes() {
		$search_and_replace_list_items = [
			[
				'file_name'    => $this->settings->new_pagehandlers_files_full_path . 'contracts/class-page-handler.php',
				'search_items' => $this->settings->general_search_items,
			],
			[
				'file_name'    => $this->settings->new_pagehandlers_files_full_path . 'class-first-page-handler.php',
				'search_items' => $this->settings->general_search_items,
			],
			[
				'file_name'    => $this->settings->new_pagehandlers_files_full_path . 'class-second-page-handler.php',
				'search_items' => $this->settings->general_search_items,
			],
		];
		$this->do_repeated_search_and_replace_items( $search_and_replace_list_items );
	}

	/**
	 * Customize parts classes in plugin
	 */
	public function customize_parts_classes() {
		$complete_shortcode_search_items = [
			[
				'search'  => $this->settings->old_plugin_name_const_prefix . '_TEXTDOMAIN',
				'replace' => $this->settings->new_plugin_name_const_prefix . '_TEXTDOMAIN',
			],
		];
		$complete_shortcode_search_items = array_merge( $this->settings->general_search_items, $complete_shortcode_search_items );
		$search_and_replace_list_items   = [
			[
				'file_name'    => $this->settings->new_parts_files_full_path . 'custom-posts/class-custom-post1.php',
				'search_items' => $this->settings->general_search_items,
			],
			[
				'file_name'    => $this->settings->new_parts_files_full_path . 'custom-taxonomies/class-custom-taxonomy1.php',
				'search_items' => $this->settings->general_search_items,
			],
			[
				'file_name'    => $this->settings->new_parts_files_full_path . 'other/class-remove-post-column.php',
				'search_items' => $this->settings->general_search_items,
			],
			[
				'file_name'    => $this->settings->new_parts_files_full_path . 'shortcodes/class-complete-shortcode.php',
				'search_items' => $complete_shortcode_search_items,
			],
			[
				'file_name'    => $this->settings->new_parts_files_full_path . 'shortcodes/class-content-for-login-user-shortcode.php',
				'search_items' => $complete_shortcode_search_items,
			],
			[
				'file_name'    => $this->settings->new_parts_files_full_path . 'shortcodes/class-shortcode1.php',
				'search_items' => $this->settings->general_search_items,
			],
		];
		$this->do_repeated_search_and_replace_items( $search_and_replace_list_items );
	}

	/**
	 * Customize uninstall classes in plugin
	 */
	public function customize_uninstall_classes() {
		$deactivator_class_search_items = [
			[
				'search'  => 'plugin_name_prefix',
				'replace' => $this->settings->new_plugin_name_method_prefix,
			],
			[
				'search'  => $this->settings->old_plugin_name_const_prefix,
				'replace' => $this->settings->new_plugin_name_const_prefix,
			],
		];
		$deactivator_class_search_items = array_merge( $this->settings->general_search_items, $deactivator_class_search_items );
		$search_and_replace_list_items  = [
			[
				'file_name'    => $this->settings->new_uninstall_files_full_path . 'class-deactivator.php',
				'search_items' => $deactivator_class_search_items,
			],
			[
				'file_name'    => $this->settings->new_uninstall_files_full_path . 'class-uninstall.php',
				'search_items' => $this->settings->general_search_items,
			],

		];
		$this->do_repeated_search_and_replace_items( $search_and_replace_list_items );
	}

	/**
	 * Customize autoloader class in plugin
	 */
	public function customize_autoloader_class() {
		$result = $this->file_process->do_search_and_replace(
			$this->settings->new_includes_files_full_path . 'class-autoloader.php',
			$this->settings->general_search_items
		);

		$this->file_process->append( $result ['message'], $this->settings->main_log_file );
		$this->file_process->append_section_separator( $this->settings->main_log_file );
	}

	/**
	 * Customize templates classes in plugin
	 */
	public function customize_templates_classes() {
		$footer_search_items = [
			[
				'search'  => $this->settings->old_plugin_name_main_name_const . '_JS',
				'replace' => $this->settings->new_plugin_name_main_name_const . '_JS',
			],
		];

		$header_search_items = [
			[
				'search'  => $this->settings->old_plugin_name_main_name_const . '_CSS',
				'replace' => $this->settings->new_plugin_name_main_name_const . '_CSS',
			],
			[
				'search'  => $this->settings->old_namespace,
				'replace' => $this->settings->new_namespace,
			],
		];
		$menu_search_items   = [
			[
				'search'  => 'pluginprefix',
				'replace' => $this->settings->new_small_name_with_dash,
			],
		];

		$first_page_search_items = array_merge( $this->settings->general_search_items, $menu_search_items );

		$simple_option_page1_search_items = [
			[
				'search'  => $this->settings->old_plugin_name_method_prefix,
				'replace' => $this->settings->new_plugin_name_method_prefix,
			],
			[
				'search'  => $this->settings->old_small_name_with_dash,
				'replace' => $this->settings->new_small_name_with_dash,
			],
		];

		$simple_option_page1_search_items = array_merge($this->settings->general_search_items, $simple_option_page1_search_items);


		$search_and_replace_list_items = [
			[
				'file_name'    => $this->settings->new_templates_files_full_path . 'admin/options-page/option-page1.php',
				'search_items' => $this->settings->general_search_items,
			],
			[
				'file_name'    => $this->settings->new_templates_files_full_path . 'admin/options-page/sample-option-page3.php',
				'search_items' => $this->settings->general_search_items,
			],
			[
				'file_name'    => $this->settings->new_templates_files_full_path . 'admin/options-page/simple-option-page1.php',
				'search_items' => $simple_option_page1_search_items,
			],
			[
				'file_name'    => $this->settings->new_templates_files_full_path . 'admin/plugin-page/dashboard-widgets.php',
				'search_items' => $this->settings->general_search_items,
			],
			[
				'file_name'    => $this->settings->new_templates_files_full_path . 'admin/plugin-page/primary-section.php',
				'search_items' => $this->settings->general_search_items,
			],
			[
				'file_name'    => $this->settings->new_templates_files_full_path . 'admin/plugin-page/second-section.php',
				'search_items' => $this->settings->general_search_items,
			],
			[
				'file_name'    => $this->settings->new_templates_files_full_path . 'admin/plugin-page/welcome-panel.php',
				'search_items' => $this->settings->general_search_items,
			],
			[
				'file_name'    => $this->settings->new_templates_files_full_path . 'front/footer/first-page-footer.php',
				'search_items' => $footer_search_items,
			],
			[
				'file_name'    => $this->settings->new_templates_files_full_path . 'front/footer/footer.php',
				'search_items' => $footer_search_items,
			],
			[
				'file_name'    => $this->settings->new_templates_files_full_path . 'front/header/first-page-head.php',
				'search_items' => $header_search_items,
			],
			[
				'file_name'    => $this->settings->new_templates_files_full_path . 'front/header/head.php',
				'search_items' => $header_search_items,
			],
			[
				'file_name'    => $this->settings->new_templates_files_full_path . 'front/header/menu.php',
				'search_items' => $menu_search_items,
			],
			[
				'file_name'    => $this->settings->new_templates_files_full_path . 'front/first-page-sample.php',
				'search_items' => $first_page_search_items,
			],
			[
				'file_name'    => $this->settings->new_templates_files_full_path . 'front/second-page-sample.php',
				'search_items' => $first_page_search_items,
			],

		];
		$this->do_repeated_search_and_replace_items( $search_and_replace_list_items );
	}

	/**
	 * @param $property
	 *
	 * @return mixed
	 */
	public function __get( $property ) {
		return $this->$property;
	}

	/**
	 * @param $name
	 * @param $value
	 */
	public function __set( $name, $value ) {
		$this->$name = $value;
	}


}