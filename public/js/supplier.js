 function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
    }
    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }

    function filterSuppliers() {
        const query = document.getElementById('supplierSearchInput').value.toLowerCase().trim();
        const rows = document.querySelectorAll('.supplier-row');

        rows.forEach(row => {
            const rowText = row.innerText.toLowerCase();
            if (rowText.includes(query)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    function printSupplierStock(areaId) {
        const printElement = document.getElementById(areaId).cloneNode(true);
        
        const closeBtn = printElement.querySelector('.no-print-element');
        if (closeBtn) closeBtn.remove();

        const printWindow = window.open('', '', 'height=750,width=950');

        printWindow.document.write('<html><head><title>Supplier Stock Statement - City Pharmacy</title>');
        printWindow.document.write('<script src="https://cdn.tailwindcss.com"><\/script>');
        printWindow.document.write('<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">');
        printWindow.document.write(`
            <style>
                body { 
                    font-family: "Inter", sans-serif; 
                    padding: 35px; 
                    background-color: #ffffff; 
                    -webkit-print-color-adjust: exact !important; 
                    print-color-adjust: exact !important; 
                }
                @page { margin: 15mm; }
            </style>
        `);
        printWindow.document.write('</head><body>');
        printWindow.document.write(printElement.innerHTML);
        printWindow.document.write('</body></html>');

        printWindow.document.close();
        
        setTimeout(() => {
            printWindow.focus();
            printWindow.print();
            printWindow.close();
        }, 700);
    }