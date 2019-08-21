import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { PortalSociosService } from 'src/app/servicios/portal-socios.service';

@Component({
  selector: 'app-caja-chica',
  templateUrl: './caja-chica.component.html',
  styleUrls: ['./caja-chica.component.css']
})
export class CajaChicaComponent implements OnInit {

  //Variables para los select de año y mes
  selectAnio;
  selectMes;

  idAnioActual;
  idMesActual;

  //Variable para las cargas
  cargandoTabla = false;

  //Variable que almacena los valores obtenidos para la tabla caja chica
  datosCajaChica;

  //variable para asociar al modal
  modalCajaChica;

  constructor(config: NgbModalConfig, private modalService: NgbModal, private _portalSociosService: PortalSociosService) {
    config.backdrop = 'static';
    config.keyboard = false;
  }

  ngOnInit() {
    //Cargar Años
    this.selectAnio = JSON.parse(localStorage.getItem('anios'));

    //Cargar Meses
    this.selectMes = JSON.parse(localStorage.getItem('meses'));
  }

  openModalCajaChica(cajaChicaModal) {
    this.modalCajaChica = this.modalService.open(cajaChicaModal, { size: 'xl' });
    this.cargarFechasActuales();
  }

  cargarFechasActuales() {
    //Cargar id del Año actual
    this._portalSociosService.getAnioActual().subscribe(
      response => {
        this.idAnioActual = response.id;
      },
      error => {
        console.log(error);
      }
    )
    //Cargar id del Mes actual
    this._portalSociosService.getMesActual().subscribe(
      response => {
        this.idMesActual = response.id;
        this.cargarTablaCajaChica();
      },
      error => {
        console.log(error);
      }
    )
  }

  cargarTablaCajaChica(){
    this._portalSociosService.getCajaChica(this.idAnioActual, this.idMesActual).subscribe(response => {
      if(response.estado == 'failed' || response.estado == 'failed_v'){
        alert(response.mensaje);
      }else{
        console.log(response);
      }
    },
    error => {
      console.log(error);
    });
  }

}
