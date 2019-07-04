import { Component, OnInit } from '@angular/core';
import { Anios } from 'src/app/modelos/anios.model';
import { Meses } from 'src/app/modelos/meses.model';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { AniosService } from 'src/app/servicios/anios.service';
import { InicioCierre } from 'src/app/modelos/inicioCierre.model';

@Component({
  selector: 'app-modal-inicio-mes',
  templateUrl: './modal-inicio-mes.component.html',
  styleUrls: ['./modal-inicio-mes.component.css']
})
export class ModalInicioMesComponent implements OnInit {
  selectAnio: Anios[] = [];
  selectMes: Meses[] = [];

  datosInicioMes: InicioCierre ={
     anio: '',
     mes: '',
     monto: '',
     inicial: ''
  }

  constructor(config: NgbModalConfig, private modalService: NgbModal) {
    config.backdrop = 'static';
    config.keyboard = false;
    
  }

  ngOnInit() {
    //Cargar Años
    this.selectAnio = JSON.parse(localStorage.getItem('anios'));

    //Cargar Meses
    this.selectMes = JSON.parse(localStorage.getItem('meses'));
  }

  openMensual(Mensual) {
    this.modalService.open(Mensual, { size: 'lg' });
  }

}
