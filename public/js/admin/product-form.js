document.addEventListener('DOMContentLoaded', () => {
    console.log('DOM entièrement chargé pour Tomselected');

    // --- Gestion du champ "inter" ---
    // Sélectionne le widget Tomselected, le select caché et le conteneur du champ "inter".
    const tsControl = document.querySelector('.ts-control');
    const interWrapper = document.querySelector('.inter-wrapper');
    const selectElement = document.querySelector('select[name$="[unit]"]');


    // Fonction pour afficher/masquer le conteneur "inter"
    function toggleInterField() {
        const selectedOption = selectElement.options[selectElement.selectedIndex];
        const optionText = selectedOption ? selectedOption.text : '';
        console.log("Option sélectionnée :", optionText);
        // Affiche le conteneur uniquement si l'option sélectionnée est "Kilo"
        interWrapper.style.display = (optionText === 'Kilo') ? 'block' : 'none';
    }

    if (tsControl && selectElement) {
        tsControl.addEventListener('click', () => setTimeout(toggleInterField, 100));
        selectElement.addEventListener('change', toggleInterField);
        toggleInterField();
    }

    // --- Gestion du champ "stock" ---
    const hasStockWrapper = document.querySelector('.has-stock-wrapper');
    const hasStockCheckbox = document.querySelector('input[name$="[hasStock]"]');
    const stockWrapper = document.querySelector('.stock-wrapper');

    if (hasStockWrapper && hasStockCheckbox && stockWrapper) {
        const stockWidget = stockWrapper.querySelector('.form-widget');
        const stockLabel = stockWrapper.querySelector('label');

        // Crée un wrapper pour tout déplacer ensemble dans le champ du switch
        const inlineGroup = document.createElement('div');
        inlineGroup.classList.add('stock-inline-group');
        inlineGroup.style.display = 'flex';
        inlineGroup.style.alignItems = 'center';
        inlineGroup.style.gap = '1rem';
        inlineGroup.style.marginTop = '0.5rem';

        if (stockLabel) {
            stockLabel.style.margin = '0';
            inlineGroup.appendChild(stockLabel);
        }

        if (stockWidget) {
            stockWidget.style.margin = '0';
            inlineGroup.appendChild(stockWidget);
        }

        hasStockWrapper.appendChild(inlineGroup);
        stockWrapper.style.display = 'none'; // cache l'ancien wrapper

        function toggleStockField() {
            inlineGroup.style.display = hasStockCheckbox.checked ? 'flex' : 'none';
        }

        hasStockCheckbox.addEventListener('change', toggleStockField);
        toggleStockField();
    }


    // --- Gestion du champ "Texte Promo" ---
    const discountCheckbox = document.querySelector('input[name$="[discount]"]');
    const discountTextWrapper = document.querySelector('.discountText-wrapper');

    if (discountCheckbox && discountTextWrapper) {
        function toggleDiscountTextField() {
            discountTextWrapper.style.display = discountCheckbox.checked ? 'block' : 'none';
        }
        discountCheckbox.addEventListener('change', toggleDiscountTextField);
        toggleDiscountTextField();
    }
});
