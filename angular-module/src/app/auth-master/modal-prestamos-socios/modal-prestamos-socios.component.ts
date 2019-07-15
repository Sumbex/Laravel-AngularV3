import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';

@Component({
  selector: 'app-modal-prestamos-socios',
  templateUrl: './modal-prestamos-socios.component.html',
  styleUrls: ['./modal-prestamos-socios.component.css']
})
export class ModalPrestamosSociosComponent implements OnInit {

  constructor(config: NgbModalConfig, private modalService: NgbModal) {
    config.backdrop = 'static';
    config.keyboard = false;

  }
  ngOnInit() {
  }

  openPrestamo(Prestamo) {
    this.modalService.open(Prestamo, { size: 'lg' });
  }

}
