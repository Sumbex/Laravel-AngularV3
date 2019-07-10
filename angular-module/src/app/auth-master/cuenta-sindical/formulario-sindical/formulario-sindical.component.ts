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
    tipoCuentaSindicato: '2',
    descripcion: '',
    definicion: '2',
    monto: null
  }

  constructor(private _sindicalService: SindicalService) {
  
  }

  ngOnInit() {
    //Cargar Años
    this.selectAnio = JSON.parse(localStorage.getItem('anios'));

    //Cargar Meses
    this.selectMes = JSON.parse(localStorage.getItem('meses'));

    //Cargar definiciones
    this.selectDefinicion = JSON.parse(localStorage.getItem('definicion'));

    //Cargar detalles
    this.selectDetalle = JSON.parse(localStorage.getItem('detalle'));
    console.log(this.selectDefinicion);
    console.log(this.selectDetalle);
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

  changeDefinicion(evento){
    this.datosSindicales.definicion = evento.target.value;
    console.log("ID Definicion: " + this.datosSindicales.definicion);
  }

  changeDetalle(evento){
    this.datosSindicales.tipoCuentaSindicato = evento.target.value;
    console.log("ID Detalle: " + this.datosSindicales.tipoCuentaSindicato);

    //Captura del nuevo metodo janito
    if(evento.target.value = 3){
      console.log("llamar metodo");
    }
  }
}
