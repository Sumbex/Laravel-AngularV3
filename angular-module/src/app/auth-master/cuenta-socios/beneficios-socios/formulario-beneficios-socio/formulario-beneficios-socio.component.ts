import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';

@Component({
  selector: 'app-formulario-beneficios-socio',
  templateUrl: './formulario-beneficios-socio.component.html',
  styleUrls: ['./formulario-beneficios-socio.component.css']
})
export class FormularioBeneficiosSocioComponent implements OnInit {

  abrirFormularioBeneficiosSocios;

  constructor(config: NgbModalConfig, 
    private modalService: NgbModal,) {
      config.backdrop = 'static';
      config.keyboard = false;
    }

  ngOnInit() {
  }

  verFormularioBeneficios(FormularioBeneficios) {
    this.abrirFormularioBeneficiosSocios = this.modalService.open(FormularioBeneficios, { size: 'xl' });
  }
}
