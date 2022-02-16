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
  dias_basicos_devengados: any;
  dias_pro_dev: any;
  resultado_dias_pro_dev: number;
  vac_his_id:any;
  @Input() set vacaciones_historial(data:any){
    if(data){
      this.vh = data;
    }
  }
  @Input() set nueva_data(dato:boolean){
    if(dato==true){
      console.log("refreshh");
      this.listar();
      this.detectar_incremento_diasbasicos();
      this.dias_progresivos_devengados_disponibles();
    }
  }
  @Output() salida:EventEmitter<boolean> = new EventEmitter<boolean>();

  solicitudes:any;
  constructor(private _vacacionesService:VacacionesService, private modalService2: NgbModal,) {}

  ngOnInit() {
    this.listar();
    this.detectar_incremento_diasbasicos();
    this.dias_progresivos_devengados_disponibles();
  }
  dias_progresivos_devengados_disponibles() {
    this._vacacionesService.dias_progresivos_devengados_disponibles(this.vh.id).subscribe(res =>{
      if(res.estado){
        this.dias_pro_dev = res.valores[0];

        this.resultado_dias_pro_dev = Number(this.dias_pro_dev.dias_progresivos_devengados||0) - Number(this.dias_pro_dev.dias_progresivos_solicitud||0)
      }
    })
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
    this.ventana = this.modalService2.open(modal, { size: 'xl' });
  }

  detectar_incremento_diasbasicos(){
    this._vacacionesService.detectar_incremento_mensaual_diasbasicos(this.vh.id).subscribe(res=>{
       this.dias_basicos_devengados = Number(res[0].dias_basicos) - Number(res[0].dias_progresivos);
    })
  }
}
