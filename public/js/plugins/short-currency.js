function shortenNominalConcise(amount) {
    const formatter = new Intl.NumberFormat('id-ID', { 
        notation: 'compact', 
        maximumFractionDigits: 2 // decimal places.
    });
    return `Rp${formatter.format(amount)}`;
}

function updateNominal(inputId, amount) {
    document.getElementById(inputId).textContent = shortenNominalConcise(amount);
}