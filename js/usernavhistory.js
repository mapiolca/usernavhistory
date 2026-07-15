/* Copyright (C) 2026 ATM Consulting x Les Métiers du Bâtiment <developpeur@lesmetiersdubatiment.fr>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

(function () {
	'use strict';

	var MAX_LABEL_CHARACTERS = 15;

	/**
	 * Split a label into user-perceived characters when the browser supports it.
	 *
	 * @param {string} label Label to split.
	 * @returns {string[]} Label characters.
	 */
	function splitLabelCharacters(label) {
		if (typeof Intl === 'object' && typeof Intl.Segmenter === 'function') {
			var segmenter = new Intl.Segmenter(undefined, {granularity: 'grapheme'});
			return Array.from(segmenter.segment(label), function (part) {
				return part.segment;
			});
		}

		return Array.from(label);
	}

	/**
	 * Return the last non-empty text node used as the visible object label.
	 *
	 * @param {HTMLElement} item History item.
	 * @returns {Text|null} Label text node.
	 */
	function getLabelTextNode(item) {
		var target = item.querySelector('a') || item;
		var walker = document.createTreeWalker(target, NodeFilter.SHOW_TEXT, null);
		var currentNode = walker.nextNode();
		var labelNode = null;

		while (currentNode) {
			if (currentNode.nodeValue && currentNode.nodeValue.trim() !== '') {
				labelNode = currentNode;
			}
			currentNode = walker.nextNode();
		}

		return labelNode;
	}

	/**
	 * Truncate a history label without replacing its native Dolibarr link.
	 *
	 * @param {HTMLElement} item History item.
	 * @returns {void}
	 */
	function truncateLabel(item) {
		if (item.getAttribute('data-usernavhistory-processed') === '1') {
			return;
		}

		var labelNode = getLabelTextNode(item);
		if (!labelNode || !labelNode.nodeValue) {
			item.setAttribute('data-usernavhistory-processed', '1');
			return;
		}

		var originalValue = labelNode.nodeValue;
		var leadingWhitespace = (originalValue.match(/^\s*/) || [''])[0];
		var trailingWhitespace = (originalValue.match(/\s*$/) || [''])[0];
		var label = originalValue.trim();
		var characters = splitLabelCharacters(label);

		item.setAttribute('data-usernavhistory-full-label', label);
		if (characters.length > MAX_LABEL_CHARACTERS) {
			labelNode.nodeValue = leadingWhitespace + characters.slice(0, MAX_LABEL_CHARACTERS).join('') + '...' + trailingWhitespace;
		}

		item.setAttribute('data-usernavhistory-processed', '1');
	}

	/**
	 * Hide the oldest entries until the breadcrumb fits on one line.
	 *
	 * @param {HTMLElement} list History list.
	 * @returns {void}
	 */
	function fitHistoryList(list) {
		var items = Array.prototype.slice.call(list.querySelectorAll('.usernavhistory-item'));
		var index = 0;

		items.forEach(function (item) {
			item.hidden = false;
		});
		list.classList.remove('usernavhistory-list--constrained');

		while (list.scrollWidth > list.clientWidth + 1 && index < items.length - 1) {
			items[index].hidden = true;
			index++;
		}

		if (list.scrollWidth > list.clientWidth + 1) {
			list.classList.add('usernavhistory-list--constrained');
		}
	}

	/**
	 * Initialize a navigation history bar.
	 *
	 * @param {HTMLElement} bar History bar.
	 * @returns {void}
	 */
	function initializeHistoryBar(bar) {
		if (window.name === 'objectpreview') {
			bar.hidden = true;
			return;
		}

		var list = bar.querySelector('.usernavhistory-list');
		if (!list) {
			return;
		}

		Array.prototype.forEach.call(list.querySelectorAll('.usernavhistory-item'), truncateLabel);

		var resizeList = function () {
			window.requestAnimationFrame(function () {
				fitHistoryList(list);
			});
		};

		resizeList();
		Array.prototype.forEach.call(list.querySelectorAll('img'), function (image) {
			if (!image.complete) {
				image.addEventListener('load', resizeList);
			}
		});
		if (document.fonts && document.fonts.ready) {
			document.fonts.ready.then(resizeList);
		}
		if (typeof ResizeObserver === 'function') {
			var observer = new ResizeObserver(resizeList);
			observer.observe(bar);
		} else {
			window.addEventListener('resize', resizeList);
		}
	}

	function initializeNavigationHistory() {
		Array.prototype.forEach.call(document.querySelectorAll('.usernavhistory'), initializeHistoryBar);
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initializeNavigationHistory);
	} else {
		initializeNavigationHistory();
	}
})();
