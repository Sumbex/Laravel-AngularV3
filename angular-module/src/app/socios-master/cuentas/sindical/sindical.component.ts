import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { AniosService } from 'src/app/servicios/anios.service';

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

  constructor(config: NgbModalConfig, private modalService: NgbModal, private _fechasService: AniosService) { }

  ngOnInit() {
  }

  openModalTablaSindical(tablaSindical){
    this.modalTablaSindical = this.modalService.open(tablaSindical, {size: 'xl'});
  }

  cargarFechasActuales(){
    //Cargar id del Año actual
    this._fechasService.getAnioActual().subscribe(
     response => {
       this.selectAnio = response;
     },
     error => {
       console.log(error);
     }
   )

   //Cargar id del Mes actual
   this._fechasService.getMesActual().subscribe(
     response => {
       this.selectMes = response;             
     },
     error => {
       console.log(error);
     }
   )
 }

}
