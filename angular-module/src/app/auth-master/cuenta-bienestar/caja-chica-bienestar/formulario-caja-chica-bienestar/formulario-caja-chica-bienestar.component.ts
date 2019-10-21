import { Component, OnInit } from '@angular/core';
import { BryanBienestarService } from 'src/app/servicios/bryans-bienestar.service';

@Component({
  selector: 'app-formulario-caja-chica-bienestar',
  templateUrl: './formulario-caja-chica-bienestar.component.html',
  styleUrls: ['./formulario-caja-chica-bienestar.component.css']
})
export class FormularioCajaChicaBienestarComponent implements OnInit {

  datosCajaChica = {
    numero_documento: '',
    archivo_documento: null,
    fecha: '',
    descripcion: '',
    definicion: '2',
    monto_ingreso: null,
    monto_egreso: null
  }

  constructor(private _binestarService: BryanBienestarService) { }

  ngOnInit() {
  }

  onSelectImage(event) {
    this.datosCajaChica.archivo_documento = event.srcElement.files[0];
  }

  onSubmit(form){
    if(form.valid){
      this._binestarService.setCajaChicaBienestar(this.datosCajaChica).subscribe(response =>{
        if(response.estado == 'failed' || response.estado == 'failed_v'){
          alert(response.mensaje);
        }else{
          alert(response.mensaje);
        }
      },
      error =>{
        this.limpiarFormulario();
        console.log(error);
      });
    }else{
      alert("Formulario no valido");
    }
  }

  limpiarFormulario(){
    this.datosCajaChica.archivo_documento = null;
    this.datosCajaChica.definicion = '';
    this.datosCajaChica.descripcion = '';
    // this.datosCajaChica.fecha = '';
    this.datosCajaChica.monto_egreso = '';
    this.datosCajaChica.monto_ingreso = '';
    this.datosCajaChica.numero_documento = '';
  }

}
