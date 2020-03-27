import { Component, OnInit } from '@angular/core';
import { LiquidacionJuanitoService } from 'src/app/servicios/liquidacion-juanito.service';

@Component({
  selector: 'app-liquidacion',
  templateUrl: './liquidacion.component.html',
  styleUrls: ['./liquidacion.component.css']
})
export class LiquidacionComponent implements OnInit {

  //Variable para los empleados del select
  empleado = '';
  empleados = [];

  constructor(private _liq: LiquidacionJuanitoService,) { }

  ngOnInit() {
    this.traerEmpleados();
  }

  traerEmpleados() {
    this._liq.traer_empleados().subscribe(response => {
        if (response.estado == "success") {
          this.empleados = response.empleado;
        }
        error => {
          console.log(error);
        }
      }
    );
  }

  traerDatosLiquidacion(){
    console.log("test maximo");
  }

}
