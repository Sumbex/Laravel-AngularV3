import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { PortalSociosService } from 'src/app/servicios/portal-socios.service';

@Component({
  selector: 'app-beneficios-cobrados',
  templateUrl: './beneficios-cobrados.component.html',
  styleUrls: ['./beneficios-cobrados.component.css']
})
export class BeneficiosCobradosComponent implements OnInit {

  //variable para asociar al modal
  modalPagosPrestamos;

  //Objeto con los datos del Prestamo
  datosNacidos;
  datosFallecidos;
  datosMedicos;

  //Variables para comrpobar si no hay ningún beneficio
  contadorBeneficios = 3;

  //Loading tabla
  loadingTabla = true;

  constructor(config: NgbModalConfig, private modalService: NgbModal, private _portalSociosService: PortalSociosService) {
    config.backdrop = 'static';
    config.keyboard = false;
  }

  ngOnInit() {
    this.getNacimientosCobrados();
    this.getFallecimientosCobrados();
    this.getGastosMedicosCobrados();
  }

  openPDF(content) {
    this.modalService.open(content, {size: 'lg'});
  }

  getNacimientosCobrados(){
    this._portalSociosService.getBeneficiosNacimientosCobrados().subscribe(response => {
      if(response.estado == 'failed' || response.estado == 'failed_v'){
        console.log(response.mensaje);
        this.contadorBeneficios--;
      }else{
        this.datosNacidos = response;
        console.log(this.datosNacidos);
      }
    },
    error => {
        console.log(error);
    });
  }

  getFallecimientosCobrados(){
    this._portalSociosService.getBeneficiosFalleciomientosCobrados().subscribe(response => {
      if(response.estado == 'failed' || response.estado == 'failed_v'){
        console.log(response.mensaje);
        this.contadorBeneficios--;
      }else{
        this.datosFallecidos = response;
        console.log(this.datosFallecidos);
      }
    },
    error => {
      console.log(error);
    });
  }

  getGastosMedicosCobrados(){
    this._portalSociosService.getBeneficiosGastosMedicos().subscribe(response => {
      if(response.estado == 'failed' || response.estado == 'failed_v'){
        console.log(response.mensaje);
        this.contadorBeneficios--;
      }else{
        this.datosMedicos = response;
        console.log(this.datosMedicos);
      }
    },
    error => {
      console.log(error);
    });
  }

  limpiarTabla(){
    this.datosNacidos = '';
    this.datosFallecidos = '';
    this.datosMedicos = '';
  }

}
