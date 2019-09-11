import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { PortalSociosService } from 'src/app/servicios/portal-socios.service';

@Component({
  selector: 'app-fondos-mutuos-socio',
  templateUrl: './fondos-mutuos-socio.component.html',
  styleUrls: ['./fondos-mutuos-socio.component.css']
})
export class FondosMutuosSocioComponent implements OnInit {

  //variable para asociar al modal
  modalPagosPrestamos;

  //Objeto con los datos del Prestamo
  datosFondosMutuos;

  //Bloquear Muestro de pagos

  //Loading tabla
  loadingTabla = true;


  constructor(config: NgbModalConfig, private modalService: NgbModal, private _portalSociosService: PortalSociosService) {
    config.backdrop = 'static';
    config.keyboard = false;
  }

  ngOnInit() {
    this.getFondosMutuos();
  }

  openPDF(content) {
    this.modalService.open(content, {size: 'lg'});
  }

  getFondosMutuos(){
    this._portalSociosService.getFondosMutuos().subscribe(response => {
      if(response.estado == 'failed' || response.estado == 'failed_v'){
        alert(response.mensaje);
      }else{
        this.datosFondosMutuos = response;
      }
    },
    error=> {
      console.log(error);
    })
  }

}
