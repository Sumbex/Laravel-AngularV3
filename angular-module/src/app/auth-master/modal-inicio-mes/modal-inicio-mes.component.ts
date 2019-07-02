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

  constructor(config: NgbModalConfig, private modalService: NgbModal, private _aniosService: AniosService) {
    config.backdrop = 'static';
    config.keyboard = false;
    
  }

  ngOnInit() {
    //Cargar Años
    this._aniosService.getAnios().subscribe((res : any[]) => {
      this.selectAnio = res;
    });
    //Cargar Meses
    this._aniosService.getMeses().subscribe((res : any[]) => {
      this.selectMes = res;
    });
  }

  openMensual(Mensual) {
    this.modalService.open(Mensual, { size: 'lg' });
  }

}
