const path = require( 'path' );
const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );
const isProduction = process.env.NODE_ENV === 'production';
let diskWritePermission = true;
let allowHosts = 'all';
let host = 'localhost';
let port = 8887;

const updatedConfig = { ...defaultConfig };

if ( ! isProduction ) {
	updatedConfig.devServer = {
		devMiddleware: {
			writeToDisk: diskWritePermission,
		},
		allowedHosts: allowHosts,
		host: host,
		port: port,
		proxy: {
			'/assets': {
				pathRewrite: {
					'^/assets': '',
				},
			},
		},
	};
}

function resolve( ...paths ) {
	return path.resolve( __dirname, ...paths );
}

module.exports = {
	...updatedConfig,

	output: {
		filename: '[name].js',
		path: resolve( 'assets', 'js' ),
		chunkFilename: 'chunks/[chunkhash].js',
		chunkLoadingGlobal: 'wpfeatherWebpack',
	},
	entry: {
		settings: './src/wpfeather-settings.js',
		form: {
			import: './src/js/floating-form.js',
			filename: 'floating-form.js',
		},
	}
};
