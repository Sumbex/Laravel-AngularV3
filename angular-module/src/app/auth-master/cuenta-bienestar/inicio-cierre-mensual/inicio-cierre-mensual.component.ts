import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';

@Component({
  selector: 'app-inicio-cierre-mensual',
  templateUrl: './inicio-cierre-mensual.component.html',
  styleUrls: ['./inicio-cierre-mensual.component.css']
})
export class InicioCierreMensualComponent implements OnInit {

  modalPrincipal;

  constructor(config: NgbModalConfig, 
    private modalService: NgbModal) {

    config.backdrop = 'static';
    config.keyboard = false;
  }

  ngOnInit() {
  }

  openMensual(Mensual) {
    this.modalPrincipal = this.modalService.open(Mensual, { size: 'lg' });


    //console.log(document.getElementById('anio').options.value);
    
  }

}
