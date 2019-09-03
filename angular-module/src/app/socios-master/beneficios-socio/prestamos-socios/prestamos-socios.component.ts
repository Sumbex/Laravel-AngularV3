import { Component, OnInit } from '@angular/core';
import { PortalSociosService } from 'src/app/servicios/portal-socios.service';

@Component({
  selector: 'app-prestamos-socios',
  templateUrl: './prestamos-socios.component.html',
  styleUrls: ['./prestamos-socios.component.css']
})
export class PrestamosSociosComponent implements OnInit {

  //Objeto con los datos de la conyuge
  datosPrestamos;

  //Loading tabla
  loadingTabla = false;

  constructor(private _portalSociosService: PortalSociosService) { }

  ngOnInit() {
    this.getPrestamos();
  }

  getPrestamos(){
    this._portalSociosService.getPrestamosSocios().subscribe(response => {
      if(response.estado == 'failed' || response.estado == 'failed_v'){
        alert(response.mensaje);
      }else{
        this.datosPrestamos = response.prestamos;
        console.log(this.datosPrestamos);
      }
    });
  }

  getPagosPrestamos(id, tipo){
    this._portalSociosService.getPagosPrestamos(id, tipo).subscribe(response => {
      if(response.estado == 'failed' || response.estado == 'failed_v'){
        alert(response.mensaje);
      }else{
        console.log();
      }
    });
  }

}
