import { Component, OnInit,Input } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { SociosService } from 'src/app/servicios/socios.service';

@Component({
  selector: 'app-tabla-beneficios-conyuge',
  templateUrl: './tabla-beneficios-conyuge.component.html',
  styleUrls: ['./tabla-beneficios-conyuge.component.css']
})
export class TablaBeneficiosConyugeComponent implements OnInit {

  abrirTablaBeneficiosConyuge;

  @Input () getIdSocio:'';
  @Input () getNombreSocio:'';
  traerDatosConyuge:object = [
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

  constructor(config: NgbModalConfig, 
  private modalService: NgbModal,
  private _SociosService:SociosService) {
  config.backdrop = 'static';
  config.keyboard = false;
                      }

  ngOnInit() {
  }
  verTablaBeneficios(TablaBeneficios) {
    this.abrirTablaBeneficiosConyuge = this.modalService.open(TablaBeneficios, { size: 'xl' });
    this.listarDatosConyuge();
  }

  listarDatosConyuge(){
    this._SociosService.getDatosConyuge(this.getIdSocio).subscribe((response) =>{
      console.log(response);
     if(response.estado == "failed"){
       alert('Error, El rut ingresado no existe en nuestra base de datos, pruebe digitando otro rut.');
       return false;
     }else{
       this.traerDatosConyuge = response.body;
     }
 
        error => {
       console.log(error);
       }
     }
   );
   }

}
