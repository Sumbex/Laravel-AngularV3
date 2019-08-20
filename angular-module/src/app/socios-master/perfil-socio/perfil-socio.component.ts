import { Component, OnInit } from '@angular/core';
import { PortalSociosService } from 'src/app/servicios/portal-socios.service';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';

@Component({
  selector: 'app-perfil-socio',
  templateUrl: './perfil-socio.component.html',
  styleUrls: ['./perfil-socio.component.css']
})
export class PerfilSocioComponent implements OnInit {

  //variable con la ruta del resumen
  rutaResumen;

  constructor(config: NgbModalConfig, private modalService: NgbModal, private _portalSocioService: PortalSociosService) {
    config.backdrop = 'static';
    config.keyboard = false;
  }

  ngOnInit() {
  }

  openPDF(content) {
    this.modalService.open(content, {size: 'lg'});
  }

  getResumen(){
    console.log('test');
    //Llamar al servicio que trae el link del resumen PDF
    this._portalSocioService.getResumenSocio().subscribe(response => {
      if(response.estado == 'failed' || response.estado == 'failed_v'){
        alert(response.mensaje);
      }else{
        this.rutaResumen = response.archivo[0];
        document.getElementById('showModal').click();
      }
    },
    error => {
      console.log(error);
    });
  }

}
