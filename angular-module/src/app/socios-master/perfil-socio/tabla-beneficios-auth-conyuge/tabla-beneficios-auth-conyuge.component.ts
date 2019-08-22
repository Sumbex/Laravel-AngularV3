import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { PortalSociosService } from 'src/app/servicios/portal-socios.service';

@Component({
  selector: 'app-tabla-beneficios-auth-conyuge',
  templateUrl: './tabla-beneficios-auth-conyuge.component.html',
  styleUrls: ['./tabla-beneficios-auth-conyuge.component.css']
})
export class TablaBeneficiosAuthConyugeComponent implements OnInit {

  //modal tabla conyuge
  verTablaConyuge;

  //Objeto con los datos de la conyuge
  datosConyuge;

  //Loading tabla
  loadingTabla = false;

  constructor(config: NgbModalConfig, private modalService: NgbModal, private _portalSociosService: PortalSociosService) { }

  ngOnInit() {
  }

  verTablaModalConyuge(tablaConyuge){
    this.verTablaConyuge = this.modalService.open(tablaConyuge, { size: 'xl' });
    this.getDatosConyuge();
  }

  getDatosConyuge(){
    this.loadingTabla = true;
    this._portalSociosService.getDatosConyuge().subscribe(response => {
      if(response.estado == 'failed' || response.estado == 'failed_v'){
        alert(response.mensaje);
        this.verTablaConyuge.close();
        this.loadingTabla = false;
      }else{
        this.datosConyuge = response.conyuge[0];
        this.loadingTabla = false;
      }
    },
    error => {
      console.log(error);
      this.loadingTabla = false;
    });
  }

}
