import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { AcuerdoAsambleaService } from 'src/app/servicios/acuerdo-asamblea.service';
import { AniosService } from 'src/app/servicios/anios.service';

@Component({
  selector: 'app-acuerdo-asamblea',
  templateUrl: './acuerdo-asamblea.component.html',
  styleUrls: ['./acuerdo-asamblea.component.css']
})
export class AcuerdoAsambleaComponent implements OnInit {

  //Variables para los select de año y mes
  selectAnio;
  idAnioActual;

  //variable para asociar al modal
  modalVariable;

  constructor(config: NgbModalConfig, private modalService: NgbModal, private _acuerdoService: AcuerdoAsambleaService, private _fechasService: AniosService) {
    config.backdrop = 'static';
    config.keyboard = false;
  }

  ngOnInit() {
    //Cargar Años
    this.selectAnio = JSON.parse(localStorage.getItem('anios'));
  }

  abrirModalAcuerdos(modalMenu){
    this.modalVariable = this.modalService.open(modalMenu, {size: 'xl'});
    this.cargarFechasActuales();
  }

  cargarFechasActuales() {
    //Cargar id del Año actual
    this._fechasService.getAnioActual().subscribe(
      response => {
        this.idAnioActual = response.id;
      },
      error => {
        console.log(error);
      }
    )
  }

  changeAnio(valorSelect){
    this.idAnioActual = valorSelect.target.value;
  }

  getAcuerdoAsamblea(){
    this._acuerdoService.getAcuerdoAsamblea('id').subscribe(response => {
      if(response.estado == 'failed' || response.estado == 'failed_v'){
        alert(response.mensaje);
      }else{
        console.log("Hola amigos del yutu");
      }
    }, error => {
      console.log(error);
    });
  }

  getListaAcuerdoAsamblea(){
    this._acuerdoService.getAcuerdosAsambleaMesAnio(this.idAnioActual).subscribe(response => {
      if(response.estado == 'failed' || response.estado == 'failed_v'){
        alert(response.mensaje);
      }else{
        console.log("Hola amigos del yutu");
      }
    }, error => {
      console.log(error);
    });
  }

}
