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

  @Input () getIdSocio:'';
  @Input () getNombreSocio:'';
  InsertarBeneficiosBeneficiario ={
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

}
