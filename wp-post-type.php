<?php
/*
	Plugin Name: Post Type (Description)
	Plugin URI: https://vtldesign.com
	Description: A custom post type for {description}
	Version: 2.5
	Author: Vital Dev Team
	Author URI: https://vtldesign.com
	License: GPL2
*/

if (! defined('ABSPATH')) {
	exit;
}

/**
 * Custom post type class.
 *
 * @since 1.0.0
 */
class VTL_Widget {

	/**
	 * The post type name.
	 * @var    string
	 * @access public
	 * @since  1.0.0
	 */
	public $post_type_name;

	/**
	 * The post type base.
	 * @var    string
	 * @access public
	 * @since  1.0.0
	 */
	public $post_type_base;

	/**
	 * The post type singular name.
	 * @var    string
	 * @access public
	 * @since  1.0.0
	 */
	public $post_type_singular;

	/**
	 * The post type plural name.
	 * @var    string
	 * @access public
	 * @since  1.0.0
	 */
	public $post_type_plural;

	public function __construct() {
		$this->post_type_name = 'vtl_widget';
		$this->post_type_base = 'widget';
		$this->post_type_singular = 'Widget';
		$this->post_type_plural = 'Widgets';

		add_action('init', [$this, 'register_post_type'], 0);
		add_action('init', [$this, 'register_widget_type_taxonomy'], 0);
		add_filter('enter_title_here', [$this, 'enter_title_here'], 10, 2);
	}

	/**
	 * Registers the post type.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function register_post_type() {

		register_post_type($this->post_type_name, [
			'label'                 => $this->post_type_singular,
			'labels'                => [
				'name'                     => $this->post_type_plural,
				'singular_name'            => $this->post_type_singular,
				'menu_name'                => $this->post_type_plural,
				'name_admin_bar'           => $this->post_type_singular,
				'parent_item_colon'        => sprintf('Parent %s:', $this->post_type_singular),
				'all_items'                => sprintf('All %s', $this->post_type_plural),
				'add_new'                  => 'Add New',
				'add_new_item'             => sprintf('Add New %s', $this->post_type_singular),
				'new_item'                 => sprintf('New %s', $this->post_type_singular),
				'edit_item'                => sprintf('Edit %s', $this->post_type_singular),
				'update_item'              => sprintf('Update %s', $this->post_type_singular),
				'item_published'           => sprintf('%s published.', $this->post_type_singular),
				'item_published_privately' => sprintf('%s published privately.', $this->post_type_singular),
				'item_reverted_to_draft'   => sprintf('%s reverted to draft.', $this->post_type_singular),
				'item_scheduled'           => sprintf('%s scheduled.', $this->post_type_singular),
				'item_updated'             => sprintf('%s updated.', $this->post_type_singular),
				'items_list'               => sprintf('%s list', $this->post_type_plural),
				'items_list_navigation'    => sprintf('%s list navigation', $this->post_type_plural),
				'view_item'                => sprintf('View %s', $this->post_type_singular),
				'view_items'               => sprintf('View %s', $this->post_type_plural),
				'search_items'             => sprintf('Search %s', $this->post_type_plural),
				'not_found'                => sprintf('No %s found', strtolower($this->post_type_plural)),
				'not_found_in_trash'       => sprintf('No %s found in Trash', strtolower($this->post_type_plural)),
				'archives'                 => sprintf('%s Archives', $this->post_type_singular),
				'attributes'               => sprintf('%s Attributes', $this->post_type_singular),
				'insert_into_item'         => sprintf('Insert into %s', strtolower($this->post_type_singular)),
				'uploaded_to_this_item'    => sprintf('Uploaded to this %s', strtolower($this->post_type_singular)),
				'featured_image'           => 'Featured Image',
				'set_featured_image'       => 'Set featured image',
				'remove_featured_image'    => 'Remove featured image',
				'use_featured_image'       => 'Use featured image',
				'filter_items_list'        => sprintf('Filter %s list', strtolower($this->post_type_plural)),
			],
			'description'           => $this->post_type_singular,
			'public'                => true,
			'hierarchical'          => false,
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'show_in_nav_menus'     => true,
			'show_in_admin_bar'     => true,
			'show_in_rest'          => false,
			'rest_base'             => $this->post_type_name,
			'rest_controller_class' => 'WP_REST_Posts_Controller',
			'menu_position'         => null,
			'menu_icon'             => 'dashicons-star-filled',
			'capability_type'       => 'post',
			'map_meta_cap'          => false,
			'supports'              => [
				'title',
				'editor',
				'thumbnail',
			],
			'register_meta_box_cb'  => null,
			'has_archive'           => true,
			'rewrite'               => [
				'slug'       => $this->post_type_base,
				'with_front' => false,
				'feeds'      => true,
				'pages'      => true,
				'ep_mask'    => EP_PERMALINK,
			],
			'can_export'            => true,
			'delete_with_user'      => false,
		]);
	}

	/**
	 * Registers post type taxonomy.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function register_widget_type_taxonomy() {
		$tax_name = 'widget_type';
		$tax_slug = 'widget-type';
		$term_singular = 'Type';
		$term_plural = 'Types';

		register_taxonomy(
			$tax_name,
			$this->post_type_name,
			[
				'labels'                => [
					'name'                       => $term_plural,
					'singular_name'              => $term_plural,
					'menu_name'                  => $term_plural,
					'search_items'               => sprintf('Search %s', $term_plural),
					'popular_items'              => sprintf('Popular %s', $term_plural),
					'all_items'                  => sprintf('All %s', $term_plural),
					'parent_item'                => sprintf('Parent %s', $term_singular),
					'parent_item_colon'          => sprintf('Parent %s:', $term_singular),
					'edit_item'                  => sprintf('Edit %s', $term_singular),
					'view_item'                  => sprintf('View %s', $term_singular),
					'update_item'                => sprintf('Update %s', $term_singular),
					'add_new_item'               => sprintf('Add New %s', $term_singular),
					'new_item_name'              => sprintf('New %s Name', $term_singular),
					'separate_items_with_commas' => sprintf('Separate %s with commas', strtolower($term_plural)),
					'add_or_remove_items'        => sprintf('Add or remove %s', strtolower($term_plural)),
					'choose_from_most_used'      => 'Choose from the most used',
					'not_found'                  => 'Not Found',
					'no_terms'                   => sprintf('No %s', $term_plural),
					'items_list_navigation'      => sprintf('%s list navigation', $term_plural),
					'items_list'                 => sprintf('%s list', $term_plural),
					'most_used'                  => 'Most Used',
					'back_to_items'              => sprintf('Back to %s', $term_plural),
				],
				'description'           => $term_singular,
				'public'                => true,
				'publicly_queryable'    => true,
				'hierarchical'          => true,
				'show_ui'               => true,
				'show_in_menu'          => true,
				'show_in_nav_menus'     => true,
				'show_in_rest'          => true,
				'rest_base'             => $tax_name,
				'rest_controller_class' => 'WP_REST_Terms_Controller',
				'show_tagcloud'         => true,
				'show_in_quick_edit'    => true,
				'show_admin_column'     => true,
				'meta_box_cb'           => null,
				'meta_box_sanitize_cb'  => null,
				'capabilities'          => [
					'manage_terms' => 'manage_categories',
					'edit_terms'   => 'manage_categories',
					'delete_terms' => 'manage_categories',
					'assign_terms' => 'edit_posts',
				],
				'rewrite'               => [
					'slug'         => $tax_slug,
					'with_front'   => false,
					'hierarchical' => false,
					'ep_mask'      => EP_NONE,
				],
			]
		);
	}

	/**
	 * Sets the placeholder text in the post title field.
	 *
	 * @since  1.0.0
	 * @param  string $title Placeholder text. Default 'Add title'.
	 * @param  WP_Post $post Post object.
	 * @return string Filtered placeholder text.
	 */
	public function enter_title_here($title, $post) {
		$post_type = get_post_type($post);
		if ($this->post_type_name === $post_type) {
			$title = sprintf('Enter %s name here', strtolower($this->post_type_singular));
		}
		return $title;
	}
}

new VTL_Widget();
