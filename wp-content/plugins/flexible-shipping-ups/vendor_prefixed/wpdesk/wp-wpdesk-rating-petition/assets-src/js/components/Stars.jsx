import React from 'react';
import { t } from '../lib/i18n';

const range = (n) => Array.from({ length: n }, (_, i) => i);

const Stars = ({ value, onHover, onLeave, onClick }) => {
  return (
    <div className="wpdesk-rating-stars" onMouseLeave={onLeave}>
      {range(5).map((i) => {
        const idx = i + 1;
        const active = value >= idx;
        return (
          <button
            key={idx}
            type="button"
            className={`wpdesk-rating-star ${active ? 'active' : ''}`}
            onMouseEnter={() => onHover(idx)}
            onClick={() => onClick(idx)}
            aria-label={(t('aria_star', '%d star') || '%d star').replace('%d', idx)}
          >
            ★
          </button>
        );
      })}
    </div>
  );
};

export default Stars;
