document.addEventListener('DOMContentLoaded', () => {
    console.log('DOM entièrement chargé');

    // Utilitaire générique pour afficher/masquer
    function toggleVisibility(trigger, targetSelector, showCondition) {
        const target = document.querySelector(targetSelector);
        if (!trigger || !target) return;

        function update() {
            target.style.display = showCondition(trigger) ? 'block' : 'none';
        }

        trigger.addEventListener('change', update);
        update(); // Initialiser au chargement
    }

    // --- Affichage du champ "inter" selon le select "unit"
    const selectUnit = document.querySelector('select[name$="[unit]"]');
    toggleVisibility(selectUnit, '.inter-wrapper', (select) => {
        const selectedOption = select.options[select.selectedIndex];
        return selectedOption && selectedOption.text === 'Kilo';
    });

    // --- Affichage du champ "stock" si "hasStock" est coché
    const hasStockCheckbox = document.querySelector('input[name$="[hasStock]"]');
    toggleVisibility(hasStockCheckbox, '.stock-inline-group', (checkbox) => checkbox.checked);

    // --- Affichage du champ "discount text" si "discount" est coché
    const discountCheckbox = document.querySelector('input[name$="[discount]"]');
    toggleVisibility(discountCheckbox, '.discountText-wrapper', (checkbox) => checkbox.checked);

    // --- Créer dynamiquement le groupement stock + label (comme avant)
    const hasStockWrapper = document.querySelector('.has-stock-wrapper');
    const stockWrapper = document.querySelector('.stock-wrapper');
    if (hasStockWrapper && stockWrapper) {
        const stockWidget = stockWrapper.querySelector('.form-widget');
        const stockLabel = stockWrapper.querySelector('label');

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
        stockWrapper.style.display = 'none';
    }
});
