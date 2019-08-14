import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';

@Component({
  selector: 'app-tabla-beneficios-auth-socio',
  templateUrl: './tabla-beneficios-auth-socio.component.html',
  styleUrls: ['./tabla-beneficios-auth-socio.component.css']
})
export class TablaBeneficiosAuthSocioComponent implements OnInit {

  //Instancia del modal
  abrirTablaBeneficiosSocios;

  //Objeto con los datos del socio
  
  constructor(config: NgbModalConfig, 
    private modalService: NgbModal) {
    config.backdrop = 'static';
    config.keyboard = false;
  }

  ngOnInit() {
  }

  verTablaBeneficios(TablaBeneficios) {
    this.abrirTablaBeneficiosSocios = this.modalService.open(TablaBeneficios, { size: 'xl' });
  }

}
