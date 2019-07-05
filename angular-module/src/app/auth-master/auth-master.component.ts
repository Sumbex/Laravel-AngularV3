import { Component, OnInit } from '@angular/core';
import { TipoCuentasService } from '../servicios/tipo-cuentas.service';
import { AniosService } from '../servicios/anios.service';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';

@Component({
  selector: 'app-auth-master',
  templateUrl: './auth-master.component.html',
  styleUrls: ['./auth-master.component.css']
})
export class AuthMasterComponent implements OnInit {

  constructor(private _tipoCuentas: TipoCuentasService, private _getAnios: AniosService, config: NgbModalConfig, private modalService: NgbModal) {
    config.backdrop = 'static';
    config.keyboard = false;
   }

  ngOnInit() {

    //DomContentLoaded
    window.addEventListener('load', (event) => {
    document.getElementById("closeModalButton").click();
      
  });

    //Guardar definicion
    this._tipoCuentas.getDefinicion().subscribe((res) => {
      localStorage.setItem('definicion', JSON.stringify(res));
    });
    //Guardar Detalle
    this._tipoCuentas.getTipoCuenta().subscribe((res) => {
      localStorage.setItem('detalle', JSON.stringify(res));
    });
    //Guardar Anios
    this._getAnios.getAnios().subscribe((res) => {
      localStorage.setItem('anios', JSON.stringify(res));
    });
    //Guardar Meses
    this._getAnios.getMeses().subscribe((res) => {
      localStorage.setItem('meses', JSON.stringify(res));
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