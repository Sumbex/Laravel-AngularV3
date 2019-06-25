import { Component, OnInit } from '@angular/core';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { AniosService } from 'src/app/servicios/anios.service';
import { Anios } from 'src/app/modelos/anios.model';
import { Meses } from 'src/app/modelos/meses.model';

@Component({
  selector: 'app-formulario-sindical',
  templateUrl: './formulario-sindical.component.html',
  styleUrls: ['./formulario-sindical.component.css']
})
export class FormularioSindicalComponent implements OnInit {
  model2: Date;
  selectAnio: Anios[] = [];
  selectMes: Meses[] = [];

  constructor(private modalService: NgbModal, private _aniosService: AniosService) {
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
  openCajaChica(CajaChica) {
    this.modalService.open(CajaChica, { size: 'lg' });
  }
  openPrestamo(Prestamo) {
    this.modalService.open(Prestamo, { size: 'lg' });
  }

  openCamping(Camping) {
    this.modalService.open(Camping, { size: 'lg' });
  }
}
