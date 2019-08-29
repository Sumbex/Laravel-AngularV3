import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';

@Component({
  selector: 'app-gastos-medicos-bienestar',
  templateUrl: './gastos-medicos-bienestar.component.html',
  styleUrls: ['./gastos-medicos-bienestar.component.css']
})
export class GastosMedicosBienestarComponent implements OnInit {

  modalPrincipal;

  constructor(config: NgbModalConfig, 
    private modalService: NgbModal) {

    config.backdrop = 'static';
    config.keyboard = false;
  }

  ngOnInit() {
  }

  openModal(Modal) {
    this.modalPrincipal = this.modalService.open(Modal, { size: 'xl' });

  }
}
