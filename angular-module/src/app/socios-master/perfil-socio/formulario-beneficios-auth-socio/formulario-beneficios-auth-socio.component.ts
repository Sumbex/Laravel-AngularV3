import { Component, OnInit } from '@angular/core';
import { NgbModal, NgbModalConfig } from '@ng-bootstrap/ng-bootstrap';
import { PortalSociosService } from 'src/app/servicios/portal-socios.service';

@Component({
  selector: 'app-formulario-beneficios-auth-socio',
  templateUrl: './formulario-beneficios-auth-socio.component.html',
  styleUrls: ['./formulario-beneficios-auth-socio.component.css']
})
export class FormularioBeneficiosAuthSocioComponent implements OnInit {

  abrirFormularioBeneficiosSocios;

  idSocio = '5';

  InsertarBeneficiosSocio ={
  socio_id:'',
  direccion:'',
  telefono:'',
  celular:'',
  anexo:'',
  email_1:'',
  email_2:'',
  cargo_planta:'',
  cargo_comision_sindicato:'',
  numero_cuenta:'',
  tipo_cuenta_banco_id:'',
  banco:'',
  isapre_fonasa:'',
  grupo_sangre:'',
  casa_propia:'',
  rol_turno:'',
  estado_civil_id:'',
  conyuge:'',
  }

  constructor(config: NgbModalConfig, 
    private modalService: NgbModal,
    private _portalSociosService: PortalSociosService) {
      config.backdrop = 'static';
      config.keyboard = false;
    }

  ngOnInit() {
  }

  verFormularioBeneficios(FormularioBeneficios) {
    this.abrirFormularioBeneficiosSocios = this.modalService.open(FormularioBeneficios, { size: 'xl' });
    console.log(this.idSocio);
  }
  
  guardarDatosSocio(){
    this._portalSociosService.setDatosBasicosSocios(this.InsertarBeneficiosSocio).subscribe(
      response => {
        if(response.estado == "failed" || response.estado == "failed_v"){
          alert(response.mensaje);
        }else{
          alert(response.mensaje);
        }
      },
      error => {
        console.log(error);
      }
    );
  }

}
