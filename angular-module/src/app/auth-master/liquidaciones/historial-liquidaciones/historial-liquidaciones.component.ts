import { Component, OnInit } from '@angular/core';
import { LiquidacionJuanitoService } from 'src/app/servicios/liquidacion-juanito.service';
import { LiquidacionMarroService } from 'src/app/servicios/liquidacion-marro.service';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';

@Component({
  selector: 'app-historial-liquidaciones',
  templateUrl: './historial-liquidaciones.component.html',
  styleUrls: ['./historial-liquidaciones.component.css']
})
export class HistorialLiquidacionesComponent implements OnInit {
  //recatador de id select
  empleado = '';
  empleados = [];

  datosFormulario = {
    fecha: '',
    archivo: ''
  }

  //VARIABLE PARA GUARDAR LAS LIQUIDACIONES
  liquidaciones = [];

  load = false;
  loadTable = false;

  constructor(config: NgbModalConfig, private modalService: NgbModal, private _liq: LiquidacionJuanitoService, private _liquidacionesHistorial: LiquidacionMarroService) {
    config.backdrop = 'static';
    config.keyboard = false;
  }

  ngOnInit() {
    this.traerEmpleados();
  }

  open(content) {
    this.modalService.open(content, {size: 'lg'});
  }

  archivo(event) {
    this.datosFormulario.archivo = event.srcElement.files[0];
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

  setDatosLiquidacion(){
    this.load = true;
    this._liquidacionesHistorial.setDatosliquidacion(this.empleado, this.datosFormulario).subscribe(response=>{
      if(response.estado == 'success'){
        alert(response.mensaje);
        this.load = false;
        this.traerDatosLiquidacion();
      }else{
        this.load = false;
        alert("Compruebe que todos los campos del formulario han sido rellenados correctamente");
      }
    },error=>{
      this.load = false;
      console.log(error);
    });
  }

  actualizarLiquidacion(id,campo,input){
    if(campo == 'archivo'){
      input = this.datosFormulario.archivo;
    }
    this._liquidacionesHistorial.actualizarLiquidacion(id,campo,input).subscribe(response=>{
      if(response.estado == 'success'){
        alert(response.mensaje)
        this.traerDatosLiquidacion();
      }else{
        alert(response.mensaje);
      }
    },error=>{
      console.log(error);
    });
  }

  traerDatosLiquidacion(){
    this.loadTable  = true;
    this.liquidaciones = [];
    this._liquidacionesHistorial.getLiquidacionesEmpleado(this.empleado).subscribe(response=>{
      if(response.estado == 'success'){
        this.liquidaciones = response.liquidaciones;
        this.loadTable = false;
      }else{
        alert(response.mensaje)
        this.loadTable = false;
      }
    },error=>{
      console.log(error);
      this.loadTable = false;
    });
  }

}
