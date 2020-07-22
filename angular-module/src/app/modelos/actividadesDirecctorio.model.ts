import { Text } from '@angular/compiler/src/i18n/i18n_ast';

export class Actividades {

    constructor(
        public id?: string,
        public fecha?: string,
        public responsable?: string,
        public actividad?: string,
        public estado_actividad_id?: string,
        public estado?: string,
    ) { }

}