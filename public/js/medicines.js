// Helper to get CSRF token safely from meta tag in external JS files
function getCsrfToken() {
    const meta = document.querySelector('meta[name="csrf-token"]');
    return meta ? meta.getAttribute('content') : '';
}

let searchTimeout;

async function filterStockAlerts() {
    const searchInput = document.getElementById('stockSearchInput');
    if (!searchInput) return;
    
    const query = searchInput.value.trim();
    
    clearTimeout(searchTimeout);
    
    searchTimeout = setTimeout(async () => {
        try {
            const url = query ? `/medicines?search=${encodeURIComponent(query)}` : '/medicines';
            
            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (response.ok) {
                const htmlText = await response.text();
                
                const parser = new DOMParser();
                const doc = parser.parseFromString(htmlText, 'text/html');

                const newTableBody = doc.getElementById('inventoryTableBody');
                const oldTableBody = document.getElementById('inventoryTableBody');
                if (newTableBody && oldTableBody) {
                    oldTableBody.style.opacity = '0.5'; 
                    setTimeout(() => {
                        oldTableBody.innerHTML = newTableBody.innerHTML;
                        oldTableBody.style.opacity = '1';
                    }, 150);
                }

                const newBadge = doc.getElementById('totalMedicinesBadge');
                if (newBadge) {
                    document.getElementById('totalMedicinesBadge').innerHTML = newBadge.innerHTML;
                }

                const oldPagination = document.querySelector('.border-t.bg-slate-50\\/50');
                const newPagination = doc.querySelector('.border-t.bg-slate-50\\/50');
                
                if (oldPagination && newPagination) {
                    oldPagination.innerHTML = newPagination.innerHTML;
                } else if (!oldPagination && newPagination) {
                    const tableContainer = document.querySelector('.overflow-x-auto');
                    if (tableContainer) tableContainer.insertAdjacentHTML('afterend', newPagination.outerHTML);
                } else if (oldPagination && !newPagination) {
                    oldPagination.remove();
                }

                window.history.pushState({path: url}, '', url);
            }
        } catch (error) {
            console.error('Search failed:', error);
        }
    }, 400); 
}

document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('stockSearchInput');
    if (searchInput) {
        searchInput.addEventListener('input', filterStockAlerts);
    }
});

async function handleDelete(event, id) {
    event.preventDefault();
    if (!confirm('Are you sure you want to delete this medicine?')) return;

    const form = event.target;
    try {
        const response = await fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': getCsrfToken(),
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: new FormData(form)
        });

        if (response.ok) {
            const row = document.getElementById(`medicine-row-${id}`);
            if (row) {
                row.style.transition = 'all 0.3s ease';
                row.style.opacity = '0';
                row.style.transform = 'scale(0.97)';
                setTimeout(() => {
                    row.remove();
                    filterStockAlerts();
                }, 300);
            }
        } else {
            form.submit();
        }
    } catch (error) {
        form.submit();
    }
}

async function handleUpdate(event, id) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);

    try {
        const response = await fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': getCsrfToken(),
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        });

        if (response.ok) {
            const modal = document.getElementById(`edit-modal-${id}`);
            if (modal) modal.classList.add('hidden');

            const row = document.getElementById(`medicine-row-${id}`);
            if (row) {
                row.querySelector('.med-name').innerText = formData.get('name');
                row.querySelector('.med-cat').innerText = formData.get('category');
                row.querySelector('.med-comp').innerText = formData.get('company');
                
                const priceVal = parseFloat(formData.get('price')).toFixed(2);
                row.querySelector('.med-price').innerText = `${CUR} ${priceVal}`;
                
                row.querySelector('.med-stock').innerText = `${formData.get('stock')} units`;
                row.querySelector('.med-expiry').innerText = formData.get('expiry_date');

                row.classList.add('bg-navy-50');
                setTimeout(() => row.classList.remove('bg-navy-50'), 1500);
            }
        } else {
            form.submit();
        }
    } catch (error) {
        form.submit();
    }
}