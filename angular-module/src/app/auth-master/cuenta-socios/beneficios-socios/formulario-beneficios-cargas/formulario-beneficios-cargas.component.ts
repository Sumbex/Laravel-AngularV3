import { Component, OnInit, Input } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { SociosService } from 'src/app/servicios/socios.service';

@Component({
  selector: 'app-formulario-beneficios-cargas',
  templateUrl: './formulario-beneficios-cargas.component.html',
  styleUrls: ['./formulario-beneficios-cargas.component.css']
})
export class FormularioBeneficiosCargasComponent implements OnInit {

  abrirFormularioBeneficiosCargas;
  blockIngreso:boolean=false;

  @Input () getIdSocio:'';
  @Input () getNombreSocio:'';
  InsertarBeneficiosCargas ={
    tipo_carga_id:'',
    rut_carga:'',
    fecha_nacimiento:'',
    nombres:'',
    apellido_paterno:'',
    apellido_materno:'',
    direccion:'',
    celular:'',
    establecimiento:'',
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
    this.abrirFormularioBeneficiosCargas = this.modalService.open(FormularioBeneficios, { size: 'xl' });
  }
  onSelectImage(event) {
    this.InsertarBeneficiosCargas.archivoDocumento = event.srcElement.files[0];
  }

  guardarDatosCarga(){
    if(this.InsertarBeneficiosCargas.tipo_carga_id == '' && this.InsertarBeneficiosCargas.rut_carga == '' && this.InsertarBeneficiosCargas.fecha_nacimiento == '' && this.InsertarBeneficiosCargas.nombres == '' && this.InsertarBeneficiosCargas.apellido_paterno == '' && this.InsertarBeneficiosCargas.apellido_materno == '' && this.InsertarBeneficiosCargas.direccion == '' && this.InsertarBeneficiosCargas.celular == ''  && this.InsertarBeneficiosCargas.establecimiento == ''){
      alert('ingrese los datos obligatorios (*)');
      return false;
    }
    this.blockIngreso=true;
    const data = new FormData();
    data.append('socio_id', this.getIdSocio);
    data.append('tipo_carga_id', this.InsertarBeneficiosCargas.tipo_carga_id);
    data.append('rut',this.InsertarBeneficiosCargas.rut_carga);
    data.append('fecha_nacimiento',this.InsertarBeneficiosCargas.fecha_nacimiento);
    data.append('nombres',this.InsertarBeneficiosCargas.nombres);
    data.append('apellido_paterno',this.InsertarBeneficiosCargas.apellido_paterno);
    data.append('apellido_materno',this.InsertarBeneficiosCargas.apellido_materno);
    data.append('direccion',this.InsertarBeneficiosCargas.direccion);
    data.append('celular',this.InsertarBeneficiosCargas.celular);
    data.append('establecimiento',this.InsertarBeneficiosCargas.establecimiento);
    data.append('archivo',this.InsertarBeneficiosCargas.archivoDocumento);

    this._SociosService.insertarDatosCarga(data).subscribe((response) =>{
      if(response.estado == 'failed'){
        alert(response.mensaje);
        this.blockIngreso=false;
        return false;
      }
      if(response.estado == 'success'){
       this.InsertarBeneficiosCargas.tipo_carga_id = '';
       this.InsertarBeneficiosCargas.rut_carga = '';
       this.InsertarBeneficiosCargas.fecha_nacimiento = '';
       this.InsertarBeneficiosCargas.nombres = '';
       this.InsertarBeneficiosCargas.apellido_paterno = '';
       this.InsertarBeneficiosCargas.apellido_materno = '';
       this.InsertarBeneficiosCargas.direccion = '';
       this.InsertarBeneficiosCargas.celular = '';
       this.InsertarBeneficiosCargas.establecimiento = '';
       this.InsertarBeneficiosCargas.archivoDocumento = null; 
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
