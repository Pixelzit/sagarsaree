import React, {useMemo, useState, useEffect} from 'react';
import {createRoot} from 'react-dom/client';
import Stars from './components/Stars';
import { t } from './lib/i18n';
import { postAjax } from './lib/api';

const App = ({ajaxUrl, ajaxAction, pluginSlug, nonce, petitionText, ratingText, userEmail}) => {
	const [hovered, setHovered] = useState(0);
	const [selected, setSelected] = useState(5);
	const [view, setView] = useState('rate'); // rate | feedback | done
	const [loading, setLoading] = useState(false);
	const [error, setError] = useState('');
	const [message, setMessage] = useState('');
	const [email, setEmail] = useState(userEmail || '');
	const [closed, setClosed] = useState(false);

	const starsValue = hovered || selected;

	const handleClick = async (stars) => {
		setSelected(stars);
		setLoading(true);
		setError('');
		try {
			const res = await postAjax(ajaxUrl, {
				action: ajaxAction,
				nonce,
				subaction: 'submit_rating',
				rating: String(stars),
				plugin_slug: pluginSlug,
			});
			if (res.success && res.data?.action === 'open_url' && res.data?.url) {
				window.open(res.data.url, '_blank', 'noopener');
				setView('done');
			} else if (res.success && res.data?.action === 'open_form') {
				setView('feedback');
			} else {
				setError(t('unexpected_response', 'Unexpected server response.'));
			}
		} catch (e) {
			setError(e.message || t('request_failed', 'Request failed'));
		} finally {
			setLoading(false);
		}
	};

	const handleLater = async () => {
		setLoading(true);
		setError('');
		try {
			const res = await postAjax(ajaxUrl, {
				action: ajaxAction,
				nonce,
				subaction: 'postpone',
				plugin_slug: pluginSlug,
			});
			if (res.success) {
				setClosed(true);
			} else {
				setError(res?.data?.message || t('request_failed', 'Request failed'));
			}
		} catch (e) {
			setError(e.message || t('request_failed', 'Request failed'));
		} finally {
			setLoading(false);
		}
	};

	const submitFeedback = async (ev) => {
		ev.preventDefault();
		if (!message.trim()) {
			setError(t('msg_empty', 'Message cannot be empty.'));
			return;
		}
		setLoading(true);
		setError('');
		try {
			const res = await postAjax(ajaxUrl, {
				action: ajaxAction,
				nonce,
				subaction: 'submit_feedback',
				rating: String(selected || hovered || 0),
				message,
				email,
				plugin_slug: pluginSlug,
			});
			if (res.success) {
				setView('done');
			} else {
				setError(res?.data?.message || t('request_failed', 'Request failed'));
			}
		} catch (e) {
			setError(e.message || t('request_failed', 'Request failed'));
		} finally {
			setLoading(false);
		}
	};

	const handleClose = () => {
		setClosed(true);
	};

	// Auto-close popup 5 seconds after showing the "done" view
	useEffect(() => {
		if (view === 'done') {
			const timer = setTimeout(() => setClosed(true), 5000);
			return () => clearTimeout(timer);
		}
		return undefined;
	}, [view]);

	if (closed) return null;

	return (
		<div className="wpdesk-rating-popup">
			{view === 'rate' && (
				<div className="wpdesk-rating-card">
					<h1 className="wpdesk-rating-title">{petitionText}</h1>
					<p className="wpdesk-rating-subtitle">{ratingText}</p>
					<Stars
						value={starsValue}
						onHover={(idx) => setHovered(idx)}
						onLeave={() => setHovered(0)}
						onClick={handleClick}
					/>
					{error && <div className="wpdesk-rating-error">{error}</div>}
					{loading && <div className="wpdesk-rating-loading">…</div>}
					<div className="wpdesk-rating-actions">
						<button type="button" disabled={loading}
								className="wpdesk-btn-secondary grey"
								onClick={handleLater}>{t('later', 'Later')}</button>
					</div>
				</div>
			)}
			{view === 'feedback' && (
				<div className="wpdesk-rating-card">
					<h2 className="wpdesk-rating-title">{petitionText}</h2>
					<p className="wpdesk-rating-subtitle">{ratingText}</p>
					<Stars
						value={starsValue}
						onHover={(idx) => setHovered(idx)}
						onLeave={() => setHovered(0)}
						onClick={(v) => handleClick(v)}
					/>
					<form className="wpdesk-rating-form" onSubmit={submitFeedback}>
						<label>
							<span>{t('your_feedback', 'Your feedback')}</span>
							<textarea value={message} onChange={(e) => setMessage(e.target.value)} rows={4} required/>
						</label>
						<label>
							<span>{userEmail ? t('email', 'Email') : t('email_optional', 'Email (optional)')}</span>
							<input
								type="email"
								value={email}
								onChange={(e) => setEmail(e.target.value)}
								placeholder={t('placeholder_email', 'you@example.com')}
							/>
						</label>
						<div className="wpdesk-rating-actions">
							<button type="button" disabled={loading}
									className="wpdesk-btn-secondary"
									onClick={handleLater}>{t('later', 'Later')}</button>
							<button type="submit" disabled={loading}
									className="wpdesk-btn-primary">{t('send', 'Send')}</button>
						</div>
					</form>
					{error && (
						<div className="wpdesk-rating-error">
							{error}
							<button type="button" className="wpdesk-rating-close-link"
									onClick={handleClose}>{t('close', 'Close')}</button>
						</div>
					)}
					{loading && <div className="wpdesk-rating-loading">…</div>}
				</div>
			)}
			{view === 'done' && (
				<div className="wpdesk-rating-card">
					<p className="wpdesk-rating-thanks">{t('thanks', 'Thank you!')}</p>
				</div>
			)}
		</div>
	);
};

function mountRatingPetitionPopup(container) {
	if (!container) return;
	const dataset = container.dataset || {};
	const props = {
		ajaxUrl: dataset.ajaxUrl,
		ajaxAction: dataset.ajaxAction,
		pluginSlug: dataset.pluginSlug,
		nonce: dataset.nonce,
		petitionText: dataset.petitionText,
		ratingText: dataset.ratingText,
		userEmail: dataset.userEmail,
	};
	const root = createRoot(container);
	root.render(<App {...props} />);
}

document.addEventListener('DOMContentLoaded', () => {
	const container = document.getElementById('wpdesk-rating-petition-popup');
	mountRatingPetitionPopup(container);
});
