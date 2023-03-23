/**
 * WordPress dependencies
 */
import { Fragment, useState, useEffect } from '@wordpress/element';
import { Button, Notice, TextareaControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

function Settings() {
	const [isPageLoading, setIsPageLoading] = useState(true);

	const [notice, setNotice] = useState({
		status: false,
		message: ''
	});

	const [wpfeatherSettings, setWpfeatherSettings] = useState(window.wpfeatherSettings);

	const [thankYouMsg, setThankYouMsg] = useState(
		{
			title: 'We received your message',
			body: 'We will reach you with your email address soon. Thank you for the time!'
		}
	);

	useEffect( () => {
		jQuery.ajax({
			url: wpfeatherSettings.ajaxurl,
			type: 'POST',
			data: {
				action: 'wpfeather_get_settings'
			},
			success: function (response) {
				if (response.success) {
					if ( response.data.settings.recipient ) {
						jQuery('#recipient').val(response.data.settings.recipient);
					}

					if ( response.data.settings.sitekey ) {
						jQuery('#sitekey').val(response.data.settings.sitekey);
					}

					if ( typeof response.data.settings.thank_you_msg !== 'undefined' ) {
						setThankYouMsg({
							title: response.data.settings.thank_you_msg.title,
							body: response.data.settings.thank_you_msg.body
						})
					}
				}
			},
			error: function (err) {
				setNotice({
					status: 'error',
					message: err.responseText,
				});
			},
		});
		setIsPageLoading(false);
	}, [] );

	const onSubmitFormHandler = ( event ) => {
		event.preventDefault();
		setIsPageLoading( true );

		setNotice({
			status: false,
			message: ''
		});

		const recipient = event.target.recipient.value.trim();
		const sitekey = event.target.sitekey.value.trim();

		jQuery.ajax({
			url: wpfeatherSettings.ajaxurl,
			type: 'POST',
			data: {
				action: wpfeatherSettings.action,
				nonce: wpfeatherSettings.nonce,
				recipient: recipient,
				sitekey: sitekey,
				thank_you_msg: thankYouMsg
			},
			success: function (response) {
				setNotice({
					status: response.data.type,
					message: response.data.message,
				});
				setIsPageLoading( false );
			},
			error: function (err) {
				alert('Something went wrong');
				setIsPageLoading( false );
			},
		});
	}

	return (
		<Fragment>
			<div className="wrap">
				{ notice.status !== false &&
					<Notice
						status={notice.status}
						onRemove={() => {
							setNotice({
								status: false,
								message: ''
							});
						}
						}
					>
						<p>
							{ notice.message }
						</p>
					</Notice>
				}
				<h1>{ __( 'WPFeather Settings', 'wpfeather' ) }</h1>
				<div id="wpfeather-settings-fields" className="mt-4">
					<form onSubmit={ ( event ) => onSubmitFormHandler( event ) }>
						<div className="flex flex-wrap -mx-3 mb-6">
							<div className="w-full md:w-1/3 px-3 mb-6 md:mb-0">
								<label
									className="block tracking-wide text-gray-700 text-xs font-bold mb-2"
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
							</div>
						</div>
						<div className="flex flex-wrap -mx-3 mb-6">
							<div className="w-full md:w-1/3 px-3">
								<label
									className="block tracking-wide text-gray-700 text-xs font-bold mb-2"
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
								   className="mt-2 text-sm text-gray-500 dark:text-gray-400">{ __( 'How to set up', 'wpfeather' ) } <a
									href="https://developers.cloudflare.com/turnstile/get-started/"
									target="_blank"
									className="font-medium text-blue-600 hover:underline dark:text-blue-500">Cloudflare Turnstile</a>.</p>

							</div>
						</div>
						<div className="flex flex-wrap -mx-3 mb-6">
							<div className="w-full md:w-1/3 px-3">
								<label
									className="block tracking-wide text-gray-700 text-xs font-bold mb-2"
									htmlFor="msg-title">
									{ __( 'Thank you message title', 'wpfeather' ) }
								</label>
								<input
									className="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
									id="msg-title"
									name="msg-title"
									type="text"
									value={thankYouMsg.title}
									onChange={ ( event ) => setThankYouMsg({
										...thankYouMsg,
										title: event.target.value
									})}
								/>
								<p id="helper-text-explanation"
								   className="mt-2 text-sm text-gray-500 dark:text-gray-400">{ __( 'The title you want to show after user submits a form', 'wpfeather' ) }</p>
							</div>
						</div>
						<div className="flex flex-wrap -mx-3 mb-6">
							<div className="w-full md:w-1/3 px-3">
								<label
									className="block tracking-wide text-gray-700 text-xs font-bold mb-2"
									htmlFor="msg-body">
									{ __( 'Thank you message body', 'wpfeather' ) }
								</label>
								<TextareaControl
									id="msg-body"
									value={thankYouMsg.body}
									onChange={ ( value ) => setThankYouMsg({
										...thankYouMsg,
										body: value
									} ) }
								/>
								<p id="helper-text-explanation"
								   className="mt-2 text-sm text-gray-500 dark:text-gray-400">{ __( 'The message you want to show after user submits a form', 'wpfeather' ) }</p>
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
											`${ __( 'Saving Settings...', 'wpfeather' ) }` :
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
