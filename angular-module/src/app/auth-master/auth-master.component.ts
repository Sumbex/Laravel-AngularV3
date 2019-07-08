import { Component, OnInit } from '@angular/core';
import { TipoCuentasService } from '../servicios/tipo-cuentas.service';
import { AniosService } from '../servicios/anios.service';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { map } from 'rxjs/operators';

@Component({
  selector: 'app-auth-master',
  templateUrl: './auth-master.component.html',
  styleUrls: ['./auth-master.component.css']
})
export class AuthMasterComponent implements OnInit {
  anios;
  filtroDefiniciones;
  filtroDetalle;
  filtroAnios;
  filtroMeses;

  constructor(private _tipoCuentas: TipoCuentasService, private _getAnios: AniosService, config: NgbModalConfig, private modalService: NgbModal) {

    config.backdrop = 'static';
    config.keyboard = false;  
    
   }

  ngOnInit() {
    //Guardar definicion
    this._tipoCuentas.getDefinicion().subscribe((res) => {
      this.filtroDefiniciones = res.map(({id,descripcion}) => ({id,descripcion}));
      localStorage.setItem('definicion', JSON.stringify(this.filtroDefiniciones));
    });
    //Guardar Detalle
    this._tipoCuentas.getTipoCuenta().subscribe((res) => {
      this.filtroDetalle = res.map(({id,descripcion}) => ({id,descripcion}));
      localStorage.setItem('detalle', JSON.stringify(this.filtroDetalle));
    });
    //Guardar Anios
    this._getAnios.getAnios().subscribe((res) => {
      this.filtroAnios = res.map(({id,descripcion}) => ({id,descripcion}));
      localStorage.setItem('anios', JSON.stringify(this.filtroAnios));
    });
    //Guardar Meses
    this._getAnios.getMeses().subscribe((res) => {
      this.filtroMeses = res.map(({id,descripcion}) => ({id,descripcion}));
      localStorage.setItem('meses', JSON.stringify(this.filtroMeses));
      document.getElementById("closeModalButton").click();
    });

    document.getElementById("openModalButton").click();
    this.verificarCarga();
  }

  verificarCarga(){
    if(localStorage.getItem('definicion') === null && localStorage.getItem('detalle') === null && localStorage.getItem('anios') === null && localStorage.getItem('meses') === null){
      console.log("Se estan cargando los datos en memoria");
    }
  }

  open(content) {
    this.modalService.open(content, {centered:true});
  }

}