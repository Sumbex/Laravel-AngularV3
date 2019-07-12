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
  selectedImage:File;

  datosSindicales: Sindical ={
    fecha: '',
    nDocumento: '',
    archivoDocumento: null,
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
      console.log("Hola");
      this._sindicalService.ingresarValor(this.datosSindicales).subscribe(
        response => {
          console.log(response);
          this.datosSindicales.fecha = '';
          this.datosSindicales.nDocumento = '';
          this.datosSindicales.descripcion = '';
          this.datosSindicales.monto = null;
          alert("Guardado con exito");
        },
        error => {
          console.log(<any>error);
        }
      );
    }
  }

  onSelectImage(event) {
    this.datosSindicales.archivoDocumento = event.srcElement.files[0];
  }

  changeDefinicion(evento){
    this.datosSindicales.definicion = evento.target.value;
    console.log("ID Definicion: " + this.datosSindicales.definicion);
  }

  changeDetalle(evento){
    this.datosSindicales.tipoCuentaSindicato = evento.target.value;
    console.log("ID Detalle: " + this.datosSindicales.tipoCuentaSindicato);
    if(this.datosSindicales.tipoCuentaSindicato == '3'){
      var anio = this.datosSindicales.fecha.substring(0,4);
      var mes = this.datosSindicales.fecha.substring(5,7);
      this._sindicalService.getCalcularCajaChica(anio,mes).subscribe(
        response => {
          //console.log(response);
          if(response.estado == "success"){
            this.datosSindicales.monto = response.monto;
          }else{
            this.datosSindicales.monto = null;
          }
        },
        error => {
          console.log(<any>error);
        }
      );
    }else{
      this.datosSindicales.monto = null;
    }
  }

}
