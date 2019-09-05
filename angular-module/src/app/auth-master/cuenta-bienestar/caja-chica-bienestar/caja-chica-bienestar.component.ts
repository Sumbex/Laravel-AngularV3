import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';

@Component({
  selector: 'app-caja-chica-bienestar',
  templateUrl: './caja-chica-bienestar.component.html',
  styleUrls: ['./caja-chica-bienestar.component.css']
})
export class CajaChicaBienestarComponent implements OnInit {

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
