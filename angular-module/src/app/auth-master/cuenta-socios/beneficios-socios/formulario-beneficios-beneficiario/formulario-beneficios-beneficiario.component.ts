import { Component, OnInit,Input } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { SociosService } from 'src/app/servicios/socios.service';

@Component({
  selector: 'app-formulario-beneficios-beneficiario',
  templateUrl: './formulario-beneficios-beneficiario.component.html',
  styleUrls: ['./formulario-beneficios-beneficiario.component.css']
})
export class FormularioBeneficiosBeneficiarioComponent implements OnInit {

  abrirFormularioBeneficiosBeneficiario;
  blockIngreso:boolean=false;

  @Input () getIdSocio:'';
  @Input () getNombreSocio:'';
  InsertarBeneficiosBeneficiario ={
    prioridad:'',
    relacion:'',
    rut_beneficiario:'',
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
    this.abrirFormularioBeneficiosBeneficiario = this.modalService.open(FormularioBeneficios, { size: 'xl' });
  }

  guardarDatosBeneficiario(){
    if(this.InsertarBeneficiosBeneficiario.relacion == '' && this.InsertarBeneficiosBeneficiario.rut_beneficiario == '' && this.InsertarBeneficiosBeneficiario.fecha_nacimiento == '' && this.InsertarBeneficiosBeneficiario.nombres == '' && this.InsertarBeneficiosBeneficiario.apellido_paterno == '' && this.InsertarBeneficiosBeneficiario.apellido_materno == '' && this.InsertarBeneficiosBeneficiario.direccion == '' && this.InsertarBeneficiosBeneficiario.celular == ''){
      alert('ingrese los datos obligatorios (*)');
      return false;
    }
    this.blockIngreso=true;
    const data = new FormData();
    data.append('socio_id', this.getIdSocio);
    data.append('prioridad', this.InsertarBeneficiosBeneficiario.prioridad);
    data.append('relacion', this.InsertarBeneficiosBeneficiario.relacion);
    data.append('rut',this.InsertarBeneficiosBeneficiario.rut_beneficiario);
    data.append('fecha_nacimiento',this.InsertarBeneficiosBeneficiario.fecha_nacimiento);
    data.append('nombres',this.InsertarBeneficiosBeneficiario.nombres);
    data.append('apellido_paterno',this.InsertarBeneficiosBeneficiario.apellido_paterno);
    data.append('apellido_materno',this.InsertarBeneficiosBeneficiario.apellido_materno);
    data.append('direccion',this.InsertarBeneficiosBeneficiario.direccion);
    data.append('celular',this.InsertarBeneficiosBeneficiario.celular);

  
    this._SociosService.insertarDatosBeneficiario(data).subscribe((response) =>{
      if(response.estado == 'failed'){
        alert(response.mensaje);
        this.blockIngreso=false;
        return false;
      }
      if(response.estado == 'success'){
       this.InsertarBeneficiosBeneficiario.prioridad = '';
       this.InsertarBeneficiosBeneficiario.relacion = '';
       this.InsertarBeneficiosBeneficiario.rut_beneficiario = '';
       this.InsertarBeneficiosBeneficiario.fecha_nacimiento = '';
       this.InsertarBeneficiosBeneficiario.nombres = '';
       this.InsertarBeneficiosBeneficiario.apellido_paterno = '';
       this.InsertarBeneficiosBeneficiario.apellido_materno = '';
       this.InsertarBeneficiosBeneficiario.direccion = '';
       this.InsertarBeneficiosBeneficiario.celular = '';
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
