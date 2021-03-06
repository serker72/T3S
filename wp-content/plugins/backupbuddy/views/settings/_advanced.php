<?php
if ( !is_admin() ) { die( 'Access Denied.' ); }

$settings_form = new pb_backupbuddy_settings( 'advanced_settings', '', 'tab=1', 320 );



$settings_form->add_setting( array(
	'type'		=>		'title',
	'name'		=>		'title_basic',
	'title'		=>		__( 'Basic Operation', 'it-l10n-backupbuddy' ),
) );





$settings_form->add_setting( array(
	'type'		=>		'checkbox',
	'name'		=>		'backup_reminders',
	'options'	=>		array( 'unchecked' => '0', 'checked' => '1' ),
	'title'		=>		__( 'Enable backup reminders for edits', 'it-l10n-backupbuddy' ),
	'tip'		=>		__( '[Default: enabled] - When enabled links will be displayed upon post or page edits and during WordPress upgrades to remind and allow rapid backing up after modifications or before upgrading.', 'it-l10n-backupbuddy' ),
	'css'		=>		'',
	'after'		=>		'',
	'rules'		=>		'required',
) );
$settings_form->add_setting( array(
	'type'		=>		'checkbox',
	'name'		=>		'archive_name_format',
	'options'	=>		array( 'unchecked' => 'date', 'checked' => 'datetime' ),
	'title'		=>		__( 'Add time in backup file name', 'it-l10n-backupbuddy' ),
	'tip'		=>		__( '[Default: disabled (date only)] - When enabled your backup filename will display the time the backup was created in addition to the default date. This is useful when making multiple backups in a one day period.', 'it-l10n-backupbuddy' ),
	'css'		=>		'',
	'rules'		=>		'required',
) );
$settings_form->add_setting( array(
	'type'		=>		'checkbox',
	'name'		=>		'lock_archives_directory',
	'options'	=>		array( 'unchecked' => '0', 'checked' => '1' ),
	'title'		=>		__( 'Lock archive directory (high security)', 'it-l10n-backupbuddy' ),
	'tip'		=>		__( '[Default: disabled] - When enabled all downloads of archives via the web will be prevented under all circumstances via .htaccess file. If your server permits it, they will only be unlocked temporarily on click to download. If your server does not support this unlocking then you will have to access the archives via the server (such as by FTP).', 'it-l10n-backupbuddy' ),
	'css'		=>		'',
	'after'		=>		'<span class="description"> ' . __('May prevent downloading backups within WordPress on incompatible servers', 'it-l10n-backupbuddy' ),
	'rules'		=>		'required',
) );
$settings_form->add_setting( array(
	'type'		=>		'checkbox',
	'name'		=>		'include_importbuddy',
	'options'	=>		array( 'unchecked' => '0', 'checked' => '1' ),
	'title'		=>		__('Include ImportBuddy in full backup archive', 'it-l10n-backupbuddy' ),
	'tip'		=>		__('[Default: enabled] - When enabled, the importbuddy.php (restoration tool) file will be included within the backup archive ZIP file in the location `/' . str_replace( ABSPATH, '', backupbuddy_core::getTempDirectory() ) . ' xxxxxxxxxx/ importbuddy.php` where the x\'s match the unique random string in the backup ZIP filename.', 'it-l10n-backupbuddy' ),
	'css'		=>		'',
	'after'		=>		' <span style="white-space: nowrap;"><span class="description">' . __( 'Located in backup', 'it-l10n-backupbuddy' ) . ':</span>&nbsp; <span class="code" style="white-space: normal; background: #EAEAEA;"">/' . str_replace( ABSPATH, '', backupbuddy_core::getTempDirectory() ) . 'xxxxxxxxxx/importbuddy.php</span>',
	'rules'		=>		'required',
) );
$log_file = backupbuddy_core::getLogDirectory() . 'log-' . pb_backupbuddy::$options['log_serial'] . '.txt';
$settings_form->add_setting( array(
	'type'		=>		'select',
	'name'		=>		'log_level',
	'title'		=>		__('Logging Level', 'it-l10n-backupbuddy' ),
	'options'	=>		array(
								'0'		=>		__( 'None', 'it-l10n-backupbuddy' ),
								'1'		=>		__( 'Errors Only', 'it-l10n-backupbuddy' ),
								'2'		=>		__( 'Errors & Warnings', 'it-l10n-backupbuddy' ),
								'3'		=>		__( 'Everything (troubleshooting mode)', 'it-l10n-backupbuddy' ),
							),
	'tip'		=>		sprintf( __('[Default: Errors Only] - This option controls how much activity is logged for records or troubleshooting. Logs may be viewed from the Logs / Other tab on the Settings page. Additionally when in Everything / Troubleshooting mode error emails will contain encrypted troubleshooting data for support. Log file: %s', 'it-l10n-backupbuddy' ), $log_file ),
	'rules'		=>		'required',
) );
$settings_form->add_setting( array(
	'type'		=>		'text',
	'name'		=>		'max_site_log_size',
	'title'		=>		__('Maximum log file size', 'it-l10n-backupbuddy' ),
	'tip'		=>		__('[Default: 10 MB] - If the log file exceeds this size then it will be cleared to prevent it from using too much space.' ),
	'rules'		=>		'required',
	'css'		=>		'width: 50px;',
	'after'		=>		' MB',
) );


$settings_form->add_setting( array(
	'type'		=>		'checkbox',
	'name'		=>		'rollback_beta',
	'options'	=>		array( 'unchecked' => '0', 'checked' => '1' ),
	'title'		=>		__( 'Database Rollback Feature (BETA)', 'it-l10n-backupbuddy' ),
	'tip'		=>		__( '[Default: Disabled] When enabled a new Database Rollback feature will be available on the Restore / Migrate page for easily rolling back the database to a prior backup state..', 'it-l10n-backupbuddy' ) . '</span>',
	'css'		=>		'',
	'after'		=>		'<span class="description"> ' . __( 'Experimental in-progress beta feature.', 'it-l10n-backupbuddy' ) . '</span>',
	'rules'		=>		'required',
) );


$settings_form->add_setting( array(
	'type'		=>		'title',
	'name'		=>		'title_advanced',
	'title'		=>		__( 'Technical & Server Compatibility', 'it-l10n-backupbuddy' ),
) );



$settings_form->add_setting( array(
	'type'		=>		'checkbox',
	'name'		=>		'delete_archives_pre_backup',
	'options'	=>		array( 'unchecked' => '0', 'checked' => '1' ),
	'title'		=>		__( 'Delete all backup archives prior to backups', 'it-l10n-backupbuddy' ),
	'tip'		=>		__( '[Default: disabled] - When enabled all local backup archives will be deleted prior to each backup. This is useful if in compatibilty mode to prevent backing up existing files.', 'it-l10n-backupbuddy' ),
	'css'		=>		'',
	'after'		=>		'<span class="description"> ' . __('Use is exclusions are malfunctioning or for special purposes.', 'it-l10n-backupbuddy' ) . '</span>',
	'rules'		=>		'required',
) );
$settings_form->add_setting( array(
	'type'		=>		'checkbox',
	'name'		=>		'disable_https_local_ssl_verify',
	'options'	=>		array( 'unchecked' => '0', 'checked' => '1' ),
	'title'		=>		__( 'Disable local SSL certificate verification', 'it-l10n-backupbuddy' ),
	'tip'		=>		__( '[Default: Disabled] When checked, WordPress will skip local https SSL verification.', 'it-l10n-backupbuddy' ) . '</span>',
	'css'		=>		'',
	'after'		=>		'<span class="description"> ' . __( 'Workaround if local SSL verification fails (ie. for loopback & local CA cert issues).', 'it-l10n-backupbuddy' ) . '</span>',
	'rules'		=>		'required',
) );
$settings_form->add_setting( array(
	'type'		=>		'checkbox',
	'name'		=>		'prevent_flush',
	'options'	=>		array( 'unchecked' => '0', 'checked' => '1' ),
	'title'		=>		__( 'Prevent Flushing', 'it-l10n-backupbuddy' ),
	'tip'		=>		__( '[Default: not prevented (unchecked)] - Rarely some servers die unexpectedly when flush() or ob_flush() are called multiple times during the same PHP process. Checking this prevents these from ever being called during backups.', 'it-l10n-backupbuddy' ),
	'css'		=>		'',
	'after'		=>		'<span class="description"> ' . __('Check if directed by support.', 'it-l10n-backupbuddy' ) . '</span>',
	'rules'		=>		'required',
) );
$settings_form->add_setting( array(
	'type'		=>		'checkbox',
	'name'		=>		'save_comment_meta',
	'options'	=>		array( 'unchecked' => '0', 'checked' => '1' ),
	'title'		=>		__( 'Save meta data in comment', 'it-l10n-backupbuddy' ),
	'tip'		=>		__( '[Default: Enabled] When enabled, BackupBuddy will store general backup information in the ZIP comment header such as Site URL, backup type & time, serial, etc. during backup creation.', 'it-l10n-backupbuddy' ) . '</span>',
	'css'		=>		'',
	'after'		=>		'<span class="description"> ' . __( 'If backups hang when saving meta data disabling skips this process.', 'it-l10n-backupbuddy' ) . '</span>',
	'rules'		=>		'required',
) );
$settings_form->add_setting( array(
	'type'		=>		'checkbox',
	'name'		=>		'profiles#0#integrity_check',
	'options'	=>		array( 'unchecked' => '0', 'checked' => '1' ),
	'title'		=>		__('Perform integrity check on backup files', 'it-l10n-backupbuddy' ),
	'tip'		=>		__('[Default: enabled] - By default each backup file is checked for integrity and completion the first time it is viewed on the Backup page.  On some server configurations this may cause memory problems as the integrity checking process is intensive.  If you are experiencing out of memory errors on the Backup file listing, you can uncheck this to disable this feature.', 'it-l10n-backupbuddy' ),
	'css'		=>		'',
	'after'		=>		'<span class="description"> ' . __( 'Disable if the backup page will not load or backups hang on integrity check.', 'it-l10n-backupbuddy' ) . '</span>',
	'rules'		=>		'required',
) );
$settings_form->add_setting( array(
	'type'		=>		'select',
	'name'		=>		'backup_mode',
	'title'		=>		__('Default global backup mode', 'it-l10n-backupbuddy' ),
	'options'	=>		array(
								'1'		=>		__( 'Classic (v1.x) - Entire backup in single PHP page load', 'it-l10n-backupbuddy' ),
								'2'		=>		__( 'Modern (v2.x+) - Split across page loads via WP cron', 'it-l10n-backupbuddy' ),
							),
	'tip'		=>		__('[Default: Modern] - If you are encountering difficulty backing up due to WordPress cron, HTTP Loopbacks, or other features specific to version 2.x you can try classic mode which runs like BackupBuddy v1.x did.', 'it-l10n-backupbuddy' ),
	'rules'		=>		'required',
) );



$settings_form->add_setting( array(
	'type'		=>		'title',
	'name'		=>		'title_database',
	'title'		=>		__( 'Database', 'it-l10n-backupbuddy' ),
) );
$settings_form->add_setting( array(
	'type'		=>		'checkbox',
	'name'		=>		'profiles#0#skip_database_dump',
	'options'	=>		array( 'unchecked' => '0', 'checked' => '1' ),
	'title'		=>		__('Skip database dump on backup', 'it-l10n-backupbuddy' ),
	'tip'		=>		__('[Default: disabled] - (WARNING: This prevents BackupBuddy from backing up the database during any kind of backup. This is for troubleshooting / advanced usage only to work around being unable to backup the database.', 'it-l10n-backupbuddy' ),
	'css'		=>		'',
	'after'		=>		'<span class="description"> ' . __('Completely bypass backing up database for all database types. Use caution.', 'it-l10n-backupbuddy' ) . '</span>',
	'rules'		=>		'required',
	'orientation' =>	'vertical',
) );
$settings_form->add_setting( array(
	'type'		=>		'checkbox',
	'name'		=>		'breakout_tables',
	'options'	=>		array( 'unchecked' => '0', 'checked' => '1' ),
	'title'		=>		__( 'Break out big table dumps into steps', 'it-l10n-backupbuddy' ),
	'tip'		=>		__( '[Default: Disabled] Currently in beta. Breaks up some commonly known database tables to be backed up separately rather than all at once. Helps with larger databases.', 'it-l10n-backupbuddy' ) . '</span>',
	'css'		=>		'',
	'after'		=>		'<span class="description"> ' . __( 'Backup large data tables in separate steps for handling large databases.', 'it-l10n-backupbuddy' ) . '</span>',
	'rules'		=>		'required',
) );
$settings_form->add_setting( array(
	'type'		=>		'checkbox',
	'name'		=>		'force_mysqldump_compatibility',
	'options'	=>		array( 'unchecked' => '0', 'checked' => '1' ),
	'title'		=>		__('Force compatibility mode database dump', 'it-l10n-backupbuddy' ),
	'tip'		=>		__('[Default: disabled] - WARNING: This forces the potentially slower mode of database dumping. Under normal circumstances mysql dump compatibility mode is automatically entered as needed without user intervention.', 'it-l10n-backupbuddy' ),
	'css'		=>		'',
	'after'		=>		'<span class="description"> ' . __( 'Forces PHP-based database dump instead of command line. Pre-v3.x mode.', 'it-l10n-backupbuddy' ) . '</span>',
	'rules'		=>		'required',
) );
$settings_form->add_setting( array(
	'type'		=>		'checkbox',
	'name'		=>		'ignore_command_length_check',
	'options'	=>		array( 'unchecked' => '0', 'checked' => '1' ),
	'title'		=>		__('Ignore command line length check results', 'it-l10n-backupbuddy' ),
	'tip'		=>		__('[Default: disabled] - WARNING: BackupBuddy attempts to determine your system\'s maximum command line length to insure that database operation commands do not get inadvertantly cut off. On some systems it is not possible to reliably detect this information which could result infalling back into compatibility mode even though the system is capable of running in normal operational modes. This option instructs BackupBuddy to ignore the results of the command line length check.', 'it-l10n-backupbuddy' ),
	'css'		=>		'',
	'after'		=>		'<span class="description"> ' . __( 'Check if directed by support.', 'it-l10n-backupbuddy' ) . '</span>',
	'rules'		=>		'required',
) );



$settings_form->add_setting( array(
	'type'		=>		'title',
	'name'		=>		'title_zip',
	'title'		=>		__( 'Zip', 'it-l10n-backupbuddy' ),
) );
$settings_form->add_setting( array(
	'type'		=>		'checkbox',
	'name'		=>		'compression', //'profiles#0#compression',
	'options'	=>		array( 'unchecked' => '0', 'checked' => '1' ),
	'title'		=>		__( 'Enable zip compression', 'it-l10n-backupbuddy' ),
	'tip'		=>		__( '[Default: enabled] - ZIP compression decreases file sizes of stored backups. If you are encountering timeouts due to the script running too long, disabling compression may allow the process to complete faster.', 'it-l10n-backupbuddy' ),
	'css'		=>		'',
	'after'		=>		'<span class="description"> ' . __('Typically DOUBLES the amount of data which may be zipped up before timeouts.', 'it-l10n-backupbuddy' ) . '</span>',
	'rules'		=>		'required',
) );
$settings_form->add_setting( array(
	'type'		=>		'select',
	'name'		=>		'zip_method_strategy',
	'title'		=>		__('Zip method strategy', 'it-l10n-backupbuddy' ),
	'options'	=>		array(
								'1'		=>		__( 'Best Available', 'it-l10n-backupbuddy' ),
								'2'		=>		__( 'All Available', 'it-l10n-backupbuddy' ),
								'3'		=>		__( 'Force Compatibility', 'it-l10n-backupbuddy' ),
							),
	'tip'		=>		__('[Default: Best Only] - Normally use Best Available but if the server is unreliable in this mode can try All Available or Force Compatibility', 'it-l10n-backupbuddy' ),
	'after'		=>		'<span class="description"> ' . __('Select Force Compatibility if absolutely necessary.', 'it-l10n-backupbuddy' ) . '</span>',
	'rules'		=>		'required',
) );
$settings_form->add_setting( array(
	'type'		=>		'checkbox',
	'name'		=>		'alternative_zip_2',
	'options'	=>		array( 'unchecked' => '0', 'checked' => '1' ),
	'title'		=>		__( 'Alternative zip system (BETA)', 'it-l10n-backupbuddy' ),
	'tip'		=>		__( '[Default: Disabled] Use if directed by support.', 'it-l10n-backupbuddy' ) . '</span>',
	'css'		=>		'',
	'after'		=>		'<span class="description"> Check if directed by support.</span>',
	'rules'		=>		'required',
) );
$settings_form->add_setting( array(
	'type'		=>		'checkbox',
	'name'		=>		'disable_zipmethod_caching',
	'options'	=>		array( 'unchecked' => '0', 'checked' => '1' ),
	'title'		=>		__( 'Disable zip method caching', 'it-l10n-backupbuddy' ),
	'tip'		=>		__( '[Default: Disabled] Use if directed by support. Bypasses caching available zip methods so they are always displayed in logs. When unchecked BackupBuddy will cache command line zip testing for a few minutes so it does not run too often. This means that your backup status log may not always show the test results unless you disable caching.', 'it-l10n-backupbuddy' ) . '</span>',
	'css'		=>		'',
	'after'		=>		'<span class="description"> Check if directed by support to always log zip detection.</span>',
	'rules'		=>		'required',
) );
$settings_form->add_setting( array(
	'type'		=>		'checkbox',
	'name'		=>		'ignore_zip_warnings',
	'options'	=>		array( 'unchecked' => '0', 'checked' => '1' ),
	'title'		=>		__( 'Ignore zip archive warnings', 'it-l10n-backupbuddy' ),
	'tip'		=>		__( '[Default: Disabled] When enabled BackupBuddy will ignore non-fatal warnings encountered during the backup process such as inability to read or access a file, symlink problems, etc. These non-fatal warnings will still be logged.', 'it-l10n-backupbuddy' ) . '</span>',
	'css'		=>		'',
	'after'		=>		'<span class="description"> Check to ignore non-fatal errors when zipping files.</span>',
	'rules'		=>		'required',
) );
$settings_form->add_setting( array(
	'type'		=>		'checkbox',
	'name'		=>		'ignore_zip_symlinks',
	'options'	=>		array( 'unchecked' => '0', 'checked' => '1' ),
	'title'		=>		__( 'Ignore/do-not-follow symbolic links', 'it-l10n-backupbuddy' ),
	'tip'		=>		__( '[Default: Enabled] When enabled BackupBuddy will ignore/not-follow symbolic links encountered during the backup process', 'it-l10n-backupbuddy' ) . '</span>',
	'css'		=>		'',
	'after'		=>		'<span class="description"> Symbolic links are followed by default. Unfollowable links may cause failures.</span>',
	'rules'		=>		'required',
) );







$settings_form->process(); // Handles processing the submitted form (if applicable).
$settings_form->display_settings( 'Save Advanced Settings' );

