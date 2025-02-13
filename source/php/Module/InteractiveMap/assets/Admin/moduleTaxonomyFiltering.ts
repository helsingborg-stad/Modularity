import { GetFieldsInterface } from "./interface/getFields";
import { Taxonomies, Translations } from "./interface/interface";

class ModuleTaxonomyFiltering
{
    constructor(
        private getModuleDataInstance: GetFieldsInterface,
        private taxonomies: Taxonomies,
        private translations: Translations
    ) {
        this.setInitialValue();
        this.addPostTypeSelectListener();
    }

    private setInitialValue(): void {
        const postType = this.getModuleDataInstance.getPostTypeFieldSelectElement()?.value;
        if (postType) {
            this.updateTaxonomySelect(postType);
        }
    }

    private addPostTypeSelectListener(): void {
        this.getModuleDataInstance.getPostTypeFieldSelectElement()?.addEventListener('change', (event) => {
            const postType = (event.target as HTMLSelectElement).value;
            console.log(postType);
            this.updateTaxonomySelect(postType);
        });
    }

    private updateTaxonomySelect(postType: string): void {
        const selectElement = this.getModuleDataInstance.getTaxonomyFieldSelectElement();

        if (!selectElement) {
            return;
        }

        const selected = this.getSelectedOptionValue();
        const taxonomies = this.taxonomies[postType] ?? {};

        selectElement.innerHTML = '';
        selectElement.appendChild(this.createOptionElement('', this.translations['no-filter'] ?? 'No taxonomy filter', selected));

        for (const [value, label] of Object.entries(taxonomies)) {
            selectElement.appendChild(this.createOptionElement(value, label, selected));
        }
    }

    private getSelectedOptionValue(): string {
        return this.getModuleDataInstance.getField(this.getModuleDataInstance.getTaxonomyFieldKey()) ?? '';
    }

    private createOptionElement(value: string, label: string, selected: string): HTMLOptionElement {
        const option = document.createElement('option');
        option.value = value;
        option.textContent = label;
        option.selected = value === selected;

        return option;
    }
}

export default ModuleTaxonomyFiltering;