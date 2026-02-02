import Choices from 'choices.js';
import 'choices.js/public/assets/styles/choices.min.css';

/**
 * Initialize Choices.js on select elements
 *
 * @param {string} selector - CSS selector for select elements
 * @param {object} options - Custom options for Choices.js
 * @returns {array} Array of Choices instances
 */
export function initChoices(selector = '.choices-select', options = {}) {
    const elements = document.querySelectorAll(selector);
    const instances = [];

    if (elements.length === 0) return instances;

    const defaultOptions = {
        searchEnabled: true,
        searchPlaceholderValue: 'Search...',
        itemSelectText: '',
        shouldSort: false,
        removeItemButton: false,
        noResultsText: 'No results found',
        noChoicesText: 'No choices to choose from',
        ...options
    };

    elements.forEach(element => {
        // Skip if already initialized
        if (element.classList.contains('choices__input')) return;

        const instance = new Choices(element, defaultOptions);
        instances.push(instance);
    });

    return instances;
}

/**
 * Initialize Choices.js with custom options for multiple select
 *
 * @param {string} selector
 * @param {object} options
 * @returns {array}
 */
export function initMultipleChoices(selector = '.choices-multiple', options = {}) {
    return initChoices(selector, {
        removeItemButton: true,
        ...options
    });
}

/**
 * Destroy Choices instance
 *
 * @param {Choices} instance
 */
export function destroyChoices(instance) {
    if (instance && instance.destroy) {
        instance.destroy();
    }
}

/**
 * Set value to Choices instance
 *
 * @param {Choices} instance
 * @param {string|array} value
 */
export function setChoicesValue(instance, value) {
    if (instance && instance.setChoiceByValue) {
        instance.setChoiceByValue(value);
    }
}

/**
 * Clear Choices instance
 *
 * @param {Choices} instance
 */
export function clearChoices(instance) {
    if (instance && instance.clearStore) {
        instance.clearStore();
    }
}
