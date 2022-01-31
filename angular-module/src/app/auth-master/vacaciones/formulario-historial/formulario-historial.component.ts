import { Component, OnInit } from '@angular/core';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { LiquidacionJuanitoService } from '../../../servicios/liquidacion-juanito.service';

@Component({
  selector: 'app-formulario-historial',
  templateUrl: './formulario-historial.component.html',
  styleUrls: ['./formulario-historial.component.css']
})
export class FormularioHistorialComponent implements OnInit {

  blockIngreso: boolean = false;
  empleados: any;
  ventana: any;
  constructor(private liq:LiquidacionJuanitoService, private modalService2: NgbModal) { }

  ngOnInit() {
    this.traer_empleados();
  }

  traer_empleados(){
    this.liq.traer_empleados().subscribe(res=>{
      if(res.estado=="success"){
        this.empleados = res.empleado;
      }
    });
  }

  open_modal(modal){

    this.ventana = this.modalService2.open(modal, { size: 'xl' });

    }

}
