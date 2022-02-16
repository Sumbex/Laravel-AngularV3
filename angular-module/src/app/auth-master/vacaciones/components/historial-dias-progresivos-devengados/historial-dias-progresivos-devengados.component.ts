import { Component, Input, OnInit } from '@angular/core';
import { VacacionesService } from '../../../../servicios/vacaciones.service';

@Component({
  selector: 'app-historial-dias-progresivos-devengados',
  templateUrl: './historial-dias-progresivos-devengados.component.html',
  styleUrls: ['./historial-dias-progresivos-devengados.component.css']
})
export class HistorialDiasProgresivosDevengadosComponent implements OnInit {
  _vh_id:any;
  lista: any;
  @Input() set vac_historial_id(id:any){
    this._vh_id = id;
  }
  constructor(private _vacacionesService:VacacionesService) { }

  ngOnInit() {
    this.listar();
  }

  listar(){
    this._vacacionesService.historial_dias_progresivos_devengados(this._vh_id).subscribe(res=>{
      if(res.estado){
        this.lista=res.tabla;
      }
    });
  }

  metodo_mes(numero){
    switch (String(numero)) {
      case '1':
        return "Enero";
      break;
      case '2':
        return "Febrero";
      break;
      case '3':
        return "Marzo";
      break;
      case '4':
        return "Abril";
      break;
      case '5':
        return "Mayo";
      break;
      case '6':
        return "Junio";
      break;
      case '7':
        return "Julio";
      break;
      case '8':
        return "Agosto";

      break;
      case '9':
        return "Septiembre";
      break;
      case '10':
        return "Octubre";
      break;
      case '11':
        return "noviembre";
      break;
      case '12':
        return "Diciembre";
      break;

      default:
        break;
    }
  }

}
