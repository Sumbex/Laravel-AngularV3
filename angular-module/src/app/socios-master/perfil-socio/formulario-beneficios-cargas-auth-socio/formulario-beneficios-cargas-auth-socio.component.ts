import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { PortalSociosService } from 'src/app/servicios/portal-socios.service';

@Component({
  selector: 'app-formulario-beneficios-cargas-auth-socio',
  templateUrl: './formulario-beneficios-cargas-auth-socio.component.html',
  styleUrls: ['./formulario-beneficios-cargas-auth-socio.component.css']
})
export class FormularioBeneficiosCargasAuthSocioComponent implements OnInit {

  //variable para el modal de la carga
  abrirModalFormularioCarga;

  //Datos de la carga
  datosCargas = {
    tipoCargaId:'',
    rut:'',
    fechaNacimiento:'',
    nombres:'',
    apellidoPaterno:'',
    apellidoMaterno:'',
    direccion:'',
    celular:'',
    establecimiento:'',
    }

  constructor(config: NgbModalConfig, private modalService: NgbModal, private _portalSociosService: PortalSociosService) {
    config.backdrop = 'static';
    config.keyboard = false;
  }

  ngOnInit() {
  }

  verModalCargas(formularioCargas){
    this.abrirModalFormularioCarga = this.modalService.open(formularioCargas, { size: 'xl' });
  }

  setDatosCarga(){
    //Aquí se llamá al servicio para ingresar los datos de la carga
    console.log(this.datosCargas);
    this._portalSociosService.setDatosCargas(this.datosCargas).subscribe(response => {
      if(response.estado == 'failed' || response.estado == 'failed_v'){
        alert(response.mensaje);
      }else{
        alert('Ingreso de la carga correcto');
      }
    },
    error => {
      console.log(error);
    });
  }

}
