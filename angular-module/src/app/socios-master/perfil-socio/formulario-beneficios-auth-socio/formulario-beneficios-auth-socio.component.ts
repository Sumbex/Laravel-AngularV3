import { Component, OnInit } from '@angular/core';
import { NgbModal, NgbModalConfig } from '@ng-bootstrap/ng-bootstrap';
import { PortalSociosService } from 'src/app/servicios/portal-socios.service';

@Component({
  selector: 'app-formulario-beneficios-auth-socio',
  templateUrl: './formulario-beneficios-auth-socio.component.html',
  styleUrls: ['./formulario-beneficios-auth-socio.component.css']
})
export class FormularioBeneficiosAuthSocioComponent implements OnInit {

  abrirFormularioBeneficiosSocios;

  idSocio = '5';

  InsertarBeneficiosSocio ={
  socio_id:'',
  direccion:'',
  telefono:'',
  celular:'',
  anexo:'',
  email_1:'',
  email_2:'',
  cargo_planta:'',
  cargo_comision_sindicato:'',
  numero_cuenta:'',
  tipo_cuenta_banco_id:'',
  banco:'',
  isapre_fonasa:'',
  grupo_sangre:'',
  casa_propia:'',
  rol_turno:'',
  estado_civil_id:'',
  conyuge:'',
  }

  constructor(config: NgbModalConfig, 
    private modalService: NgbModal,
    private _portalSociosService: PortalSociosService) {
      config.backdrop = 'static';
      config.keyboard = false;
    }

  ngOnInit() {
  }

  verFormularioBeneficios(FormularioBeneficios) {
    this.abrirFormularioBeneficiosSocios = this.modalService.open(FormularioBeneficios, { size: 'xl' });
    console.log(this.idSocio);
  }
  guardarDatosSocio(){
    /* if(this.InsertarBeneficiosSocio.email_1 == '' && this.InsertarBeneficiosSocio.direccion == ''){
      alert('ingrese los datos obligatorios (*)');
      return false;
    }
    const data = new FormData();
    data.append('socio_id', this.idSocio);
    data.append('direccion',this.InsertarBeneficiosSocio.direccion);
    data.append('telefono',this.InsertarBeneficiosSocio.telefono); 
    data.append('celular',this.InsertarBeneficiosSocio.celular);
    data.append('anexo',this.InsertarBeneficiosSocio.anexo);
    data.append('email_1',this.InsertarBeneficiosSocio.email_1);
    data.append('email_2',this.InsertarBeneficiosSocio.email_2);
    data.append('cargo_planta',this.InsertarBeneficiosSocio.cargo_planta);
    data.append('cargo_comision_sindicato',this.InsertarBeneficiosSocio.cargo_comision_sindicato);
    data.append('numero_cuenta',this.InsertarBeneficiosSocio.numero_cuenta);
    data.append('tipo_cuenta_banco_id',this.InsertarBeneficiosSocio.tipo_cuenta_banco_id);
    data.append('banco',this.InsertarBeneficiosSocio.banco);
    data.append('isapre_fonasa',this.InsertarBeneficiosSocio.isapre_fonasa);
    data.append('grupo_sangre',this.InsertarBeneficiosSocio.grupo_sangre);
    data.append('casa_propia',this.InsertarBeneficiosSocio.casa_propia);
    data.append('rol_turno',this.InsertarBeneficiosSocio.rol_turno);
    data.append('estado_civil_id',this.InsertarBeneficiosSocio.estado_civil_id);
    data.append('conyuge',this.InsertarBeneficiosSocio.conyuge);

    this._SociosService.insertarDatosSocio(data).subscribe((response) =>{
      if(response.estado == 'failed'){
        alert(response.mensaje);
        return false;
      }
      if(response.estado == 'success'){
        this.InsertarBeneficiosSocio.direccion = '';
        this.InsertarBeneficiosSocio.telefono = '';
        this.InsertarBeneficiosSocio.celular = '';
        this.InsertarBeneficiosSocio.anexo ='';
        this.InsertarBeneficiosSocio.email_1 ='';
        this.InsertarBeneficiosSocio.email_2 ='';
        this.InsertarBeneficiosSocio.cargo_planta ='';
        this.InsertarBeneficiosSocio.cargo_comision_sindicato ='';
        this.InsertarBeneficiosSocio.numero_cuenta ='';
        this.InsertarBeneficiosSocio.tipo_cuenta_banco_id ='';
        this.InsertarBeneficiosSocio.banco ='';
        this.InsertarBeneficiosSocio.isapre_fonasa ='';
        this.InsertarBeneficiosSocio.grupo_sangre ='';
        this.InsertarBeneficiosSocio.casa_propia ='';
        this.InsertarBeneficiosSocio.rol_turno ='';
        this.InsertarBeneficiosSocio.estado_civil_id ='';
        this.InsertarBeneficiosSocio.conyuge ='';
        alert(response.mensaje);
        return false;
      }
    },
    error => {
      console.log(error);
    }
    ); */
    
  }

}
