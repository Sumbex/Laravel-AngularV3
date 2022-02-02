import { Component, EventEmitter, Input, OnInit, Output } from "@angular/core";
import { FormGroup, FormBuilder, Validators } from "@angular/forms";
import { VacacionesService } from "../../../../servicios/vacaciones.service";

@Component({
  selector: "app-formulario-solicitud-vacaciones",
  templateUrl: "./formulario-solicitud-vacaciones.component.html",
  styleUrls: ["./formulario-solicitud-vacaciones.component.css"],
})
export class FormularioSolicitudVacacionesComponent implements OnInit {
  form: FormGroup;
  vh: any;
  doc: any;
  @Input() set vacaciones_historial(data: any) {
    if (data) {
      this.vh = data;
    }
  }
  @Output() salida:EventEmitter<boolean> = new EventEmitter<boolean>();
  blockIngreso: boolean = false;
  constructor(
    private fb: FormBuilder,
    private _vacacionesService: VacacionesService
  ) {}

  ngOnInit() {
    this.construir_form();
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

  enviar() {
    if (this.form.valid) {
      console.log(this.form.value);
      const fd = new FormData();
      fd.append("vacaciones_historial_id", this.vh.id);
      fd.append("fecha_solicitud", this.form.controls.fecha_solicitud.value);
      fd.append("fecha_inicio", this.form.controls.fecha_inicio.value);
      fd.append("fecha_termino", this.form.controls.fecha_termino.value);
      fd.append("dias_legales", this.form.controls.dias_legales.value);
      fd.append("dias_progresivos", this.form.controls.dias_progresivos.value);
      fd.append("documento", this.doc || null);
      this._vacacionesService.crear_solicitud_vacaciones(fd).subscribe(res=>{
        if(res.estado){
          alert(res.mensaje)
          this.salida.emit(true);
        }else{
          alert(res.estado)
          this.salida.emit(false);
        }
      })
    } else {
      alert("Faltan campos por llenar");
    }
  }
  archivo(event) {
    this.doc = event.srcElement.files[0];
  }
}