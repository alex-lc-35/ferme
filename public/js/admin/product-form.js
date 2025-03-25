document.addEventListener('DOMContentLoaded', () => {
    console.log('DOM entièrement chargé pour Tomselected');

    // --- Gestion du champ "inter" ---
    // Sélectionne le widget Tomselected, le select caché et le conteneur du champ "inter".
    const tsControl = document.querySelector('.ts-control');
    const interWrapper = document.querySelector('.inter-wrapper');
    const selectElement = document.querySelector('select[name$="[unit]"]');

    if (!tsControl) {
        console.error("Le widget ts-control est introuvable.");
    }
    if (!interWrapper) {
        console.error("Le conteneur inter-wrapper est introuvable.");
    }
    if (!selectElement) {
        console.error("Le select unit est introuvable.");
    }

    // Fonction pour afficher/masquer le conteneur "inter"
    function toggleInterField() {
        const selectedOption = selectElement.options[selectElement.selectedIndex];
        const optionText = selectedOption ? selectedOption.text : '';
        console.log("Option sélectionnée :", optionText);
        // Affiche le conteneur uniquement si l'option sélectionnée est "Kilo"
        interWrapper.style.display = (optionText === 'Kilo') ? 'block' : 'none';
    }

    if (tsControl && selectElement) {
        // Écoute l'événement "click" sur le widget Tomselected pour capter l'interaction
        tsControl.addEventListener('click', () => {
            setTimeout(toggleInterField, 100);
        });
        // Écoute également le changement sur le select caché
        selectElement.addEventListener('change', toggleInterField);
        // Applique l'état initial dès le chargement
        toggleInterField();
    }

    // --- Gestion du champ "stock" ---
    // Sélectionne le conteneur stock et le checkbox "hasStock"
    const stockWrapper = document.querySelector('.stock-wrapper');
    const hasStockCheckbox = document.querySelector('input[name$="[hasStock]"]');

    if (!stockWrapper) {
        console.error("Le conteneur stock-wrapper est introuvable.");
    }
    if (!hasStockCheckbox) {
        console.error("Le checkbox hasStock est introuvable.");
    }

    // Fonction pour afficher/masquer le conteneur "stock"
    function toggleStockField() {
        // Affiche le conteneur si le checkbox est coché, sinon le masque
        stockWrapper.style.display = hasStockCheckbox.checked ? 'block' : 'none';
    }

    if (hasStockCheckbox) {
        hasStockCheckbox.addEventListener('change', toggleStockField);
        // Applique l'état initial dès le chargement
        toggleStockField();
    }
// --- Gestion du champ "Texte Promo" ---
    // Le champ "Texte Promo" s'affichera uniquement si le champ "Promo" est coché.
    const discountCheckbox = document.querySelector('input[name$="[discount]"]');
    const discountTextWrapper = document.querySelector('.discountText-wrapper');

    if (discountCheckbox && discountTextWrapper) {
        function toggleDiscountTextField() {
            discountTextWrapper.style.display = discountCheckbox.checked ? 'block' : 'none';
        }
        discountCheckbox.addEventListener('change', toggleDiscountTextField);
        toggleDiscountTextField();
    } else {
        if (!discountCheckbox) console.error("Le checkbox discount est introuvable.");
        if (!discountTextWrapper) console.error("Le conteneur discountText-wrapper est introuvable.");
    }
});
