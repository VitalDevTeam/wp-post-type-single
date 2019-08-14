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

class VTL_Widget {

	private $post_type_name;
	private $post_type_slug;
	private $post_type_singular;
	private $post_type_plural;

	public function __construct() {
		$this->post_type_name = 'vtl_widget';
		$this->post_type_slug = 'widget';
		$this->post_type_singular = 'Widget';
		$this->post_type_plural = 'Widgets';

		add_action('init', [$this, 'register_post_type'], 0);
		add_action('init', [$this, 'register_widget_type_taxonomy'], 0);
		add_filter('post_updated_messages', [$this, 'updated_messages']);
		add_filter('bulk_post_updated_messages', [$this, 'bulk_updated_messages'], 10, 2);
		add_filter('enter_title_here', [$this, 'enter_title_here'], 10, 2);
	}

	/**
	 * Registers widget post type
	 * @return void
	 */
	public function register_post_type() {

		register_post_type($this->post_type_slug, [
			'label'               => $this->post_type_singular,
			'description'         => $this->post_type_singular,
			'labels'              => [
				'name'                  => $this->post_type_plural,
				'singular_name'         => $this->post_type_singular,
				'menu_name'             => $this->post_type_plural,
				'name_admin_bar'        => $this->post_type_singular,
				'parent_item_colon'     => sprintf('Parent %s:', $this->post_type_singular),
				'all_items'             => sprintf('All %s', $this->post_type_plural),
				'add_new'               => 'Add New',
				'add_new_item'          => sprintf('Add New %s', $this->post_type_singular),
				'new_item'              => sprintf('New %s', $this->post_type_singular),
				'edit_item'             => sprintf('Edit %s', $this->post_type_singular),
				'update_item'           => sprintf('Update %s', $this->post_type_singular),
				'view_item'             => sprintf('View %s', $this->post_type_singular),
				'view_items'            => sprintf('View %s', $this->post_type_plural),
				'search_items'          => sprintf('Search %s', $this->post_type_plural),
				'not_found'             => sprintf('No %s found', strtolower($this->post_type_plural)),
				'not_found_in_trash'    => sprintf('No %s found in Trash', strtolower($this->post_type_plural)),
				'items_list'            => sprintf('%s list', $this->post_type_plural),
				'items_list_navigation' => sprintf('%s list navigation', $this->post_type_plural),
				'archives'              => sprintf('%s Archives', $this->post_type_singular),
				'attributes'            => sprintf('%s Attributes', $this->post_type_singular),
				'insert_into_item'      => sprintf('Insert into %s', strtolower($this->post_type_singular)),
				'uploaded_to_this_item' => sprintf('Uploaded to this %s', strtolower($this->post_type_singular)),
				'featured_image'        => 'Featured Image',
				'set_featured_image'    => 'Set featured image',
				'remove_featured_image' => 'Remove featured image',
				'use_featured_image'    => 'Use featured image',
				'filter_items_list'     => sprintf('Filter %s list', strtolower($this->post_type_plural)),
			],
			'supports'            => ['title', 'editor', 'thumbnail'],
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_icon'           => 'dashicons-star-filled',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'show_in_rest'        => false,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'delete_with_user'    => null,
			'rewrite'             => [
				'slug'       => $this->post_type_slug,
				'with_front' => false,
				'pages'      => true,
				'feeds'      => true,
			],
			'capability_type'     => 'post',
		]);
	}

	/**
	 * Registers widget type taxonomy
	 * @return void
	 */
	public function register_widget_type_taxonomy() {
		$tax_name = 'widget_type';
		$tax_slug = 'widget-type';
		$term_singular = 'Type';
		$term_plural = 'Types';

		register_taxonomy(
			$tax_name,
			[$this->post_type_slug],
			[
				'labels'            => [
					'name'                       => $term_plural,
					'singular_name'              => $term_plural,
					'menu_name'                  => $term_plural,
					'all_items'                  => sprintf('All %s', $term_plural),
					'parent_item'                => sprintf('Parent %s', $term_singular),
					'parent_item_colon'          => sprintf('Parent %s:', $term_singular),
					'new_item_name'              => sprintf('New %s Name', $term_singular),
					'add_new_item'               => sprintf('Add New %s', $term_singular),
					'edit_item'                  => sprintf('Edit %s', $term_singular),
					'update_item'                => sprintf('Update %s', $term_singular),
					'view_item'                  => sprintf('View %s', $term_singular),
					'separate_items_with_commas' => sprintf('Separate %s with commas', strtolower($term_plural)),
					'add_or_remove_items'        => sprintf('Add or remove %s', strtolower($term_plural)),
					'choose_from_most_used'      => 'Choose from the most used',
					'popular_items'              => sprintf('Popular %s', $term_plural),
					'search_items'               => sprintf('Search %s', $term_plural),
					'not_found'                  => 'Not Found',
					'items_list'                 => sprintf('%s list', $term_plural),
					'items_list_navigation'      => sprintf('%s list navigation', $term_plural),
				],
				'hierarchical'      => true,
				'public'            => true,
				'show_ui'           => true,
				'show_admin_column' => true,
				'show_in_nav_menus' => true,
				'show_tagcloud'     => true,
				'rewrite'           => [
					'slug'         => $tax_slug,
					'with_front'   => false,
					'hierarchical' => false,
				],
			]
		);
	}

	/**
	 * Set up admin messages for post type
	 * @param  array $messages Default message
	 * @return array           Modified messages
	 */
	public function updated_messages($messages) {
		global $post;
		$post_type = get_post_type($post);
		$post_type_object = get_post_type_object($post_type);

		$messages[$this->post_type_slug] = [
			0  => '',
			1  => sprintf('%s updated.', $this->post_type_singular),
			2  => 'Custom field updated.',
			3  => 'Custom field deleted.',
			4  => sprintf('%s updated.', $this->post_type_singular),
			5  => isset($_GET['revision']) ? sprintf('%s restored to revision from %s', $this->post_type_singular, wp_post_revision_title( (int) $_GET['revision'], false)) : false,
			6  => sprintf('%s published.', $this->post_type_singular),
			7  => sprintf('%s saved.', $this->post_type_singular),
			8  => sprintf('%s submitted.', $this->post_type_singular),
			9  => sprintf('%s scheduled for: <strong>%1$s</strong>.', $this->post_type_singular, date_i18n('M j, Y @ G:i', strtotime($post->post_date))),
			10 => sprintf('%s draft updated.', $this->post_type_singular),
		];

		if ($post_type_object->publicly_queryable & $post->post_type === $this->post_type_slug) {
			$permalink = get_permalink($post->ID);
			$view_link = sprintf(' <a href="%s">View %s</a>', esc_url($permalink), $this->post_type_singular);
			$messages[ $post_type ][1] .= $view_link;
			$messages[ $post_type ][6] .= $view_link;
			$messages[ $post_type ][9] .= $view_link;
			$preview_permalink = add_query_arg('preview', 'true', $permalink);
			$preview_link = sprintf(' <a target="_blank" href="%s">Preview %s</a>', esc_url($preview_permalink), $this->post_type_singular);
			$messages[ $post_type ][8]  .= $preview_link;
			$messages[ $post_type ][10] .= $preview_link;
		}

		return $messages;
	}

	/**
	 * Set up bulk admin messages for post type
	 * @param  array  $bulk_messages Default bulk messages
	 * @param  array  $bulk_counts   Counts of selected posts in each status
	 * @return array                Modified messages
	 */
	public function bulk_updated_messages($bulk_messages = [], $bulk_counts = []) {
		$bulk_messages[$this->post_type_slug] = [
			'updated'   => sprintf(_n('%1$s %2$s updated.', '%1$s %3$s updated.', $bulk_counts['updated']), $bulk_counts['updated'], strtolower($this->post_type_singular), strtolower($this->post_type_plural)),
			'locked'    => sprintf(_n('%1$s %2$s not updated, somebody is editing it.', '%1$s %3$s not updated, somebody is editing them.', $bulk_counts['locked']), $bulk_counts['locked'], strtolower($this->post_type_singular), strtolower($this->post_type_plural)),
			'deleted'   => sprintf(_n('%1$s %2$s permanently deleted.', '%1$s %3$s permanently deleted.', $bulk_counts['deleted']), $bulk_counts['deleted'], strtolower($this->post_type_singular), strtolower($this->post_type_plural)),
			'trashed'   => sprintf(_n('%1$s %2$s moved to the Trash.', '%1$s %3$s moved to the Trash.', $bulk_counts['trashed']), $bulk_counts['trashed'], strtolower($this->post_type_singular), strtolower($this->post_type_plural)),
			'untrashed' => sprintf(_n('%1$s %2$s restored from the Trash.', '%1$s %3$s restored from the Trash.', $bulk_counts['untrashed']), $bulk_counts['untrashed'], strtolower($this->post_type_singular), strtolower($this->post_type_plural)),
		];

		return $bulk_messages;
	}

	/**
	 * Customizes title placeholder text
	 *
	 * @param string $title Placeholder text
	 * @param WP_Post $post Post object
	 * @return string Modified placeholder text
	 */
	public function enter_title_here($title, $post) {
		$post_type = get_post_type($post);
		if ($this->post_type_slug === $post_type) {
			$title = sprintf('Enter %s name here', strtolower($this->post_type_singular));
		}
		return $title;
	}
}

new VTL_Widget();
