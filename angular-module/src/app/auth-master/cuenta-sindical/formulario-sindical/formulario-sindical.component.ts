import { Component, OnInit } from '@angular/core';
import { AniosService } from 'src/app/servicios/anios.service';
import { Anios } from 'src/app/modelos/anios.model';
import { Meses } from 'src/app/modelos/meses.model';
import { Definicion } from 'src/app/modelos/definicion.model';
import { Detalle } from "src/app/modelos/detalle.model";
import { TipoCuentasService } from 'src/app/servicios/tipo-cuentas.service';
import { Sindical } from 'src/app/modelos/sindical.model';
import { SindicalService } from 'src/app/servicios/sindical.service';

@Component({
  selector: 'app-formulario-sindical',
  templateUrl: './formulario-sindical.component.html',
  styleUrls: ['./formulario-sindical.component.css']
})
export class FormularioSindicalComponent implements OnInit {
  selectAnio: Anios[] = [];
  selectMes: Meses[] = [];
  selectDefinicion: Definicion[] = [];
  selectDetalle: Detalle[] = [];

  datosSindicales: Sindical ={
    fecha: '',
    nDocumento: '',
    tipoCuentaSindicato: '1',
    descripcion: '',
    definicion: '1',
    monto: 0
  }

  constructor(private _aniosService: AniosService, private _tiposService: TipoCuentasService, private _sindicalService: SindicalService) {
  
  }

  ngOnInit() {
    //Cargar Años
    this._aniosService.getAnios().subscribe((res : any[]) => {
      this.selectAnio = res;
    });
    //Cargar Meses
    this._aniosService.getMeses().subscribe((res : any[]) => {
      this.selectMes = res;
    });
    //Cargar Definiciones
    this._tiposService.getDefinicion().subscribe((res : any[]) => {
      this.selectDefinicion = res;
      console.log(res);
    });
    //Cargar Detalles
    this._tiposService.getTipoCuenta().subscribe((res : any[]) => {
      this.selectDetalle = res;
    })
  }

  onSubmit({value, valid}: {value: Sindical, valid: boolean}) {
    if(!valid){
      console.log("Ingreso no valido revisar campos");
    }else{
      this._sindicalService.ingresarValor(this.datosSindicales).subscribe(
        response => {
          console.log(response);
        },
        error => {
          console.log(<any>error);
        }
      );
    }
  }

  tipoOperacionDefinicion(evento){
    this.datosSindicales.definicion = evento.target.value;
    console.log(this.datosSindicales.tipoCuentaSindicato);
  }

  tipoOperacionDetalle(evento){
    this.datosSindicales.tipoCuentaSindicato = evento.target.value;
    console.log(this.datosSindicales.definicion);
  }
}
