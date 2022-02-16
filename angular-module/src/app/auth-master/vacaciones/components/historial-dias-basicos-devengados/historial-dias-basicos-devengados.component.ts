import { Component, Input, OnInit } from '@angular/core';
import { VacacionesService } from '../../../../servicios/vacaciones.service';

@Component({
  selector: 'app-historial-dias-basicos-devengados',
  templateUrl: './historial-dias-basicos-devengados.component.html',
  styleUrls: ['./historial-dias-basicos-devengados.component.css']
})
export class HistorialDiasBasicosDevengadosComponent implements OnInit {
  @Input() vac_historial_id:any;
  listado: any;
  constructor(private _vacaciones:VacacionesService) { }

  ngOnInit() {

    this.listar();
  }

  listar(){
    this._vacaciones.historial_dias_basicos_devengados(this.vac_historial_id).subscribe((res:any)=>{

      if(res.estado){
        this.listado = res.listado;
      }
    })
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
