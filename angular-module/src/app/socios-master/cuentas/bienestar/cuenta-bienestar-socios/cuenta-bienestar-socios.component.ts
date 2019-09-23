import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';

@Component({
  selector: 'app-cuenta-bienestar-socios',
  templateUrl: './cuenta-bienestar-socios.component.html',
  styleUrls: ['./cuenta-bienestar-socios.component.css']
})
export class CuentaBienestarSociosComponent implements OnInit {

  //variable para asociar al modal
  modalCajaChica;

  constructor(config: NgbModalConfig, private modalService: NgbModal) {
    config.backdrop = 'static';
    config.keyboard = false;
  }

  ngOnInit() {
  }

  openModalCajaChica(cajaChicaModal) {
    this.modalCajaChica = this.modalService.open(cajaChicaModal, { size: 'xl' });
  }


}
