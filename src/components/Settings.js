/**
 * WordPress dependencies
 */
import { Fragment, useState, useEffect } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import { Button } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

function Settings() {
	const [isPageLoading, setIsPageLoading] = useState(true);

	useEffect(() => {
		setIsPageLoading( false );
	}, []);

	function onSubmitFormHandler( event ) {
		event.preventDefault();
		setIsPageLoading( true );

		const recipient = event.target.recipient.value;
		const sitekey = event.target.sitekey.value;

		const settings = {
			wpfeather_settings: {
				recipient: recipient,
				sitekey: sitekey
			}
		};

		const settingsUrl = wpfeatherSettings.root + 'wp/v2/settings';
        const requestMetadata = {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(settings)
        };

        fetch(settingsUrl, requestMetadata)
			.then(response => response.json())
			.then();

		setIsPageLoading( false );
	}

	return (
		<Fragment>
			<div className="wrap">
				<h1>{ __( 'WPFeather Settings', 'wpfeather' ) }</h1>
				<div id="wpfeather-settings-fields">
					<form onSubmit={ ( event ) => onSubmitFormHandler( event ) }>
						<div className="flex flex-wrap -mx-3 mb-6">
							<div className="w-full md:w-1/2 px-3 mb-6 md:mb-0">
								<label
									className="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
									htmlFor="recipient">
									{ __( 'Recipient E-mail', 'wpfeather' ) }
								</label>
								<input
									className="appearance-none block w-full bg-gray-200 text-gray-700 border border-red-500 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white"
									id="recipient"
									name="recipient"
									type="email"
									placeholder="admin@mail.com" />
									<p className="mt-2 text-sm text-gray-500 dark:text-gray-400">{ __( 'Where the e-mail will be received', 'wpfeather' ) }</p>
									<p className="text-red-500 text-xs italic invisible">{ __( 'Please fill out this field.', 'wpfeather' ) }</p>
							</div>
						</div>
						<div className="flex flex-wrap -mx-3 mb-6">
							<div className="w-full md:w-1/2 px-3">
								<label
									className="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
									htmlFor="sitekey">
									{ __( 'Cloudflare Turnstile sitekey', 'wpfeather' ) }
								</label>
								<input
									className="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
									id="sitekey"
									name="sitekey"
									type="text"
									placeholder="YOUR_SITE_KEY"/>
								<p id="helper-text-explanation"
								   className="mt-2 text-sm text-gray-500 dark:text-gray-400">We’ll never share your
									details. Learn more about <a href="https://www.cloudflare.com/products/turnstile/"
																 target="_blank"
														 className="font-medium text-blue-600 hover:underline dark:text-blue-500">Cloudflare Turnstile</a>.</p>

							</div>
						</div>
						<fieldset disabled={ isPageLoading }>
							<div className="wpfeather-settings-submit">
								<Button
									type="submit"
									isPrimary={ true }
									isBusy={ isPageLoading }
								>
									{
										isPageLoading ?
											`${ __( 'Saving Settings', 'wpfeather' ) }...` :
											__( 'Save Settings', 'wpfeather' )
									}
								</Button>
							</div>
						</fieldset>
					</form>
				</div>
			</div>
		</Fragment>
	);
}

export default Settings;
