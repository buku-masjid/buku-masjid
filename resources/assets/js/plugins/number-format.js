(function(global) {
  // Utility to escape special characters for RegExp
  function escapeRegExp(string) {
    return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
  }

  // Utility function to format number with custom separators
  function formatNumber(input, options = {}) {
    const {
      decimalSeparator = '.',
      thousandSeparator = ' '
    } = options;

    // If input is empty or just a decimal separator, return as is
    if (!input || input === decimalSeparator) return input;

    // Determine the "other" decimal separator to remove
    const otherSeparator = decimalSeparator === '.' ? ',' : '.';

    // Replace the other separator with the preferred decimal separator if present
    input = input.replace(new RegExp(escapeRegExp(otherSeparator), 'g'), decimalSeparator);

    // Split the input into integer and decimal parts
    const [integerPart, decimalPart] = input.split(decimalSeparator);

    // Remove non-digit characters from integer part
    const cleanedIntegerPart = integerPart.replace(/\D/g, '');

    // Format the integer part with thousand separators
    const formattedInteger = cleanedIntegerPart
      .replace(/\B(?=(\d{3})+(?!\d))/g, thousandSeparator)
      .trim();

    // If there's a decimal part, add it back with the custom separator
    return decimalPart !== undefined
      ? `${formattedInteger}${decimalSeparator}${decimalPart}`
      : formattedInteger;
  }

  // Main function to initialize number formatting on an input
  function initNumberFormatter(selector, options = {}) {
    const {
      decimalSeparator = '.',
      thousandSeparator = ' ',
      preventArrowKeys = true
    } = options;

    // Support both jQuery and vanilla JavaScript selectors
    const inputs = global.jQuery
      ? global.jQuery(selector)
      : document.querySelectorAll(selector);

    // Convert to array for consistent handling
    const inputElements = global.jQuery
      ? inputs.get()
      : Array.from(inputs);

    inputElements.forEach(input => {
      // Set attributes for mobile numeric keyboard
      input.setAttribute('inputmode', 'decimal');  // For decimal numbers

      // Create a pattern that allows for the separators
      const escapedDecimalSep = escapeRegExp(decimalSeparator);
      const escapedThousandSep = escapeRegExp(thousandSeparator);
      const pattern = `[0-9${escapedThousandSep}]*${escapedDecimalSep}?[0-9]*`;
      input.setAttribute('pattern', pattern);

      // Override separators from data attributes if they exist
      const selectorDecimalSep = input.dataset.decimalSeparator;
      const selectorThousandSep = input.dataset.thousandSeparator;

      const formatterOptions = {
        decimalSeparator: selectorDecimalSep || decimalSeparator,
        thousandSeparator: selectorThousandSep || thousandSeparator,
      };

      // Update pattern if separators were provided via data attributes
      if (selectorDecimalSep || selectorThousandSep) {
        const newEscapedDecimalSep = escapeRegExp(formatterOptions.decimalSeparator);
        const newEscapedThousandSep = escapeRegExp(formatterOptions.thousandSeparator);
        const newPattern = `[0-9${newEscapedThousandSep}]*${newEscapedDecimalSep}?[0-9]*`;
        input.setAttribute('pattern', newPattern);
      }

      // Prevent cursor movement
      input.addEventListener('click', (e) => {
        e.target.setSelectionRange(e.target.value.length, e.target.value.length);
      });

      // Prevent arrow keys if option is enabled
      if (preventArrowKeys) {
        input.addEventListener('keydown', (e) => {
          if (['ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown'].includes(e.key)) {
            e.preventDefault();
          }
        });
      }

      // Input formatting
      input.addEventListener('input', (e) => {
        // Get the raw input value (remove existing spaces and thousand separators)
        const rawValue = e.target.value
          .replace(new RegExp(escapeRegExp(formatterOptions.thousandSeparator), 'g'), '');

        // Format the raw value with the custom separators
        const formattedValue = formatNumber(rawValue, formatterOptions);

        // Update input value
        e.target.value = formattedValue;

        // Always move cursor to the end
        e.target.setSelectionRange(e.target.value.length, e.target.value.length);
      });

      // Add form submit handler to clean up the value if needed
      if (input.form) {
        input.form.addEventListener('submit', (e) => {
          // You might want to add a hidden input with the clean numeric value
          const cleanValue = input.value
            .replace(new RegExp(escapeRegExp(formatterOptions.thousandSeparator), 'g'), '')
            .replace(formatterOptions.decimalSeparator, '.');

          // Create or update hidden input
          let hiddenInput = input.form.querySelector(`#${input.id}_clean`);
          if (!hiddenInput) {
            hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.id = `${input.id}_clean`;
            hiddenInput.name = input.name;
            input.removeAttribute('name'); // Remove name from formatted input
            input.form.appendChild(hiddenInput);
          }
          hiddenInput.value = cleanValue;
        });
      }
    });

    // Return the inputs for chaining
    return inputs;
  }

  // Expose functions globally
  global.formatNumber = formatNumber;
  global.initNumberFormatter = initNumberFormatter;

  // Auto-initialize if no jQuery (vanilla JS approach)
  if (!global.jQuery) {
    document.addEventListener('DOMContentLoaded', () => {
      initNumberFormatter('[data-number-format]');
    });
  }
})(typeof window !== 'undefined' ? window : global);