import { Component, OnInit,Input } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { SociosService } from 'src/app/servicios/socios.service';

@Component({
  selector: 'app-tabla-beneficios-socio',
  templateUrl: './tabla-beneficios-socio.component.html',
  styleUrls: ['./tabla-beneficios-socio.component.css']
})
export class TablaBeneficiosSocioComponent implements OnInit {

  abrirTablaBeneficiosSocios;

  @Input () getIdSocio;
  @Input () getNombreSocio;
  traerDatosSocio:object = [
                      'numero_cuenta',
                      'direccion',
                      'direccion_2',
                      'telefono',
                      'celular',
                      'anexo',
                      'email_1',
                      'email_2',
                      'cargo_planta',
                      'cargo_comision_sindicato',
                      'activo',
                      'created_at',
                      'updated_at',
                      'comuna_id',
                      'casa_propia',
                      'rol_turno',
                      'estado_civil_id',
                      'conyuge',
                      'numero_cuenta',
                      'tipo_cuenta_banco_id',
                      'banco',
                      'isapre_fonasa',
                      'grupo_sangre'
                  ];
  vista_tabla:boolean=false;

  constructor(config: NgbModalConfig, 
    private modalService: NgbModal,
    private _SociosService:SociosService) {
      config.backdrop = 'static';
      config.keyboard = false;
    }

  ngOnInit() {
  }

  verTablaBeneficios(TablaBeneficios) {
    this.abrirTablaBeneficiosSocios = this.modalService.open(TablaBeneficios, { size: 'xl' });
    this.listarDatosSocio();
  }

  limpiar_tabla(){
    this.vista_tabla = false;
  }

  listarDatosSocio(){
   this._SociosService.getDatosSocio(this.getIdSocio).subscribe((response) =>{
    if(response.estado == "failed"){
      alert('Error, El rut ingresado no existe en nuestra base de datos, pruebe digitando otro rut.');
      this.vista_tabla = false;
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
