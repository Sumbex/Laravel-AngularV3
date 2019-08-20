import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { PortalSociosService } from 'src/app/servicios/portal-socios.service';

@Component({
  selector: 'app-cambio-informacion-personal-socio',
  templateUrl: './cambio-informacion-personal-socio.component.html',
  styleUrls: ['./cambio-informacion-personal-socio.component.css']
})
export class CambioInformacionPersonalSocioComponent implements OnInit {

  //Variable para definir el modal
  abrirModalInfoPersonal;

  //Objeto que traera los datos iniciales
  infoPersonal;

  //variable para mostrar el loading
  cargandoTabla = false;

  //bloquear ingreso
  blockUpdate = false;

  constructor(config: NgbModalConfig, private modalService: NgbModal, private _portalSociosService: PortalSociosService) {
    config.backdrop = 'static';
    config.keyboard = false;
  }

  ngOnInit() {   
  }

  vertTablaInfoPersonal(infoPersonal){
    this.abrirModalInfoPersonal = this.modalService.open(infoPersonal, {size: 'xl'});
    this.getInfoPersonal();
  }

  getInfoPersonal(){
    //Aquí se llama al servicio para obtener los datos personales
    this.cargandoTabla = true;
    this._portalSociosService.getSocioLogeado().subscribe(response => {
      this.infoPersonal = response;
      this.cargandoTabla = false;
    },
    error => {
      console.log(error);
      this.cargandoTabla = false;
    });
  }

  setNuevosDatos(valor, input){
    //Aquí se llama al servicio para ingresar los nuevos datos
    this.blockUpdate = true;
    this._portalSociosService.updateInfoSocio(valor.value, input).subscribe(response => {
      if(response.estado == 'failed' || response.estado == 'failed_v'){
        alert(response.mensaje);
        this.blockUpdate = false;
      }else{
        alert('¡Dato actualizado correctamente!');
        this.blockUpdate = false;
        window.location.reload();
      }
    },
    error => {
      console.log(error);
      this.blockUpdate = false;
    });
  }
}
