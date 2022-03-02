import { Component, EventEmitter, Input, OnInit, Output } from "@angular/core";
import { FormGroup, FormBuilder, Validators } from "@angular/forms";
import { VacacionesService } from '../../../../servicios/vacaciones.service';

@Component({
  selector: "app-editar-solicitud",
  templateUrl: "./editar-solicitud.component.html",
  styleUrls: ["./editar-solicitud.component.css"],
})
export class EditarSolicitudComponent implements OnInit {
  private _sol: any;
  @Output() salida: EventEmitter<boolean> = new EventEmitter<boolean>();
  btn: boolean = false;

  @Input() set solicitud(data: any) {
    if (data) {
      this._sol = data;
    }
  }
  form: FormGroup;
  constructor(private fb: FormBuilder, private _vacacionesService:VacacionesService) {}

  ngOnInit() {
    this.construir_form();
    this.llenar_form();
  }

  construir_form() {
    this.form = this.fb.group({
      fecha_solicitud: ["", Validators.required],
      fecha_inicio: ["", Validators.required],
      fecha_termino: ["", Validators.required],
      dias_legales: ["", Validators.required],
      dias_progresivos: ["", Validators.required],
    });
  }

  llenar_form() {
    this.form.setValue({
      fecha_solicitud: this.fecha_latina_a_gringa(this._sol.fecha_solicitud),
      fecha_inicio: this.fecha_latina_a_gringa(this._sol.fecha_inicio),
      fecha_termino: this.fecha_latina_a_gringa(this._sol.fecha_termino),
      dias_legales: this._sol.dias_legales,
      dias_progresivos: this._sol.dias_progresivos,
    });
  }

  fecha_latina_a_gringa(fecha: string) {
    const _fecha = fecha.split("/");
    const _dias = _fecha[0];
    const _mes = _fecha[1];
    const _anio = _fecha[2];

    return _anio + "-" + _mes + "-" + _dias;
  }

  enviar() {
    this.btn = true;
    if (this.form.valid) {
      console.log(this.form.value);
      const data ={
        "id": this._sol.id,
        "fecha_solicitud": this.form.controls.fecha_solicitud.value,
        "fecha_inicio": this.form.controls.fecha_inicio.value,
        "fecha_termino": this.form.controls.fecha_termino.value,
        "dias_legales": this.form.controls.dias_legales.value,
        "dias_progresivos": this.form.controls.dias_progresivos.value,
        "vacaciones_historial_id": this._sol.vac_historial_id
      }


      this._vacacionesService.editar_solicitud_vacaciones(data).subscribe(res=>{
        if(res.estado){
          this.btn = false;
          alert(res.mensaje)
          this.salida.emit(true);
        }else{
          this.btn = false;
          alert(res.mensaje)
          this.salida.emit(false);
        }
      })
    } else {
      this.btn = false;
      alert("Faltan campos por llenar");
    }
  }
}
