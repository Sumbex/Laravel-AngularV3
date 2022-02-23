import { Component, Input, OnInit } from "@angular/core";
import { VacacionesService } from "../../../../servicios/vacaciones.service";

@Component({
  selector: "app-dias-progresivos",
  templateUrl: "./dias-progresivos.component.html",
  styleUrls: ["./dias-progresivos.component.css"],
})
export class DiasProgresivosComponent implements OnInit {
  @Input() vacaciones_historial;
  listado: any;
  input: string="";
  constructor(private vacaciones: VacacionesService) {}

  ngOnInit() {
    this.listar();
  }

  listar() {
    this.vacaciones
      .lista_dias_progresivos(this.vacaciones_historial.id)
      .subscribe((res) => {
        this.listado = res;
        console.log(this.listado);
      });
  }

  agregar() {
    if(this.input.trim() == ""){
      alert("Ingrese una cantidad");
      return false;
    }else{

      const data = {
        id: this.vacaciones_historial.id,
        d_progresivos_devengados: this.input
      };
      this.vacaciones.crear_dia_progresivo(data).subscribe((res) => {
        if(res.estado){
          alert(res.mensaje);
          this.input="";
          this.listar();
        }
      });
    }
  }
}
