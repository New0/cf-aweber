<?php
/**
 * Processor config UI for Awber for Caldera Forms
 *
 * @package   cf_awber
 * @author    Josh Pollock Josh Pollock for CalderaWP LLC (email : Josh@CalderaWP.com)
 * @license   GPL-2.0+
 * @link
 * @copyright 2015 Josh Pollock for CalderaWP LLC for CalderaWP LLC
 */
$credentials = cf_awber_main_credentials();
$credentials->set_from_save();
$credentials_set = $credentials->all_set();

?>

<div id="cf-awber-setup" <?php if( false == $credentials_set ) : ?> style="visibility: hidden;display: none;" aria-hidden="true" <?php endif; ?> >
	<?php
		$config_fields              = Caldera_Forms_Processor_UI::config_fields( cf_awber_fields() );
		echo $config_fields;
	?>
</div>
<?php if( false == $credentials_set ) : ?>
<div id="cf-awber-auth">
	<h3>
		<?php esc_html_e( 'Authorize Caldera Forms To Connect To Awber', 'cf-awber' ); ?>
	</h3>

		<div class="caldera-config-group">
			<label for="cf-awber-auth-url" id="cf-awber-auth-url-label">
				<?php esc_html_e('Get Authorization', 'cf-aweber'); ?>
			</label>
			<a href="<?php echo esc_url( cf_awber_get_auth_url() ); ?>" id="cf-awber-auth-url" target="_blank" class="button" aria-describedby="cf-awber-auth-url-desc" title="<?php esc_attr_e( 'Click to get authorization code from Awber', 'cf-awber');?>">
				<?php esc_html_e('Get Authorization Code', 'cf-aweber' ); ?>
			</a>
			<p class="description" id="cf-awber-auth-url-desc">
				<?php esc_html_e( 'Click this button to login to your Awber account and get an authorization code. You will need to paste the code in the input below.', 'cf-awber' ); ?>
			</p>
		</div>



		<div class="caldera-config-group">
			<label for="cf-awber-code" id="cf-awber-code-label">
				<?php esc_html_e('Authorization Code', 'cf-aweber'); ?>
			</label>
			<div class="caldera-config-field">
				<input type="text" class="block-input field-config" id="cf-awber-code" aria-describedby="cf-awber-code-desc" aria-labelledby="cf-awber-code-label">
			</div>
			<p class="description" id="cf-awber-code-desc">
				<?php esc_html_e( 'Paste your authorization code here and click the "Save Authorization Button".', 'cf-awber' ); ?>
			</p>
		</div>

		<div class="caldera-config-group">
			<label for="cf-awber-auth-save" id="cf-awber-auth-save-label">
				<?php esc_html_e('Save Authorization', 'cf-aweber'); ?>
			</label>
			<div class="caldera-config-field">
				<button id="cf-awber-auth-save" class="button button"  aria-labelledby="cf-awber-auth-save-label">
					<?php esc_html_e( 'Save Authorization', 'cf-aweber'); ?>
				</button>
			</div>
			<p class="description" id="cf-awber-code-desc">
				<?php esc_html_e( 'Once you have entered your authorization code, click here.', 'cf-awber' ); ?>
				<span id="cf-awber-save-auth-spinner" class="spinner" aria-hidden="true"></span>
			</p>
		</div>

</div>

<?php endif; ?>

<script type="text/javascript">
	jQuery(document).ready(function( $ ) {


		$( "#cf-awber-auth-save" ).on( 'click', function(e) {
			e.preventDefault();
			var spinnerEL = document.getElementById( 'cf-awber-save-auth-spinner' );
			$( spinnerEL ).css( 'visibility', 'visible' ).attr( 'aria-hidden', 'false' ).show();
			var data = {
				action: 'cf_awber_auth_save',
				code: $( '#cf-awber-code' ).val(),
				nonce: "<?php echo wp_create_nonce(); ?>"
			};
			var xhr = $.post( ajaxurl, data );
			xhr.done(function() {
				$( spinnerEL ).css( 'visibility', 'hidden' ).attr( 'aria-hidden', 'true' ).hide();
				$( '#cf-awber-auth' ).slideUp( "slow", function() {
					$( this ).attr( 'aria-hidden', 'true' ).css( 'visibility', 'hidden' );
					$( '#cf-awber-setup' ).attr( 'aria-hidden', 'false' ).css( 'visibility', 'visible' ).show();
				});
			});
			xhr.error(function(r) {
				alert( r.responseJSON.data.message );
				$( spinnerEL ).css( 'visibility', 'hidden' ).attr( 'aria-hidden', 'true' ).hide();
			});
		});

		$( '#cf-awber-refresh-lists' ).on( 'click', function(e){
			e.preventDefault();
			getLists();
		});

		function getLists(){
			var spinnerEL = document.getElementById( 'cf-awber-get-list-spinner' );
			var data = {
				action: 'cf_awber_get_lists',
				nonce: "<?php echo wp_create_nonce(); ?>"
			};
			$( spinnerEL ).css( 'visibility', 'visible' ).attr( 'aria-hidden', 'false' ).show();

			var xhr = $.get( ajaxurl, data );
			xhr.done(function( r ) {
				if( 'object' == typeof  r ){
					$( '#cf-awber-list' ).html( r.data.input );
				}
				$( spinnerEL ).css( 'visibility', 'hidden' ).attr( 'aria-hidden', 'true' ).hide();
			});
			xhr.error(function(r) {
				alert( r.statusText );
				$( spinnerEL ).css( 'visibility', 'hidden' ).attr( 'aria-hidden', 'true' ).hide();
			});
		}

	});

</script>
