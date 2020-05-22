<?php
/**
 * Custom Control Customizer.
 *
 * Contains class of custom control.
 *
 * @package Xpertiz
 */

/**
 * WordPress customizer Icon Picker Control.
 *
 * Description.
 *
 * @since 1.0.0
 * @see WP_Customize_Control.
 */
class Xpertiz_Icon_Picker_Control extends WP_Customize_Control {
	/**
	 * Type
	 *
	 * @var string
	 */
	public $type = 'icon-picker';

	/**
	 * Icon Set
	 *
	 * @var array
	 */
	public $iconset = array();

	/**
	 * To JSON
	 */
	public function to_json() {
		if ( empty( $this->iconset ) ) {
			$this->iconset = 'fa';
		}
		$iconset               = $this->iconset;
		$this->json['iconset'] = $iconset;
		parent::to_json();
	}

	/**
	 * Enqueue
	 */
	public function enqueue() {
		wp_enqueue_script( 'xpertiz-icon-picker-ddslick-min', XPERTIZ_THEME_URI . '/inc/customizer/assets/vendor/icon-picker/js/jquery.ddslick.min.js', array( 'jquery' ) );
		wp_enqueue_script( 'xpertiz-icon-picker-control', XPERTIZ_THEME_URI . '/inc/customizer/assets/vendor/icon-picker/js/icon-picker-control.js', array( 'jquery', 'xpertiz-icon-picker-ddslick-min' ), '', true );
		if ( in_array( $this->iconset, array( 'genericon', 'genericons' ) ) ) {
			wp_enqueue_style( 'genericons', XPERTIZ_THEME_URI . '/inc/customizer/assets/vendor/icon-picker/css/genericons/genericons.css' );
		} elseif ( in_array( $this->iconset, array( 'dashicon', 'dashicons' ) ) ) {
			wp_enqueue_style( 'dashicons' );
		} else {
			wp_enqueue_style( 'font-awesome', XPERTIZ_THEME_URI . '/inc/customizer/assets/vendor/icon-picker/css/font-awesome/css/font-awesome.min.css' );
		}
	}

	/**
	 * Render Content
	 */
	public function render_content() {
		if ( empty( $this->choices ) ) {
			if ( in_array( $this->iconset, array( 'genericon', 'genericons' ) ) ) {
				require_once XPERTIZ_THEME_DIR . '/inc/customizer/lib/icon-picker/genericons-icons.php';
				$this->choices = xpertiz_genericons_list();
			} elseif ( in_array( $this->iconset, array( 'dashicon', 'dashicons' ) ) ) {
				require_once XPERTIZ_THEME_DIR . '/inc/customizer/lib/icon-picker/dashicons-icons.php';
				$this->choices = xpertiz_dashicons_list();
			} else {
				require_once XPERTIZ_THEME_DIR . '/inc/customizer/lib/icon-picker/fa-icons.php';
				$this->choices = xpertiz_font_awesome_list();
			}
		}
		?>
		<label>
		<?php if ( ! empty( $this->label ) ) : ?>
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<?php
				endif;
if ( ! empty( $this->description ) ) :
	?>
			<span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
		<?php endif; ?>
				<select class="xpertiz-icon-picker-icon-control" id="<?php echo esc_attr( $this->id ); ?>">
					<?php foreach ( $this->choices as $value => $label ) : ?>
						<option value="<?php echo esc_attr( $value ); ?>" <?php echo esc_attr( selected( $this->value(), $value, false ) ); ?> data-iconsrc="<?php echo esc_attr( $value ); ?>"><?php echo esc_html( $label ); ?></option>
					<?php endforeach; ?>
				</select>
			</label>
		<?php
	}
}
