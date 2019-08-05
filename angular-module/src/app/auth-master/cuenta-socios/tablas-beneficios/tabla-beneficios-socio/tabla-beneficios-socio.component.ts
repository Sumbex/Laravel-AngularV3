import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';

@Component({
  selector: 'app-tabla-beneficios-socio',
  templateUrl: './tabla-beneficios-socio.component.html',
  styleUrls: ['./tabla-beneficios-socio.component.css']
})
export class TablaBeneficiosSocioComponent implements OnInit {

  abrirTablaBeneficiosSocios;

  constructor(config: NgbModalConfig, 
    private modalService: NgbModal,) {
      config.backdrop = 'static';
      config.keyboard = false;
    }

  ngOnInit() {
  }

  verTablaBeneficios(TablaBeneficios) {
    this.abrirTablaBeneficiosSocios = this.modalService.open(TablaBeneficios, { size: 'xl' });
  }


}
