import { Component, OnInit, Input} from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { SociosService } from 'src/app/servicios/socios.service';

@Component({
  selector: 'app-formulario-beneficios-socio',
  templateUrl: './formulario-beneficios-socio.component.html',
  styleUrls: ['./formulario-beneficios-socio.component.css']
})
export class FormularioBeneficiosSocioComponent implements OnInit {

  abrirFormularioBeneficiosSocios;

  @Input () getIdSocio: string;
  socio_id;
  direccion;
  telefono;
  celular;
  anexo;
  email_1;
  email_2;
  cargo_planta;
  carga_comision_sindicato;
  numero_cuenta;
  tipo_cuenta_banco_id;
  banco;
  isapre_fonasa;
  grupo_sangre;
  casa_propia;
  rol_turno;
  estado_civil_id;
  conyuge;

  constructor(config: NgbModalConfig, 
    private modalService: NgbModal,
    private _SociosService:SociosService) {
      config.backdrop = 'static';
      config.keyboard = false;
    }

  ngOnInit() {
  }

  verFormularioBeneficios(FormularioBeneficios) {
    this.abrirFormularioBeneficiosSocios = this.modalService.open(FormularioBeneficios, { size: 'xl' });
    console.log(this.getIdSocio);
  }
  guardarDatosSocio(){
    if(this.email_1 == '' && this.direccion == ''){
      alert('ingrese los datos obligatorios (*)');
      return false;
    }
    const data = new FormData();
    data.append('socio_id', this.getIdSocio);
    data.append('direccion',this.direccion);
    data.append('telefono',this.telefono);
    data.append('celular',this.celular);
    data.append('anexo',this.anexo);
    data.append('email_1',this.email_1);
    data.append('email_2',this.email_2);
    data.append('cargo_planta',this.cargo_planta);
    data.append('carga_comision_sindicato',this.carga_comision_sindicato);
    data.append('numero_cuenta',this.numero_cuenta);
    data.append('tipo_cuenta_banco_id',this.tipo_cuenta_banco_id);
    data.append('banco',this.banco);
    data.append('isapre_fonasa',this.isapre_fonasa);
    data.append('grupo_sangre',this.grupo_sangre);
    data.append('casa_propia',this.casa_propia);
    data.append('rol_turno',this.rol_turno);
    data.append('estado_civil_id',this.estado_civil_id);
    data.append('conyuge',this.conyuge);

    this._SociosService.insertarDatosSocio(data).subscribe((response) =>{
      if(response.estado == 'failed'){
        alert(response.mensaje);
        return false;
      }
      if(response.estado == 'success'){
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

