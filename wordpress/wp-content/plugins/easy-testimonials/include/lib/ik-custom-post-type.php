<?php 
/* 
This file is part of Easy Testimonials. 

Easy Testimonials is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

Easy Testimonials is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Easy Testimonials.  If not, see <http://www.gnu.org/licenses/>.
*/
class ikTestimonialsCustomPostType
{
	var $customFields = false;
	var $customPostTypeName = 'custompost';
	var $customPostTypeSingular = 'customPost';
	var $customPostTypePlural = 'customPosts';
	var $prefix = '_ikcf_';

	function __construct($postType, $customFields = false, $removeDefaultCustomFields = false)
	{
		$this->setupCustomPostType($postType);
		
		if ($customFields)
		{
			$this->setupCustomFields($customFields);
		}				
	}
	
	function setupCustomPostType($postType)
	{
		//RWG: make the singular and plural versions of "Testimonials" translatable
		$singular = __("Testimonial",'easy-testimonials');//ucwords($postType['name']);
		$plural = __("Testimonials",'easy-testimonials');//isset($postType['plural']) ? ucwords($postType['plural']) : $singular . 's';
		$exclude_from_search = isset($postType['exclude_from_search']) ? $postType['exclude_from_search'] : false;
		$default_supports = array('title','editor','author','thumbnail','excerpt','comments','custom-fields');		
		$supports = isset( $postType['supports'] )
					? $postType['supports']
					: $default_supports;
					
		
		$this->customPostTypeName = 'testimonial';//RWG: DO NOT TRANLSATE THIS STRING, ELSE YOU RISK THE PREVIOUSLY ENTERED TESTIMONIALS DISAPPEARING!
		$this->customPostTypeSingular = $singular;
		$this->customPostTypePlural = $plural;

		if ($this->customPostTypeName != 'post' && $this->customPostTypeName != 'page')
		{		
			$labels = array
			(
				'name' => __($plural, 'easy-testimonials'),
				'singular_name' => __($singular, 'easy-testimonials'),
				'add_new' => __('Add New Testimonial', 'easy-testimonials'),
				'add_new_item' => __('Add New Testimonial', 'easy-testimonials'),
				'edit_item' => __('Edit Testimonial', 'easy-testimonials'),
				'new_item' => __('New Testimonial', 'easy-testimonials'),
				'view_item' => __('View Testimonial', 'easy-testimonials'),
				'search_items' => __('Search Testimonial', 'easy-testimonials'),
				'not_found' =>  __('No testimonials found', 'easy-testimonials'),
				'not_found_in_trash' => __('No testimonials found in Trash', 'easy-testimonials'), 
				'parent_item_colon' => ''
			);

			$args = array(
				'labels' => $labels,
				'public' => true,
				'publicly_queryable' => true,
				'exclude_from_search' => $exclude_from_search,
				'show_ui' => true, 
				'query_var' => true,
				'rewrite' => true,
				'capability_type' => 'post',
				'hierarchical' => false,
				'supports' => $supports,
				'menu_icon' => 'dashicons-testimonial',//RWG: added this for Easy Testimonials, specifically
				'show_in_rest' => true,
			); 
			$this->customPostTypeArgs = $args;
	
			$this->registerPostTypes();
		
			//hook functions to change "Post Updated", etc, to relevant CPT naming
			add_filter( 'post_updated_messages', array( &$this, 'add_update_messages' ) );
			add_filter( 'bulk_post_updated_messages', array( &$this, 'add_bulk_update_messages' ), 10, 2 );
		}
	}

	function registerPostTypes()
	{
	  register_post_type($this->customPostTypeName,$this->customPostTypeArgs);
	}
	
	function setupCustomFields($fields)
	{
		$this->customFields = array();
		foreach ($fields as $f)
		{
			$this->customFields[] = array
			(
				"name"			=> $f['name'],
				"title"			=> $f['title'],
				"description"	=> isset($f['description']) ? $f['description'] : '',
				"type"			=> isset($f['type']) ? $f['type'] : "text",
				"scope"			=>	array( $this->customPostTypeName ),
				"capability"	=> "edit_posts"
			);
		}
		// register hooks
		add_action( 'admin_menu', array( &$this, 'createCustomFields' ) );
		add_action( 'save_post', array( &$this, 'saveCustomFields' ), 1, 2 );
	}
		
	/**
	* Create the new Custom Fields meta box
	*/
	function createCustomFields() 
	{
		if ( function_exists( 'add_meta_box' ) ) 
		{
			//add_meta_box( 'my-custom-fields', 'Custom Fields', array( &$this, 'displayCustomFields' ), 'page', 'normal', 'high' );
			//add_meta_box( 'my-custom-fields', 'Custom Fields', array( &$this, 'displayCustomFields' ), 'post', 'normal', 'high' );
			add_meta_box( 'my-custom-fields'.md5(serialize($this->customFields)), $this->customPostTypeSingular . ' Information', array( &$this, 'displayCustomFields' ), $this->customPostTypeName, 'normal', 'high' );//RWG
		}
	}

	/**
	* Display the new Custom Fields meta box
	*/
	function displayCustomFields() {
		global $post;
		?>
		<div class="form-wrap">
			<?php
			wp_nonce_field( 'my-custom-fields', 'my-custom-fields_wpnonce', false, true );
			foreach ( $this->customFields as $customField ) {
				// Check scope
				$scope = $customField[ 'scope' ];
				$output = false;
				foreach ( $scope as $scopeItem ) {
					switch ( $scopeItem ) {
						case "post": {
							// Output on any post screen
							if ( basename( $_SERVER['SCRIPT_FILENAME'] )=="post-new.php" || $post->post_type=="post" )
								$output = true;
							break;
						}
						case "page": {
							// Output on any page screen
							if ( basename( $_SERVER['SCRIPT_FILENAME'] )=="page-new.php" || $post->post_type=="page" )
								$output = true;
							break;
						}
						default:{//RWG
							if ($post->post_type==$scopeItem )
								$output = true;
							break;
						}
					}
					if ( $output ) break;
				}
				// Check capability
				if ( !current_user_can( $customField['capability'], $post->ID ) )
					$output = false;
				// Output if allowed
				if ( $output ) { ?>
					<div class="form-field form-required">
						<?php
						switch ( $customField[ 'type' ] ) {
							case "checkbox": {
								// Checkbox
								echo '<label for="' . $this->prefix . $customField[ 'name' ] .'" style="display:inline;"><b>' . $customField[ 'title' ] . '</b></label>&nbsp;&nbsp;';
								echo '<input type="checkbox" name="' . $this->prefix . $customField['name'] . '" id="' . $this->prefix . $customField['name'] . '" value="yes"';
								if ( get_post_meta( $post->ID, $this->prefix . $customField['name'], true ) == "yes" )
									echo ' checked="checked"';
								echo '" style="width: auto;" />';
								break;
							}
							case "textarea": {
								// Text area
								echo '<label for="' . $this->prefix . $customField[ 'name' ] .'"><b>' . $customField[ 'title' ] . '</b></label>';
								echo '<textarea name="' . $this->prefix . $customField[ 'name' ] . '" id="' . $this->prefix . $customField[ 'name' ] . '" columns="30" rows="3">' . htmlspecialchars( get_post_meta( $post->ID, $this->prefix . $customField[ 'name' ], true ) ) . '</textarea>';
								break;
							}
							case "number": {
								$min = !empty($customField[ 'min' ]) ? sprintf( 'min="%s"',$customField['min'] ) : 'min="1"';
								$max = !empty($customField[ 'max' ]) ? sprintf( 'max="%s"',$customField['max'] ) : 'max="5"';
								
								
								// HTML5 Number
								echo '<label for="' . $this->prefix . $customField[ 'name' ] .'"><b>' . $customField[ 'title' ] . '</b></label>';
								echo '<input type="number" '.$min.' '.$max.' name="' . $this->prefix . $customField[ 'name' ] . '" id="' . $this->prefix . $customField[ 'name' ] . '" value="' . htmlspecialchars( get_post_meta( $post->ID, $this->prefix . $customField[ 'name' ], true ) ) . '" />';
								break;
							}
							default: {
								// Plain text field
								echo '<label for="' . $this->prefix . $customField[ 'name' ] .'"><b>' . $customField[ 'title' ] . '</b></label>';
								echo '<input type="text" name="' . $this->prefix . $customField[ 'name' ] . '" id="' . $this->prefix . $customField[ 'name' ] . '" value="' . htmlspecialchars( get_post_meta( $post->ID, $this->prefix . $customField[ 'name' ], true ) ) . '" />';
								break;
							}
						}
						?>
						<?php if ( $customField[ 'description' ] ) echo '<p>' . $customField[ 'description' ] . '</p>'; ?>
					</div>
				<?php
				}
			} ?>
		</div>
		<?php
	}

	/**
	* Save the new Custom Fields values
	*/
	function saveCustomFields( $post_id, $post ) {
		//idea from here: http://wordpress.stackexchange.com/questions/37967/custom-field-being-erased-after-autosave
		//don't udpate custom fields on quickedit screen so that they aren't erased.
		if ((defined('DOING_AJAX') && DOING_AJAX) || isset($_REQUEST['bulk_edit'])){
			return;
		}
	
		//RWG: 1.30.14 - added isset($_POST[ 'my-custom-fields_wpnonce' ]) to prevent undefined index notices on new item creation
		if ( isset($_POST[ 'my-custom-fields_wpnonce' ]) && !wp_verify_nonce( $_POST[ 'my-custom-fields_wpnonce' ], 'my-custom-fields' ) )
			return;
		if ( !current_user_can( 'edit_post', $post_id ) )
			return;
		//if ( $post->post_type != 'page' && $post->post_type != 'post')//RWG
		//	return;
		foreach ( $this->customFields as $customField ) {
			if ( current_user_can( $customField['capability'], $post_id ) ) {
				if ( isset( $_POST[ $this->prefix . $customField['name'] ] ) && trim( $_POST[ $this->prefix . $customField['name'] ] ) ) {
					update_post_meta( $post_id, $this->prefix . $customField[ 'name' ], $_POST[ $this->prefix . $customField['name'] ] );
				} else {
					delete_post_meta( $post_id, $this->prefix . $customField[ 'name' ] );
				}
			}
		}
	}
		
	/**
	 * Add customized update messages for the custom post type. This way WP
	 * will say e.g., "Custom Post Type updated. View custom post type."
	 * instead of "Post updated. View post".
	 *
	 * See https://codex.wordpress.org/Function_Reference/register_post_type
	 *
	 * @param array $messages Existing post update messages.
	 *
	 * @return array Updated list with our messages added
	 */
	function add_update_messages( $messages )
	{
		$post             = get_post();
		$post_type        = get_post_type( $post );
		$post_type_object = get_post_type_object( $post_type );
		$textdomain = $this->customPostTypeName; // TODO: pass this in as an option

		$messages[ $this->customPostTypeName ] = array(
			0  => '', // Unused. Messages start at index 1.
			1  => $this->customPostTypeSingular . __( ' updated.', $textdomain ),
			2  => __( 'Custom field updated.', $textdomain ),
			3  => __( 'Custom field deleted.', $textdomain ),
			4  => $this->customPostTypeSingular . __( ' updated.', $textdomain ),
			/* translators: %s: date and time of the revision */
			5  => isset( $_GET['revision'] ) ? sprintf( $this->customPostTypeSingular . __( ' restored to revision from %s', $textdomain ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6  => $this->customPostTypeSingular . __( ' published.', $textdomain ),
			7  => $this->customPostTypeSingular . __( ' saved.', $textdomain ),
			8  => $this->customPostTypeSingular . __( ' submitted.', $textdomain ),
			9  => sprintf(
				$this->customPostTypeSingular . __( ' scheduled for: <strong>%1$s</strong>.', $textdomain ),
				// translators: Publish box date format, see http://php.net/date
				date_i18n( __( 'M j, Y @ G:i', $textdomain ), strtotime( $post->post_date ) )
			),
			10 => $this->customPostTypeSingular . __( ' draft updated.', $textdomain )
		);

		// Append "View Custom Post Type" links to the end of some messages
		// if we are currently viewing viewing an obkect of this Post Type
		if ( $post_type_object->publicly_queryable && ($this->customPostTypeName === $post_type) ) {
			$permalink = get_permalink( $post->ID );

			$view_link = sprintf( ' <a href="%s">%s</a>', esc_url( $permalink ), __( 'View ', $textdomain ) . strtolower($this->customPostTypeSingular) );
			$messages[ $post_type ][1] .= $view_link;
			$messages[ $post_type ][6] .= $view_link;
			$messages[ $post_type ][9] .= $view_link;

			$preview_permalink = add_query_arg( 'preview', 'true', $permalink );
			$preview_link = sprintf( ' <a target="_blank" href="%s">%s</a>', esc_url( $preview_permalink ), __( 'Preview ', $textdomain ) . strtolower($this->customPostTypeSingular) );
			$messages[ $post_type ][8]  .= $preview_link;
			$messages[ $post_type ][10] .= $preview_link;
		}

		return $messages;
	}
	
	/**
	 * Add customized update messages for bulk actions applied to the custom
	 * post type (e.g., post(s) moved to Trash). This way WP will say e.g.,
	 * "1 Custom Post Type moved to the trash.", instead of "1 post moved to
	 * the trash".
	 *
	 * See https://codex.wordpress.org/Plugin_API/Filter_Reference/bulk_post_updated_messages
	 *
	 * @param array $bulk_messages Existing bulk update messages.
	 * @param array $bulk_counts The number of posts with each new status
	 *							 ('trashed', 'updated', etc)
	 *
	 * @return array Updated list with our bulk messages added
	 */
	function add_bulk_update_messages( $bulk_messages, $bulk_counts )
	{
		$singular = strtolower($this->customPostTypeSingular);
		$plural = strtolower($this->customPostTypePlural);
		$bulk_messages[ $this->customPostTypeName ] = array(
			'updated'   => _n( '%s ' . $singular . ' updated.', '%s ' . $plural . ' updated.', $bulk_counts['updated'] ),
			'locked'    => _n( '%s ' . $singular . ' not updated, somebody is editing it.', '%s ' . $plural . ' not updated, somebody is editing them.', $bulk_counts['locked'] ),
			'deleted'   => _n( '%s ' . $singular . ' permanently deleted.', '%s ' . $plural . ' permanently deleted.', $bulk_counts['deleted'] ),
			'trashed'   => _n( '%s ' . $singular . ' moved to the Trash.', '%s ' . $plural . ' moved to the Trash.', $bulk_counts['trashed'] ),
			'untrashed' => _n( '%s ' . $singular . ' restored from the Trash.', '%s ' . $plural . ' restored from the Trash.', $bulk_counts['untrashed'] ),
		);	
		return $bulk_messages;
	}
}