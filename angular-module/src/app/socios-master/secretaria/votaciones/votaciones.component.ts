import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { VotacionesBryanmService } from 'src/app/votaciones-bryanm.service';
import { AniosService } from 'src/app/servicios/anios.service';
import { Chart } from 'chart.js';

@Component({
  selector: 'app-votaciones',
  templateUrl: './votaciones.component.html',
  styleUrls: ['./votaciones.component.css']
})
export class VotacionesComponent implements OnInit {

  idAnioActual;
  idMesActual;
  idTipoActual = 0;

  selectAnio;
  selectMes;
  selectTipo;

  constructor(config: NgbModalConfig, private modalService: NgbModal, private _votaciones: VotacionesBryanmService, private _fechasService: AniosService) {
    config.backdrop = 'static';
    config.keyboard = false;
  }

  ngOnInit() {
  }

  abrirModalActivos(modal) {
    this.modalService.open(modal, { size: 'xl' });
  }

}
