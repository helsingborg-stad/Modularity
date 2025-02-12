import { GetFieldsInterface } from "./interface/getFields";
import { Taxonomies, Translations } from "./interface/interface";

class ModuleTaxonomyFiltering
{
    constructor(
        private getModuleDataInstance: GetFieldsInterface,
        private taxonomies: Taxonomies,
        private translations: Translations
    ) {
    }
}