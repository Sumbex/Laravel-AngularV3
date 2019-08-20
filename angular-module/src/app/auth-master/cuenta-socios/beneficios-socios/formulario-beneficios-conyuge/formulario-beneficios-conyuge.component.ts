import { Component, OnInit,Input } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { SociosService } from 'src/app/servicios/socios.service';

@Component({
  selector: 'app-formulario-beneficios-conyuge',
  templateUrl: './formulario-beneficios-conyuge.component.html',
  styleUrls: ['./formulario-beneficios-conyuge.component.css']
})
export class FormularioBeneficiosConyugeComponent implements OnInit {

  abrirFormularioBeneficiosConyuge;

  @Input () getIdSocio:'';
  @Input () getNombreSocio:'';
  InsertarBeneficiosConyuge ={
    rut_conyuge:'',
    fecha_nacimiento:'',
    nombres:'',
    apellido_paterno:'',
    apellido_materno:'',
    direccion:'',
    celular:'',
    archivoDocumento:null,
    }
    constructor(config: NgbModalConfig, 
      private modalService: NgbModal,
      private _SociosService:SociosService) {
        config.backdrop = 'static';
        config.keyboard = false;
      }

  ngOnInit() {
  }
  verFormularioBeneficios(FormularioBeneficios) {
    this.abrirFormularioBeneficiosConyuge = this.modalService.open(FormularioBeneficios, { size: 'xl' });
  }
  onSelectImage(event) {
    this.InsertarBeneficiosConyuge.archivoDocumento = event.srcElement.files[0];
  }


  guardarDatosConyuge(){
    if(this.InsertarBeneficiosConyuge.rut_conyuge == '' && this.InsertarBeneficiosConyuge.fecha_nacimiento == '' && this.InsertarBeneficiosConyuge.fecha_nacimiento == '' && this.InsertarBeneficiosConyuge.nombres == '' && this.InsertarBeneficiosConyuge.apellido_paterno == '' && this.InsertarBeneficiosConyuge.apellido_materno == '' && this.InsertarBeneficiosConyuge.direccion == '' && this.InsertarBeneficiosConyuge.celular == ''){
      alert('ingrese los datos obligatorios (*)');
      return false;
    }
    const data = new FormData();
    data.append('socio_id', this.getIdSocio);
    data.append('rut',this.InsertarBeneficiosConyuge.rut_conyuge);
    data.append('fecha_nacimiento',this.InsertarBeneficiosConyuge.fecha_nacimiento);
    data.append('nombres',this.InsertarBeneficiosConyuge.nombres);
    data.append('apellido_paterno',this.InsertarBeneficiosConyuge.apellido_paterno);
    data.append('apellido_materno',this.InsertarBeneficiosConyuge.apellido_materno);
    data.append('direccion',this.InsertarBeneficiosConyuge.direccion);
    data.append('celular',this.InsertarBeneficiosConyuge.celular);
    data.append('archivo',this.InsertarBeneficiosConyuge.archivoDocumento);

    this._SociosService.insertarDatosConyuge(data).subscribe((response) =>{
      if(response.estado == 'failed'){
        alert(response.mensaje);
        return false;
      }
      if(response.estado == 'success'){
       this.InsertarBeneficiosConyuge.rut_conyuge = '';
       this.InsertarBeneficiosConyuge.fecha_nacimiento = '';
       this.InsertarBeneficiosConyuge.nombres = '';
       this.InsertarBeneficiosConyuge.apellido_paterno = '';
       this.InsertarBeneficiosConyuge.apellido_materno = '';
       this.InsertarBeneficiosConyuge.direccion = '';
       this.InsertarBeneficiosConyuge.celular = '';
        alert(response.mensaje);
        return false;
      }
    },
    error => {
      console.log(error);
    }
    );
    
  }

}
