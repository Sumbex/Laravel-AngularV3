import { Component, OnInit } from '@angular/core';
import { PortalSociosService } from 'src/app/servicios/portal-socios.service';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';

@Component({
  selector: 'app-formulario-beneficios-conyuge-auth-socio',
  templateUrl: './formulario-beneficios-conyuge-auth-socio.component.html',
  styleUrls: ['./formulario-beneficios-conyuge-auth-socio.component.css']
})
export class FormularioBeneficiosConyugeAuthSocioComponent implements OnInit {

  //Modal
  abrirFormularioBeneficiosConyuge;

  //Objeto con los datos del conyuge
  datosConyuge = {
    rut: '',
    fechaNacimiento: '',
    nombres: '',
    apellidoPaterno: '',
    apellidoMaterno: '',
    direccion: '',
    celular: '',
    archivoDocumento: ''
  }

  //variable para bloquear el doble ingreso
  blockIngreso = false;

  constructor(config: NgbModalConfig, private modalService: NgbModal, private _portalSociosService: PortalSociosService) {
    config.backdrop = 'static';
    config.keyboard = false;
  }

  ngOnInit() {
  }

  verFormularioConyuge(formularioBeneficiosConyuge){
    this.abrirFormularioBeneficiosConyuge = this.modalService.open(formularioBeneficiosConyuge, { size: 'xl' });
  }

  onSelectImage(event) {
    this.datosConyuge.archivoDocumento = event.srcElement.files[0];
  }

  setDatosConyuge(){
    //Llamar al servicio para ingresar los valores del objeto
    this.blockIngreso = true;
    this._portalSociosService.setDatosConyugeSocio(this.datosConyuge).subscribe(
      response => {
        if(response.estado == 'failed' || response.estado == 'failed_v'){
          alert(response.mensaje);
          this.blockIngreso = false;
        }else{
          alert('Se ha ingresado correctamente la conyuge en la base de datos');
          this.blockIngreso = false;
        }
      },
      error =>{
        console.log(error);
        this.blockIngreso = false;
      }
    );
    console.log(this.datosConyuge);
  }

}
