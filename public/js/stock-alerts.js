function filterStockAlerts() {
    const query = document.getElementById('stockSearchInput').value.toLowerCase().trim();
    
    document.querySelectorAll('.empty-stock-row').forEach(row => {
        const text = row.innerText.toLowerCase();
        row.style.display = text.includes(query) ? '' : 'none';
    });

    document.querySelectorAll('.low-stock-row').forEach(row => {
        const text = row.innerText.toLowerCase();
        row.style.display = text.includes(query) ? '' : 'none';
    });

    document.querySelectorAll('.expiring-row').forEach(row => {
        const text = row.innerText.toLowerCase();
        row.style.display = text.includes(query) ? '' : 'none';
    });
}