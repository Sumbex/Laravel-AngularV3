import { Component, OnInit, Input } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { SociosService } from 'src/app/servicios/socios.service';

@Component({
  selector: 'app-tabla-beneficios-padres-suegros',
  templateUrl: './tabla-beneficios-padres-suegros.component.html',
  styleUrls: ['./tabla-beneficios-padres-suegros.component.css']
})
export class TablaBeneficiosPadresSuegrosComponent implements OnInit {

  abrirTablaBeneficiosPS;

  @Input () getIdSocio:'';
  @Input () getNombreSocio:'';
  traerDatosPS:object = [
                        'relacion',
                        'rut',
                        'fecha_nacimiento',
                        'nombres',
                        'apellido_paterno',
                        'apellido_materno',
                        'direccion',
                        'celular',
                        'created_at',
                        'updated_at',

                        ];

  vista_tabla: boolean = false;

  constructor(config: NgbModalConfig, 
    private modalService: NgbModal,
    private _SociosService:SociosService) {
    config.backdrop = 'static';
    config.keyboard = false;
                        }

  ngOnInit() {
  }

  verTablaBeneficios(TablaBeneficios) {
    this.abrirTablaBeneficiosPS = this.modalService.open(TablaBeneficios, { size: 'xl' });
    this.listarDatosPS();
  }

  limpiar_tabla(){
    this.vista_tabla = false;
  }

  listarDatosPS(){
    this._SociosService.getDatosRelacion(this.getIdSocio).subscribe((response) =>{
      console.log(response);
     if(response.estado == "failed"){
      //  alert('Error, El rut ingresado no existe en nuestra base de datos, pruebe digitando otro rut.');
       this.vista_tabla = false;
       alert(response.mensaje);
       this.abrirTablaBeneficiosPS.close();
       return false;
     }else{
       this.traerDatosPS = response.body;
       this.vista_tabla = true;
     }
 
        error => {
       console.log(error);
       this.vista_tabla = false;
       }
     }
   );
   }

}
