<?php
/**
 * Set up the default theme options
 *
 * @since 1.0.0
 */
function bavotasan_theme_options() {
	//delete_option( 'arcade_basic_theme_options' );
	$default_theme_options = array(
		'arc' => 400,
		'fittext' => '',
		'header_icon' => 'fa-heart',
		'width' => '1170',
		'layout' => 'right',
		'primary' => 'col-md-8',
		'display_author' => 'on',
		'display_date' => 'on',
		'display_comment_count' => 'on',
		'display_categories' => 'on',
		'jumbo_headline_title' => 'A great big headline to catch some attention',
		'jumbo_headline_text' => 'By ten o\'clock the police organisation, and by midday even the railway organisations, were losing coherency, losing shape and efficiency, guttering, softening, running at last in that swift liquefaction of the social body.',
	);

	return get_option( 'arcade_basic_theme_options', $default_theme_options );
}

if ( class_exists( 'WP_Customize_Control' ) ) {
	class Bavotasan_Textarea_Control extends WP_Customize_Control {
	    public $type = 'textarea';

	    public function render_content() {
	        ?>
	        <label>
	        <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
	        <textarea rows="5" class="custom-textarea" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
	        </label>
	        <?php
	    }
	}

    class Bavotasan_Text_Description_Control extends WP_Customize_Control {
        public $description;

	    public function render_content() {
			?>
			<label>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
                <input type="text" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); ?> />
            </label>
            <p class="description more-top"><?php echo ( $this->description ); ?></p>
			<?php
        }
    }

	class Bavotasan_Icon_Select_Control extends WP_Customize_Control {
		public function render_content() {
			?>
			<div id="widgets-right" class="widget-content">
			<label class="customize-control-select"><span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<span class="custom-icon-container"><i class="fa <?php echo esc_attr( $this->value() ); ?>"></i></span>
				<input type="hidden" class="image-widget-custom-icon" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); ?> />
				<a href="#" class="view-icons"><?php _e( 'View Icons', 'arcade' ); ?></a> | <a href="#" class="delete-icon"><?php _e( 'Remove Icon', 'arcade' ); ?></a>
				<?php bavotasan_font_awesome_icons( false ); ?>
			</label>
			</div>
			<?php
		}
	}

	class Bavotasan_Post_Layout_Control extends WP_Customize_Control {
	    public function render_content() {
			if ( empty( $this->choices ) )
				return;

			$name = '_customize-radio-' . $this->id;

			?>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php
			foreach ( $this->choices as $value => $label ) :
				?>
				<label>
					<input type="radio" value="<?php echo esc_attr( $value ); ?>" name="<?php echo esc_attr( $name ); ?>" <?php $this->link(); checked( $this->value(), $value ); ?> />
					<?php
					$value = ( is_active_sidebar( 'second-sidebar' ) ) ? $value . ' second-sidebar' : $value;
					echo '<div class="' . esc_attr( $value ) . '"></div>'; ?>
				</label>
				<?php
			endforeach;
			echo '<p class="description">' . __( 'Sidebars do not appear on the home page unless you have set a static front page.', 'arcade' ) . '</p>';
	    }
	}
}

class Bavotasan_Customizer {
	public function __construct() {
		add_action( 'customize_register', array( $this, 'customize_register' ) );
		add_action( 'customize_controls_print_styles', array( $this, 'customize_controls_print_styles' ) );
	}

	public function customize_controls_print_styles() {
		wp_enqueue_script( 'bavotasan_image_widget', BAVOTASAN_THEME_URL . '/library/js/admin/image-widget.js', array( 'jquery' ), '', true );
		wp_enqueue_style( 'bavotasan_image_widget_css', BAVOTASAN_THEME_URL . '/library/css/admin/image-widget.css' );
		wp_enqueue_style( 'font_awesome', BAVOTASAN_THEME_URL .'/library/css/font-awesome.css', false, '4.1.0', 'all' );
	}

	/**
	 * Adds theme options to the Customizer screen
	 *
	 * This function is attached to the 'customize_register' action hook.
	 *
	 * @param	class $wp_customize
	 *
	 * @since 1.0.0
	 */
	public function customize_register( $wp_customize ) {
		$bavotasan_theme_options = bavotasan_theme_options();

		$wp_customize->add_setting( 'arcade_basic_theme_options[arc]', array(
			'default' => $bavotasan_theme_options['arc'],
			'type' => 'option',
			'capability' => 'edit_theme_options',
            'sanitize_callback' => 'absint',
		) );

		$wp_customize->add_control( new Bavotasan_Text_Description_Control( $wp_customize, 'arc', array(
			'label' => __( 'Arc Radius', 'arcade' ),
			'section' => 'title_tagline',
			'settings' => 'arcade_basic_theme_options[arc]',
			'description' => __( 'The space and rotation for each letter will be calculated using the arc radius and the width of the site title. Leave blank for no arc.', 'arcade' ),
		) ) );

		$wp_customize->add_setting( 'arcade_basic_theme_options[fittext]', array(
			'default' => $bavotasan_theme_options['fittext'],
			'type' => 'option',
			'capability' => 'edit_theme_options',
            'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
		) );

		$wp_customize->add_control( 'bavotasan_fittext', array(
			'label' => __( 'Use Fittext for long site title', 'arcade' ),
			'section' => 'title_tagline',
			'settings' => 'arcade_basic_theme_options[fittext]',
			'type' => 'checkbox',
		) );

		$wp_customize->add_setting( 'arcade_basic_theme_options[header_icon]', array(
			'default' => $bavotasan_theme_options['header_icon'],
			'type' => 'option',
			'capability' => 'edit_theme_options',
            'sanitize_callback' => 'esc_attr',
		) );

		$wp_customize->add_control( new Bavotasan_Icon_Select_Control( $wp_customize, 'header_icon', array(
			'label' => __( 'Header Icon', 'arcade' ),
			'section' => 'title_tagline',
			'settings' => 'arcade_basic_theme_options[header_icon]',
		) ) );

		// Layout section panel
		$wp_customize->add_section( 'bavotasan_layout', array(
			'title' => __( 'Layout', 'arcade' ),
			'priority' => 35,
		) );

		$wp_customize->add_setting( 'arcade_basic_theme_options[width]', array(
			'default' => $bavotasan_theme_options['width'],
			'type' => 'option',
			'capability' => 'edit_theme_options',
            'sanitize_callback' => 'absint',
		) );

		$wp_customize->add_control( 'bavotasan_width', array(
			'label' => __( 'Site Width', 'arcade' ),
			'section' => 'bavotasan_layout',
			'settings' => 'arcade_basic_theme_options[width]',
			'priority' => 10,
			'type' => 'select',
			'choices' => array(
				'1170' => __( '1200px', 'arcade' ),
				'992' => __( '992px', 'arcade' ),
			),
		) );

		$choices =  array(
			'col-md-2' => '17%',
			'col-md-3' => '25%',
			'col-md-4' => '34%',
			'col-md-5' => '42%',
			'col-md-6' => '50%',
			'col-md-7' => '58%',
			'col-md-8' => '66%',
			'col-md-9' => '75%',
			'col-md-10' => '83%',
			'col-md-12' => '100%',
		);

		$wp_customize->add_setting( 'arcade_basic_theme_options[primary]', array(
			'default' => $bavotasan_theme_options['primary'],
			'type' => 'option',
			'capability' => 'edit_theme_options',
            'sanitize_callback' => 'esc_attr',
		) );

		$wp_customize->add_control( 'bavotasan_primary_column', array(
			'label' => __( 'Main Content Width', 'arcade' ),
			'section' => 'bavotasan_layout',
			'settings' => 'arcade_basic_theme_options[primary]',
			'priority' => 15,
			'type' => 'select',
			'choices' => $choices,
		) );

		$wp_customize->add_setting( 'arcade_basic_theme_options[layout]', array(
			'default' => $bavotasan_theme_options['layout'],
			'type' => 'option',
			'capability' => 'edit_theme_options',
            'sanitize_callback' => 'esc_attr',
		) );

		$layout_choices = array(
			'left' => __( 'Left', 'arcade' ),
			'right' => __( 'Right', 'arcade' ),
		);

		$wp_customize->add_control( new Bavotasan_Post_Layout_Control( $wp_customize, 'layout', array(
			'label' => __( 'Sidebar Layout', 'arcade' ),
			'section' => 'bavotasan_layout',
			'settings' => 'arcade_basic_theme_options[layout]',
			'size' => false,
			'priority' => 25,
			'choices' => $layout_choices,
		) ) );

		$colors = array(
			'default' => __( 'Default', 'arcade' ),
			'info' => __( 'Light Blue', 'arcade' ),
			'primary' => __( 'Blue', 'arcade' ),
			'danger' => __( 'Red', 'arcade' ),
			'warning' => __( 'Yellow', 'arcade' ),
			'success' => __( 'Green', 'arcade' ),
		);

		// Jumbo headline section panel
		$wp_customize->add_section( 'bavotasan_jumbo', array(
			'title' => __( 'Jumbo Headline', 'arcade' ),
			'priority' => 36,
			'description' => __( 'This section appears below the header image on the home page. To remove it just delete all the content from the Title textarea.', 'arcade' ),
		) );

		$wp_customize->add_setting( 'arcade_basic_theme_options[jumbo_headline_title]', array(
			'default' => $bavotasan_theme_options['jumbo_headline_title'],
			'type' => 'option',
			'capability' => 'edit_theme_options',
            'sanitize_callback' => 'esc_textarea',
		) );

		$wp_customize->add_control( new Bavotasan_Textarea_Control( $wp_customize, 'jumbo_headline_title', array(
			'label' => __( 'Title', 'arcade' ),
			'section' => 'bavotasan_jumbo',
			'settings' => 'arcade_basic_theme_options[jumbo_headline_title]',
			'priority' => 26,
			'type' => 'text',
		) ) );

		$wp_customize->add_setting( 'arcade_basic_theme_options[jumbo_headline_text]', array(
			'default' => $bavotasan_theme_options['jumbo_headline_text'],
			'type' => 'option',
			'capability' => 'edit_theme_options',
            'sanitize_callback' => 'esc_textarea',
		) );

		$wp_customize->add_control( new Bavotasan_Textarea_Control( $wp_customize, 'jumbo_headline_text', array(
			'label' => __( 'Text', 'arcade' ),
			'section' => 'bavotasan_jumbo',
			'settings' => 'arcade_basic_theme_options[jumbo_headline_text]',
			'priority' => 27,
			'type' => 'text',
		) ) );

		// Posts panel
		$wp_customize->add_section( 'bavotasan_posts', array(
			'title' => __( 'Posts', 'arcade' ),
			'priority' => 45,
			'description' => __( 'These options do not affect the home page post section.', 'arcade' ),
		) );

		$wp_customize->add_setting( 'arcade_basic_theme_options[display_categories]', array(
			'default' => $bavotasan_theme_options['display_categories'],
			'type' => 'option',
			'capability' => 'edit_theme_options',
            'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
		) );

		$wp_customize->add_control( 'bavotasan_display_categories', array(
			'label' => __( 'Display Categories', 'arcade' ),
			'section' => 'bavotasan_posts',
			'settings' => 'arcade_basic_theme_options[display_categories]',
			'type' => 'checkbox',
		) );

		$wp_customize->add_setting( 'arcade_basic_theme_options[display_author]', array(
			'default' => $bavotasan_theme_options['display_author'],
			'type' => 'option',
			'capability' => 'edit_theme_options',
            'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
		) );

		$wp_customize->add_control( 'bavotasan_display_author', array(
			'label' => __( 'Display Author', 'arcade' ),
			'section' => 'bavotasan_posts',
			'settings' => 'arcade_basic_theme_options[display_author]',
			'type' => 'checkbox',
		) );

		$wp_customize->add_setting( 'arcade_basic_theme_options[display_date]', array(
			'default' => $bavotasan_theme_options['display_date'],
			'type' => 'option',
			'capability' => 'edit_theme_options',
            'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
		) );

		$wp_customize->add_control( 'bavotasan_display_date', array(
			'label' => __( 'Display Date', 'arcade' ),
			'section' => 'bavotasan_posts',
			'settings' => 'arcade_basic_theme_options[display_date]',
			'type' => 'checkbox',
		) );

		$wp_customize->add_setting( 'arcade_basic_theme_options[display_comment_count]', array(
			'default' => $bavotasan_theme_options['display_comment_count'],
			'type' => 'option',
			'capability' => 'edit_theme_options',
            'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
		) );

		$wp_customize->add_control( 'bavotasan_display_comment_count', array(
			'label' => __( 'Display Comment Count', 'arcade' ),
			'section' => 'bavotasan_posts',
			'settings' => 'arcade_basic_theme_options[display_comment_count]',
			'type' => 'checkbox',
		) );
	}

	/**
	 * Sanitize checkbox options
	 *
	 * @since 1.0.2
	 */
    public function sanitize_checkbox( $value ) {
        if ( 'on' != $value )
            $value = false;

        return $value;
    }
}
$bavotasan_customizer = new Bavotasan_Customizer;