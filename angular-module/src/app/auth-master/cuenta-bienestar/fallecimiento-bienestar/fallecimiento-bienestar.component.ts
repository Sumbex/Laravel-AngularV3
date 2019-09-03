import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';

@Component({
  selector: 'app-fallecimiento-bienestar',
  templateUrl: './fallecimiento-bienestar.component.html',
  styleUrls: ['./fallecimiento-bienestar.component.css']
})
export class FallecimientoBienestarComponent implements OnInit {

  modalPrincipal;

  constructor(config: NgbModalConfig, 
    private modalService: NgbModal) {

    config.backdrop = 'static';
    config.keyboard = false;
  }

  ngOnInit() {
  }

  openMensual(Mensual) {
    this.modalPrincipal = this.modalService.open(Mensual, { size: 'xl' });

  }
}
