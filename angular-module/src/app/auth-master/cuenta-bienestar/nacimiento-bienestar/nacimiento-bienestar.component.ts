import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';

@Component({
  selector: 'app-nacimiento-bienestar',
  templateUrl: './nacimiento-bienestar.component.html',
  styleUrls: ['./nacimiento-bienestar.component.css']
})
export class NacimientoBienestarComponent implements OnInit {

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
