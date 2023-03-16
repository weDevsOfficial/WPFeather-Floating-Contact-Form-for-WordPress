const fs = require( 'fs' );
const replace = require( 'replace-in-file' );

const pluginFiles = [ 'includes/**/*.php', 'src/**/*.js', 'wpfeather.php' ];

const { version } = JSON.parse( fs.readFileSync( 'package.json' ) );

replace( {
	files: pluginFiles,
	from: /WPFEATHER_SINCE/g,
	to: version,
} );
