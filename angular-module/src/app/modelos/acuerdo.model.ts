export class Acuerdo{

    constructor(
        public id?: string,
        public titulo?: string,
        public fecha?: string,
        public contenido?: string,
        public descripcion_cambio_estado?: string,
        public id_tipo_acuerdo?: number,
        public id_estado_acuerdo?: number,
        public id_usuario?: string,
        public nombre?: string,
        public nuevo?: string
    ){

    }

}