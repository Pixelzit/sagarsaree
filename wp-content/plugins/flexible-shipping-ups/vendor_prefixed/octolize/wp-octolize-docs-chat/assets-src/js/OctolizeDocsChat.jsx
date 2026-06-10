import React, {useEffect, useMemo, useRef, useState} from 'react';
import {createRoot} from 'react-dom/client';
import {createChat} from '@n8n/chat';
import {v4 as uuidv4} from 'uuid';

let chatSettings = null;
let apiStatus = null;

const App = ({settings, pluginSlug}) => {
	const [isModalOpen, setIsModalOpen] = useState(false);
	const [chatInitialized, setChatInitialized] = useState(false);
	// state to manage consent modal and/chat lifecycle

	const consentKey = `octolize-docs-chat-consent-${pluginSlug}`;
	const consentDisabled = Boolean(settings?.consent_disabled ?? false);
	const hasConsent = typeof window !== 'undefined' && (() => {
		try {
			return sessionStorage.getItem(consentKey) === '1';
		} catch (e) {
			return false;
		}
	})();

	const isConsentGiven = () => {
		if (typeof window === 'undefined') return false;
		try {
			return sessionStorage.getItem(consentKey) === '1';
		} catch (e) {
			return false;
		}
	};

	const initChat = (autoOpen = true) => {
		if (!settings || chatInitialized) return;
		// Build i18n config
		const i18n = {
			en: {
				title: settings.title,
				subtitle: settings.subtitle,
				footer: settings.footer,
				getStarted: settings.get_started,
				inputPlaceholder: settings.input_placeholder,
			},
		};

		const includeConsent = !consentDisabled && isConsentGiven();

		createChat({
			webhookUrl: settings.webhook_url,
			mode: 'window',
			enableStreaming: settings.streaming ?? false,
			chatSessionKey: 'sessionIdn8n',
			loadPreviousSession: true,
			showWelcomeScreen: false,
			defaultLanguage: 'en',
			initialMessages: settings.initial_messages,
			i18n,
			metadata: {
				...(includeConsent ? {consent: true} : {}),
				sessionId: getChatSessionId(pluginSlug),
				...settings.metadata,
			},
		});

		setChatInitialized(true);

		// Try to auto-open chat window when requested
		if (autoOpen) {
			setTimeout(() => {
				try {
					const toggle = document.querySelector('.chat-window-toggle');
					if (toggle) {
						toggle.click();
					}
				} catch (e) { /* noop */
				}
			}, 50);
		}
	};

	// Auto-initialize chat on page load
	useEffect(() => {
		if (!chatInitialized && (consentDisabled || hasConsent)) {
			initChat(false);
		}
		// eslint-disable-next-line react-hooks/exhaustive-deps
	}, [hasConsent, chatInitialized, consentDisabled]);

	const onLauncherClick = (e) => {
		e.preventDefault();
		if (consentDisabled || hasConsent) {
			if (!chatInitialized) {
				initChat();
			} else {
				try {
					const toggle = document.querySelector('.chat-window-toggle');
					if (toggle) {
						toggle.click();
					}
				} catch (err) { /* noop */
				}
			}
		} else {
			setIsModalOpen(true);
		}
	};

	const acceptConsent = () => {
		try {
			sessionStorage.setItem(consentKey, '1');
		} catch (e) { /* ignore */
		}
		setIsModalOpen(false);
		initChat();
	};

	const declineConsent = () => {
		setIsModalOpen(false);
	};

	// close modal with ESC
	useEffect(() => {
		if (!isModalOpen) return;
		const onKey = (e) => {
			if (e.key === 'Escape') setIsModalOpen(false);
		};
		window.addEventListener('keydown', onKey);
		return () => window.removeEventListener('keydown', onKey);
	}, [isModalOpen]);

	// Consent texts from settings or defaults
	const consent = settings?.consent ?? {};
	const consentTitle = consent.title || 'Do you consent to sending data to the chat?';
	const consentMessage = consent.message || 'To start the chat, we need to send your inputs and technical data to the chat service. Read more in our Privacy Policy.';
	const consentAccept = consent.accept || 'Accept';
	const consentDecline = consent.decline || 'Decline';
	const privacyUrl = consent.privacy_policy_url || 'https://octolize.com/terms-of-service/privacy-policy/';
	const privacyLabel = consent.privacy_link_label || 'Privacy Policy';
	const supportUrl = consent.support_url || 'https://octolize.com/support/';
	const supportText = consent.support_text || 'If you don’t want to give consent, you can contact our support using this form:';
	const supportLabel = consent.support_link_label || 'Support form';
	const hasPrivacy = Boolean((privacyUrl ?? '').toString().trim()) && Boolean((privacyLabel ?? '').toString().trim());
	const hasSupport = Boolean((supportText ?? '').toString().trim()) && Boolean((supportUrl ?? '').toString().trim());

	return (
		<>
			{!chatInitialized && (
				<button
					className={`octo-chat-launcher${(!consentDisabled && !hasConsent) ? ' is-attention' : ''}`}
					aria-label="Open chat"
					onClick={onLauncherClick}
				>
					<svg width="32" height="32" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
						<path fill="currentColor"
							  d="M12 3c5.5 0 10 3.58 10 8s-4.5 8-10 8c-1.24 0-2.43-.18-3.53-.5C5.55 21 2 21 2 21c2.33-2.33 2.7-3.9 2.75-4.5C3.05 15.07 2 13.13 2 11c0-4.42 4.5-8 10-8"></path>
					</svg>
				</button>
			)}

			{!consentDisabled && isModalOpen && (
				<div className="octo-chat-consent-overlay" role="dialog" aria-modal="true" onClick={(e) => {
					if (e.target === e.currentTarget) setIsModalOpen(false);
				}}>
					<div className="octo-chat-consent-modal">
						<h3 className="octo-chat-consent-title">{consentTitle}</h3>
						<p className="octo-chat-consent-message">{consentMessage}</p>
						{hasSupport && (
							<p className="octo-chat-consent-support">
								{supportText} {" "}
								<a href={supportUrl} target="_blank" rel="noopener noreferrer">{supportLabel}</a>.
							</p>
						)}
						<div className="octo-chat-consent-actions">
							<button className="btn accept" onClick={acceptConsent}>{consentAccept}</button>
							<button className="btn decline" onClick={declineConsent}>{consentDecline}</button>
						</div>
						{hasPrivacy && (
							<p className="octo-chat-consent-privacy">
								<a href={privacyUrl} target="_blank" rel="noopener noreferrer">{privacyLabel}</a>
							</p>
						)}
					</div>
				</div>
			)}
		</>
	);
};

function mountDocsChat(container, pluginSlug = 'octolize-docs') {
	const root = createRoot(container);
	root.render(<App settings={chatSettings} pluginSlug={pluginSlug}/>);
}


function fetchSettings(container) {
	const ajax_url = container.getAttribute('data-ajax_url');
	const ajax_action = container.getAttribute('data-ajax_action');
	const nonce = container.getAttribute('data-nonce');
	const instance_id = container.getAttribute('data-instance_id');
	const pluginSlug = container.getAttribute('data-plugin_slug');
	fetch(ajax_url, {
		method: 'POST',
		headers: {
			'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
		},
		body: new URLSearchParams({
			action: ajax_action,
			nonce: nonce,
			instance_id: instance_id
		}),
		credentials: 'same-origin'
	}).then((response) => response.json()).then((data) => {
		chatSettings = data?.data?.settings ?? null;
		if (!chatSettings) return;
		chatSettings.metadata = chatSettings.metadata ?? {};
		changeApiStatusInSettings();
		mountDocsChat(container, pluginSlug);
	})
}

function getChatSessionId(pluginSlug) {
	const key = `octolize-docs-chat-${pluginSlug}`;
	const SIX_HOURS_MS = 6 * 60 * 60 * 1000;
	const now = Date.now();

	let raw = localStorage.getItem(key);
	let sessionId = null;
	let ts = 0;

	if (raw) {
		try {
			const stored = JSON.parse(raw);
			sessionId = stored.id ?? null;
			ts = stored.ts ?? 0;
		} catch (e) {
			// Backward compatibility: previously only the UUID string was stored
			sessionId = raw;
			ts = 0;
		}
	}

	// Generate a new session id if none exists or it's older than 6 hours
	if (!sessionId || (now - ts) > SIX_HOURS_MS) {
		sessionId = uuidv4();
		ts = now;
		try {
			localStorage.setItem(key, JSON.stringify({id: sessionId, ts}));
		} catch (e) { /* ignore */
		}
	} else {
		// Normalize legacy storage format to JSON object
		if (raw === sessionId) {
			try {
				localStorage.setItem(key, JSON.stringify({id: sessionId, ts}));
			} catch (e) { /* ignore */
			}
		}
	}

	return sessionId;
}

function changeApiStatusInSettings() {
	if (!chatSettings) return;
	if (!apiStatus) return;

	chatSettings.metadata.plugin_settings.api_status = apiStatus;
}

document.addEventListener('wpdesk_wc_shipping_api_status', (e) => {
	apiStatus = e.detail.status;
	changeApiStatusInSettings();
});

document.addEventListener('DOMContentLoaded', () => {
	const container = document.getElementById('octolize-docs-chat');
	if (!container) return;

	fetchSettings(container);
});
