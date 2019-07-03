import { Component, OnInit } from '@angular/core';
import { TipoCuentasService } from '../servicios/tipo-cuentas.service';
import { AniosService } from '../servicios/anios.service';

@Component({
  selector: 'app-auth-master',
  templateUrl: './auth-master.component.html',
  styleUrls: ['./auth-master.component.css']
})
export class AuthMasterComponent implements OnInit {

  constructor(private _tipoCuentas: TipoCuentasService, private _getAnios: AniosService) { }

  ngOnInit() {
    //Guardar definicion
    this._tipoCuentas.getDefinicion().subscribe((res: any[]) => {
      localStorage.setItem('definicion', JSON.stringify(res));
    });
    //Guardar Detalle
    this._tipoCuentas.getTipoCuenta().subscribe((res: any[]) => {
      localStorage.setItem('detalle', JSON.stringify(res));
    });
    //Guardar Anios
    this._getAnios.getAnios().subscribe((res: any[]) => {
      localStorage.setItem('anios', JSON.stringify(res));
    });
    //Guardar Meses
    this._getAnios.getMeses().subscribe((res: any[]) => {
      localStorage.setItem('meses', JSON.stringify(res));
    });
  }

}