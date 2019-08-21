import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { PortalSociosService } from 'src/app/servicios/portal-socios.service';

@Component({
  selector: 'app-sindical',
  templateUrl: './sindical.component.html',
  styleUrls: ['./sindical.component.css']
})
export class SindicalComponent implements OnInit {

  //Variables para los select de año y mes
  selectAnio;
  selectMes;

  idAnioActual;
  idMesActual;

  //variable asociada al modalSindical
  modalTablaSindical;

  constructor(config: NgbModalConfig, private modalService: NgbModal, private _fechasService: PortalSociosService) {
    config.backdrop = 'static';
    config.keyboard = false;
  }

  ngOnInit() {
    //Cargar Años
    this.selectAnio = JSON.parse(localStorage.getItem('anios'));

    //Cargar Meses
    this.selectMes = JSON.parse(localStorage.getItem('meses'));
  }

  openModalTablaSindical(tablaSindical){
    this.modalTablaSindical = this.modalService.open(tablaSindical, {size: 'xl'});
  }

  cargarFechasActuales(){
    //Cargar id del Año actual
    this._fechasService.getAnioActual().subscribe(
     response => {
       this.idAnioActual = response.id;
     },
     error => {
       console.log(error);
     }
   )

   //Cargar id del Mes actual
   this._fechasService.getMesActual().subscribe(
     response => {
       this.idMesActual = response.id;             
     },
     error => {
       console.log(error);
     }
   )
 }

}
