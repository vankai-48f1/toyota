<div class="clear"></div>
<div id="cdhl-csv-import-troubleshoot" class="cdhl_configuration postbox metabox-holder">
	<h2 class="hndle"><?php echo esc_html__( 'Troubleshooting', 'cardealer-helper' ); ?></h2>
	<div class="inside" id="cdhl_troubleshoot_accordion">
		<h3><?php echo esc_html__( 'Common Server Configuration Issues', 'cardealer-helper' ); ?></h3>
		<div>
			<p><?php esc_html_e( 'If the size of the file you are trying to upload exceeds your server\'s hard limit, the import process will reject it or throw an error. Here are some most common hard limits that users encounter:', 'cardealer-helper' ); ?></p>
			<ul style="list-style: disc;margin-left: 11.3px;">
				<li><h4 style="margin-bottom:5px;"><?php esc_html_e( 'Maximum Upload File Size (PHP)', 'cardealer-helper' ); ?></h4><?php echo wp_kses( __( 'It is set in php.ini with <strong>upload_max_filesize</strong>. It determines the maximum file size that your server will allow to be uploaded. This value must be larger than the size of the file you wish to upload to the import process.', 'cardealer-helper' ), array( 'strong' => array() ) ); ?></li>
				<li><h4 style="margin-bottom:5px;"><?php esc_html_e( 'Maximum Post Size (PHP)', 'cardealer-helper' ); ?></h4><?php echo wp_kses( __( 'It is set in php.ini with <strong>post_max_size</strong>. It determines the maximum allowed file size the PHP process can use. It should be set higher than upload_max_filesize.', 'cardealer-helper' ), array( 'strong' => array() ) ); ?></li>
				<li><h4 style="margin-bottom:5px;"><?php esc_html_e( 'Memory Limit (PHP)', 'cardealer-helper' ); ?></h4><?php echo wp_kses( __( 'It is set in php.ini with <strong>memory_limit</strong>. It determines how much memory the server will allocate to the script. It should be set higher than <strong>post_max_size</strong>.', 'cardealer-helper' ), array( 'strong' => array() ) ); ?></li>
				<li><h4 style="margin-bottom:5px;"><?php esc_html_e( 'Maximum Execution Time (PHP)', 'cardealer-helper' ); ?></h4><?php echo wp_kses( __( 'It is set in php.ini with max_execution_time. It determines how long a script will run before it\'s terminated. You can ask your host to increase the limit, but this should be considered a last resort.', 'cardealer-helper' ), array( 'strong' => array() ) ); ?></li>
			</ul>
			<?php /* translators: %1$s: url */ ?>
			</p><?php printf( wp_kses( __( 'If you cannot import the inventory, your server is likely missing the necessary components. Please refer to the <a href="%1$s" target="_blank" rel="noopener noreferrer">server configuration</a> page.', 'cardealer-helper' ), array( 'strong' => array(), 'a' => array( 'href' => array(), 'target' => array() ) ) ), esc_url_raw( admin_url( 'admin.php?page=cardealer-system-status' ) ) ); ?></p>
		</div>
		<h3><?php esc_html_e( 'Invalid CSV Content', 'cardealer-helper' ); ?></h3>
		<div>
			<p><?php esc_html_e( 'There are pretty strict rules that CSV files must conform to for them to work. You can validate your CSV data using the below free online service:', 'cardealer-helper' ); ?></p>
			<a href="http://csvlint.io" target="_blank">http://csvlint.io</a>
		</div>
		<h3><?php esc_html_e( 'Is Import not working as expected?', 'cardealer-helper' ); ?></h3>
		<div>
			<p><?php esc_html_e( 'This process will solve pretty much any problem you’re having:', 'cardealer-helper' ); ?></p>
			<ol>
				<li><?php esc_html_e( 'Make sure you are using the latest version of WordPress, the "Car Dealer" theme, and the "Car Dealer - Helper Library" plugin.', 'cardealer-helper' ); ?></li>
				<li><?php esc_html_e( 'Eliminate other themes and plugins that do not belong to the "car dealer" theme, as there may be potential causes of conflict. Deactivate all other active WordPress plugins that are no longer needed.', 'cardealer-helper' ); ?></li>
				<li><?php esc_html_e( 'Ask your host to check your server’s error log to see if something is stopping Import from working properly. Often, artificial limits on script execution time or MySQL queries prevent the import process from finishing imports.', 'cardealer-helper' ); ?></li>
			</ol>
		</div>
		<h3><?php esc_html_e( 'Common Issues', 'cardealer-helper' ); ?></h3>
		<div>
			<ul style="list-style: disc;margin-left: 11.3px;">
				<li><strong><?php esc_html_e( 'Drag and drop not working? Admin screens look strange?', 'cardealer-helper' ); ?></strong></br><?php esc_html_e( 'First, try clearing your browser cache or using a different web browser. If the problem persists, the issue is usually other plugin conflict.', 'cardealer-helper' ); ?><br></strong></li>
				<li><strong><?php esc_html_e( 'Can’t get from one step to another?', 'cardealer-helper' ); ?></strong></br><?php esc_html_e( 'Broken PHP session settings will prevent you from being able to move between the different steps of the import process. If you suspect this is the cause, you’ll need to contact your host. Also try from first step.', 'cardealer-helper' ); ?><br></strong></li>
				<li><strong><?php esc_html_e( 'Running in to a Security Check error?', 'cardealer-helper' ); ?></strong></br><?php esc_html_e( 'Clear your browser cache or try using a different browser.', 'cardealer-helper' ); ?><br></strong></li>
				<?php /* translators: %1$s url */?>
				<li><strong><?php esc_html_e( 'Concerned your server isn’t properly configured?', 'cardealer-helper' ); ?></strong></br><?php printf( wp_kses( __( 'If your imports complete successfully, your server is properly configured. If you’re not able to successfully complete an import, it may be that your server is missing necessary components. Please check <a href="%1$s" target="_blank" rel="noopener noreferrer">server configuration</a> page.', 'cardealer-helper' ), array( 'a' => array( 'href' => array(), 'target' => array() ) ) ), esc_url_raw( admin_url( 'admin.php?page=cardealer-system-status' ) ) ); ?><br></strong></li>
			</ul>
		</div>
		<h3><?php esc_html_e( 'Encoding', 'cardealer-helper' ); ?></h3>
		<div>
			<p><strong><?php esc_html_e( 'CSV Files Must Be UTF-8 Or UTF-8 Compatible', 'cardealer-helper' ); ?></strong></p>
			<p><?php esc_html_e( 'Since there is no way to specify the character encoding for a CSV file, Import assumes all uploaded CSV files are UTF-8, which is the standard character encoding for the web.', 'cardealer-helper' ); ?></p>
			<p><?php esc_html_e( 'This works the vast majority of the time, but if you are importing a CSV file with special characters (things like umlauts, non-Latin characters, etc.) and those special characters aren’t appearing correctly in the posts created by Import, try opening your CSV file in a text editor using the correct encoding, and then re-save it using UTF-8.', 'cardealer-helper' ); ?></p>
		</div>
		<h3><?php esc_html_e( 'Import Speed', 'cardealer-helper' ); ?></h3>
		<div>
			<h3><?php esc_html_e( 'Slow Imports - An Explanation', 'cardealer-helper' ); ?></h3>
			<p><?php esc_html_e( 'There are three main issues that affect import speed:', 'cardealer-helper' ); ?></p>
			<ol>
				<li><?php echo wp_kses( __( '<strong>Server Resources:</strong> If your server doesn’t have enough available resources, this will slow down your import. So if you see that your server is maxing out its CPU or memory usage during an import process, upgrading to a more powerful server will help.', 'cardealer-helper' ), cardealer_allowed_html( 'strong' ) ); ?></li>
				<li><?php echo wp_kses( __( '<strong>Database Size:</strong> WordPress stores data in a SQL database. During the import process, it does the actual importing by interacting with your database. The larger your database, the longer each interaction will take.', 'cardealer-helper' ), cardealer_allowed_html( 'strong' ) ); ?></li>
				<li><?php echo wp_kses( __( '<strong>External Images:</strong> Downloading images from somewhere else during your import will always make your import take longer. If the images are large, or the server providing the images is slow, the impact will be even greater.', 'cardealer-helper' ), cardealer_allowed_html( 'strong' ) ); ?></li>
			</ol>
			<h3><?php esc_html_e( 'Specific Problems & Solutions', 'cardealer-helper' ); ?></h3>
			<ol>
				<li>
					<strong><?php esc_html_e( 'Large Databases, Bloated Tables, and Big Import Files', 'cardealer-helper' ); ?></strong><br>
					<p><?php esc_html_e( 'As your database grows in size, your imports will take longer. It is not possible to avoid this slowdown because as your database grows larger, your server and import process must do more work.', 'cardealer-helper' ); ?></p>
					<p><?php esc_html_e( 'For example, when checking for duplicates, the import process must search through more records. When updating existing posts with new data, the more posts on the site, the longer it takes MySQL to find the correct post to update.', 'cardealer-helper' ); ?></p>
					<h4><?php esc_html_e( '&#9656; Solution', 'cardealer-helper' ); ?></h4>
					<?php /* translators: %1$s: url %2$s: url %3$s: url */ ?>
					<p><?php echo sprintf( wp_kses( __( 'Sometimes your database is bigger than it should be. We’ve seen some users suffering from slow imports, and when we look inside their database, we find that the wp_options table has over a million entries. The most common reason for wp_options bloat is the transients cache, which you can manage with a plugin like <a href="%1$s" target="_blank">Transients Manager</a> (search <a href="%2$s" target="_blank">transient</a>/<a href="%3$s" target="_blank">transients</a> on WordPress.org for more plugins). Check with your host or a database professional to see if your database is similarly bloated.', 'cardealer-helper' ), cardealer_allowed_html( 'strong,a' ) ), esc_url( 'https://wordpress.org/plugins/transients-manager/' ), esc_url( 'https://wordpress.org/plugins/tags/transient/' ), esc_url( 'https://wordpress.org/plugins/tags/transients/' ) ); ?></p>
					<p><?php esc_html_e( 'If you’re trying to build a site with 500,000 products, you’ll need a very powerful and professionally optimized server.', 'cardealer-helper' ); ?></p>
					<p><?php esc_html_e( 'Typically on a shared host, somewhere between 50,000 to 100,000 records seems to be the upper limit. Anything beyond that often results in server timeouts, slow imports, and long load times.', 'cardealer-helper' ); ?></p>
				</li>
				<li>
					<strong><?php esc_html_e( 'Shared Hosts and Low Server Resources', 'cardealer-helper' ); ?></strong><br>
					<p><?php esc_html_e( 'All servers were not created equally. Many times hosting companies will cram as many sites as possible into their hardware to keep costs down. Sometimes they will limit the amount of PHP processing time you are allowed or the number of SQL queries you’re able to make. Or maybe they’ll throttle your processing power. Or maybe they don’t do anything, but the 1,000 other websites are running on the same piece of hardware are consuming so many resources that your import has to fight for CPU time to finish processing.', 'cardealer-helper' ); ?></p>
					<h4><?php esc_html_e( '&#9656; Solution', 'cardealer-helper' ); ?></h4>
					<p><?php esc_html_e( 'If you’re running low on available resources, upgrading to a server with more available resources (amount of RAM, number of CPUs, etc.) will help. But it will usually only help if you’re running low on available resources. While the actual clock speed of your server’s CPU, RAM, and disk do play a role in how long your import takes, it’s not a very big one.', 'cardealer-helper' ); ?></p>
					<p><?php esc_html_e( 'Think of it like a moving truck.', 'cardealer-helper' ); ?></p>
					<p><?php esc_html_e( 'If you’re moving and your truck is too small, you’ll have to make more trips, which will slow you down. Buying a bigger truck will help. But if your truck is already big enough to fit all of your stuff, buying a bigger truck (more CPUs, RAM, etc.) isn’t going to help you move any faster. Upgrading to a server with a faster CPU and disk would be like putting a new engine in your truck. Sure, your move might go a little faster, but it should not be the focus of your efforts.', 'cardealer-helper' ); ?></p>
				</li>
			</ol>
		</div>
		<h3><?php esc_html_e( 'Sample Files', 'cardealer-helper' ); ?></h3>
		<div>
			<ol>
				<li><a href="https://sample-data.potenzaglobal.com/cardealer/sample-inventory/cardealer-sample-inventory-small-wt-images.csv" style="text-decoration: none;" download><?php esc_html_e( 'Small Data (Without Images)', 'cardealer-helper' ); ?></a></li>
				<li><a href="https://sample-data.potenzaglobal.com/cardealer/sample-inventory/cardealer-sample-inventory-small-wo-images.csv" style="text-decoration: none;" download><?php esc_html_e( 'Small Data (With Images)', 'cardealer-helper' ); ?></a></li>
				<li><a href="https://sample-data.potenzaglobal.com/cardealer/sample-inventory/cardealer-sample-inventory-large-wt-images.csv" style="text-decoration: none;" download><?php esc_html_e( 'Large Data (Without Images)', 'cardealer-helper' ); ?></a></li>
				<li><a href="https://sample-data.potenzaglobal.com/cardealer/sample-inventory/cardealer-sample-inventory-large-wo-images.csv" style="text-decoration: none;" download><?php esc_html_e( 'Large Data (With Images)', 'cardealer-helper' ); ?></a></li>
				<li><a href="https://sample-data.potenzaglobal.com/cardealer/sample-inventory/cardealer-sample-inventory-invalid-format.csv" style="text-decoration: none;" download><?php esc_html_e( 'Invalid Format', 'cardealer-helper' ); ?></a></li>
			</ol>
		</div>
	</div>
</div>
