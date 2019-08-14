import { Component, OnInit, Input } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { SociosService } from 'src/app/servicios/socios.service';
import { ValidarUsuarioService } from 'src/app/servicios/validar-usuario.service';
import { PortalSociosService } from 'src/app/servicios/portal-socios.service';

@Component({
  selector: 'app-tabla-beneficios-auth-socio',
  templateUrl: './tabla-beneficios-auth-socio.component.html',
  styleUrls: ['./tabla-beneficios-auth-socio.component.css']
})
export class TablaBeneficiosAuthSocioComponent implements OnInit {

  abrirTablaBeneficiosSocios;

  idSocio = '25';

  traerDatosSocio = {
    numero_cuenta: '',
    direccion: '',
    direccion_2: '',
    telefono: '',
    celular: '',
    anexo: '',
    email_1: '',
    email_2: '',
    cargo_planta: '',
    cargo_comision_sindicato: '',
    comuna_id: '',
    casa_propia: '',
    rol_turno: '',
    estado_civil_id: '',
    conyuge: '',
    tipo_cuenta_banco_id: '',
    banco: '',
    isapre_fonasa: '',
    grupo_sangre: ''
  };

  vista_tabla:boolean=false;

  actualizarLoad:boolean=false;

  constructor(config: NgbModalConfig, 
    private modalService: NgbModal,
    private _portalSociosService:PortalSociosService) {
      config.backdrop = 'static';
      config.keyboard = false;
    }

  ngOnInit() {
  }

  verTablaBeneficios(TablaBeneficios) {
    this.abrirTablaBeneficiosSocios = this.modalService.open(TablaBeneficios, { size: 'xl' });
    this.listarDatosSocio();
  }

  listarDatosSocio(){
   this._portalSociosService.getDatosSocios().subscribe((response) =>{
    if(response.estado == "failed"){
      // alert('Error, El rut ingresado no existe en nuestra base de datos, pruebe digitando otro rut.');
      this.vista_tabla = false;
      alert(response.mensaje);
      this.abrirTablaBeneficiosSocios.close();
      return false;
    }else{
      this.traerDatosSocio = response[0];
      this.vista_tabla = true;
    }

       error => {
      console.log(error);
      this.vista_tabla = false;
      }
    }
  );
  }

}
