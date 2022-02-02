import { Component, EventEmitter, Input, OnInit, Output } from "@angular/core";
import { NgbModal } from "@ng-bootstrap/ng-bootstrap";
import { VacacionesService } from '../../../../servicios/vacaciones.service';

@Component({
  selector: "app-listado-historial-feriados",
  templateUrl: "./listado-historial-feriados.component.html",
  styleUrls: ["./listado-historial-feriados.component.css"],
})
export class ListadoHistorialFeriadosComponent implements OnInit {
  vh:any;
  ventana: any;
  @Input() set vacaciones_historial(data:any){
    if(data){
      this.vh = data;
    }
  }
  @Input() set nueva_data(dato:boolean){
    if(dato==true){
      console.log("refreshh");
      this.listar();
    }
  }
  @Output() salida:EventEmitter<boolean> = new EventEmitter<boolean>();

  solicitudes:any;
  constructor(private _vacacionesService:VacacionesService, private modalService2: NgbModal,) {}

  ngOnInit() {
    this.listar();
  }

  listar(){
    this._vacacionesService.listar_solicitud_vacaciones(this.vh.id).subscribe(res =>{
      if(res){
        this.solicitudes = res.solicitudes;
        this.salida.emit(false);
      }
    })
  }
  open_modal(modal){
    this.ventana = this.modalService2.open(modal, { size: 'lg' });
  }
}
