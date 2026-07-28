 function filterInventoryTable() {
        const nameQuery = document.getElementById('liveSearchInput').value.toLowerCase().trim();
        const catQuery = document.getElementById('liveCategoryInput').value.toLowerCase().trim();
        const compQuery = document.getElementById('liveCompanyInput').value.toLowerCase().trim();

        const rows = document.querySelectorAll('.inventory-row');

        rows.forEach(row => {
            const nameText = row.querySelector('.med-name') ? row.querySelector('.med-name').innerText.toLowerCase() : '';
            const catText = row.querySelector('.med-cat') ? row.querySelector('.med-cat').innerText.toLowerCase() : '';
            const compText = row.querySelector('.med-comp') ? row.querySelector('.med-comp').innerText.toLowerCase() : '';

            const matchesName = nameText.includes(nameQuery);
            const matchesCat = catText.includes(catQuery);
            const matchesComp = compText.includes(compQuery);

            if (matchesName && matchesCat && matchesComp) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    async function handleDelete(event, id) {
        event.preventDefault();
        if (!confirm('Are you sure you want to delete this medicine?')) return;

        const form = event.target;
        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new FormData(form)
            });

            if (response.ok || response.redirected) {
                const row = document.getElementById(`medicine-row-${id}`);
                if (row) {
                    row.style.transition = 'all 0.3s ease';
                    row.style.opacity = '0';
                    row.style.transform = 'scale(0.97)';
                    setTimeout(() => row.remove(), 300);
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
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });

            if (response.ok || response.redirected) {
                const modal = document.getElementById(`edit-modal-${id}`);
                if (modal) modal.classList.add('hidden');

                const row = document.getElementById(`medicine-row-${id}`);
                if (row) {
                    row.querySelector('.med-name').innerText = formData.get('name');
                    row.querySelector('.med-cat').innerText = formData.get('category');
                    row.querySelector('.med-comp').innerText = formData.get('company');
                    
                    const priceVal = parseFloat(formData.get('price')).toFixed(2);
                    row.querySelector('.med-price').innerText = `Rs. ${priceVal}`;
                    
                    row.querySelector('.med-stock').innerText = `${formData.get('stock')} units`;
                    row.querySelector('.med-expiry').innerText = formData.get('expiry_date');

                    row.classList.add('bg-emerald-50');
                    setTimeout(() => row.classList.remove('bg-emerald-50'), 1500);
                }
            } else {
                form.submit();
            }
        } catch (error) {
            form.submit();
        }
    }