function shortenMoney(amount, localeCode, currencyCode = '') {
    const formatter = new Intl.NumberFormat(localeCode, {
        notation: 'compact',
        maximumFractionDigits: 2 // decimal places.
    });
    return `${currencyCode} ${formatter.format(amount)}`;
}

function shortenMoneyContent(elementId, amount, localeCode, currencyCode) {
    document.getElementById(elementId).textContent = shortenMoney(amount, localeCode, currencyCode);
}
