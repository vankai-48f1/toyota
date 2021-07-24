<script type="text/html" id="tmpl-cardealer-helper-sample-import-alert">
	<h3 class="sample-title"><?php echo esc_html__( 'Sample Data', 'cardealer-helper' ); ?> : {{data.title}}</h3>
	{{data.message}}
	<h3 class="import-requirements"><?php echo esc_html__( 'Import Requirements', 'cardealer-helper' ); ?> : </h3>
	<ul class="import-requirements-list">
		<# _.each( data.import_requirements_list, function(res, index) { #>
			<li>- {{res}}</li>
		<# }) #>
	</ul>
	<# if ( data.required_plugins_list ) { #>
		<h3 class="required-plugins"><?php echo esc_html__( 'Required Plugins', 'cardealer-helper' ); ?> : </h3>
		<p class="required-plugins-message"><?php echo esc_html__( 'Please install/activate below required plugins before proceed to import.', 'cardealer-helper' ); ?></p>
		<ul class="required-plugins-list">
			<# _.each( data.required_plugins_list, function(res, index) { #>
				<li>- {{res}}</li>
			<# }) #>
		</ul>
	<# } #>
</script>
