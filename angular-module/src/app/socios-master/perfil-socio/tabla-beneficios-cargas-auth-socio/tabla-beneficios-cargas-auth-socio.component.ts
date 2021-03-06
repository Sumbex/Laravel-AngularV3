import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { PortalSociosService } from 'src/app/servicios/portal-socios.service';

@Component({
  selector: 'app-tabla-beneficios-cargas-auth-socio',
  templateUrl: './tabla-beneficios-cargas-auth-socio.component.html',
  styleUrls: ['./tabla-beneficios-cargas-auth-socio.component.css']
})
export class TablaBeneficiosCargasAuthSocioComponent implements OnInit {

  //variable para el modal cargas
  abrirModalTablaCargas;

  //objeto que guardara los datos obtenidos de las cargas
  datosCargas;

  //Loading tabla
  loadingTabla = false;

  constructor(config: NgbModalConfig, private modalService: NgbModal, private _portalSociosService: PortalSociosService) {
    config.backdrop = 'static';
    config.keyboard = false;
  }

  ngOnInit() {
  }

  abrirModalCargas(modalCargas){
    this.abrirModalTablaCargas = this.modalService.open(modalCargas, {size: 'xl'});
    this.getDatosCargas();
  }

  openModalPDF(content) {
    this.modalService.open(content, {size: 'lg'});
  }

  getDatosCargas(){
    //Aquí se llamá al servicio para obtener los datos de las cargas
    this.loadingTabla = true;
    this._portalSociosService.getDatosCargas().subscribe(response => {
      if(response.estado == 'failed' || response.estado == 'failed_v'){
        alert(response.mensaje);
        this.abrirModalTablaCargas.close();
        this.loadingTabla = false;
      }else{
        this.datosCargas = response.cargas;
        this.loadingTabla = false;
      }
    },
    error => {
      console.log(error);
      this.loadingTabla = false;
    });
  }

}
