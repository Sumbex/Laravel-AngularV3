import { Component, OnInit, Input } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { SociosService } from 'src/app/servicios/socios.service';

@Component({
  selector: 'app-formulario-beneficios-padres-suegros',
  templateUrl: './formulario-beneficios-padres-suegros.component.html',
  styleUrls: ['./formulario-beneficios-padres-suegros.component.css']
})
export class FormularioBeneficiosPadresSuegrosComponent implements OnInit {
  abrirFormularioBeneficiosPadresSuegros;
  blockIngreso=false;

  @Input () getIdSocio:'';
  @Input () getNombreSocio:'';
  InsertarBeneficiosPadresSuegros ={
    relacion_socio_id:'',
    rut:'',
    fecha_nacimiento:'',
    nombres:'',
    apellido_paterno:'',
    apellido_materno:'',
    direccion:'',
    celular:'',
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
    this.abrirFormularioBeneficiosPadresSuegros = this.modalService.open(FormularioBeneficios, { size: 'xl' });
  }

  guardarDatosPadresSuegros(){
    if(this.InsertarBeneficiosPadresSuegros.relacion_socio_id == '' && this.InsertarBeneficiosPadresSuegros.rut == '' && this.InsertarBeneficiosPadresSuegros.fecha_nacimiento == '' && this.InsertarBeneficiosPadresSuegros.nombres == '' && this.InsertarBeneficiosPadresSuegros.apellido_paterno == '' && this.InsertarBeneficiosPadresSuegros.apellido_materno == '' && this.InsertarBeneficiosPadresSuegros.direccion == '' && this.InsertarBeneficiosPadresSuegros.celular == ''){
      alert('ingrese los datos obligatorios (*)');
      return false;
    }
    this.blockIngreso=true;
    const data = new FormData();
    data.append('socio_id', this.getIdSocio);
    data.append('relacion_socio_id', this.InsertarBeneficiosPadresSuegros.relacion_socio_id);
    data.append('rut',this.InsertarBeneficiosPadresSuegros.rut);
    data.append('fecha_nacimiento',this.InsertarBeneficiosPadresSuegros.fecha_nacimiento);
    data.append('nombres',this.InsertarBeneficiosPadresSuegros.nombres);
    data.append('apellido_paterno',this.InsertarBeneficiosPadresSuegros.apellido_paterno);
    data.append('apellido_materno',this.InsertarBeneficiosPadresSuegros.apellido_materno);
    data.append('direccion',this.InsertarBeneficiosPadresSuegros.direccion);
    data.append('celular',this.InsertarBeneficiosPadresSuegros.celular);

    this._SociosService.insertarDatosPS(data).subscribe((response) =>{
      if(response.estado == 'failed'){
        alert(response.mensaje);
        this.blockIngreso=false;
        return false;
      }
      if(response.estado == 'success'){
       this.InsertarBeneficiosPadresSuegros.relacion_socio_id = '';
       this.InsertarBeneficiosPadresSuegros.rut = '';
       this.InsertarBeneficiosPadresSuegros.fecha_nacimiento = '';
       this.InsertarBeneficiosPadresSuegros.nombres = '';
       this.InsertarBeneficiosPadresSuegros.apellido_paterno = '';
       this.InsertarBeneficiosPadresSuegros.apellido_materno = '';
       this.InsertarBeneficiosPadresSuegros.direccion = '';
       this.InsertarBeneficiosPadresSuegros.celular = '';
        alert(response.mensaje);
        this.blockIngreso=false;
        return false;
      }
    },
    error => {
      console.log(error);
      this.blockIngreso=false;
    }
    );
    
  }



}
