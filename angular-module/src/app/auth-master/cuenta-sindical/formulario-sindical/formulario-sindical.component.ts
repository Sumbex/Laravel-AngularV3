import { Component, OnInit } from '@angular/core';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';

@Component({
  selector: 'app-formulario-sindical',
  templateUrl: './formulario-sindical.component.html',
  styleUrls: ['./formulario-sindical.component.css']
})
export class FormularioSindicalComponent implements OnInit {
  model2: Date;

  constructor(private modalService: NgbModal) { }

  ngOnInit() {
  }

  openMensual(Mensual) {
    this.modalService.open(Mensual, { size: 'lg' });
  }
  openCajaChica(CajaChica) {
    this.modalService.open(CajaChica, { size: 'lg' });
  }
}
