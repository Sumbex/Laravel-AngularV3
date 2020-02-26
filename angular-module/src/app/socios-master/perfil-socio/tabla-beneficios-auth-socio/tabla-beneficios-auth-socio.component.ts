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

  //variable para definir el modal
  abrirTablaBeneficiosSocios;

  //objeto que almacenara la informacion
  traerDatosSocio;

  //variable para mostrar el loading
  cargandoTabla;

  constructor(config: NgbModalConfig,
    private modalService: NgbModal,
    private _portalSociosService: PortalSociosService) {
    config.backdrop = 'static';
    config.keyboard = false;
  }

  ngOnInit() {
  }

  verTablaBeneficios(TablaBeneficios) {
    this.abrirTablaBeneficiosSocios = this.modalService.open(TablaBeneficios, { size: 'xl' });
    this.getDatosSocio();
  }

  getDatosSocio() {
    this.cargandoTabla = true;
    this._portalSociosService.getDatosSocios().subscribe(
      response => {
        if (response.estado == 'failed' || response.estado == 'failed_v') {
          alert(response.mensaje);
          this.abrirTablaBeneficiosSocios.close();
        } else {
          this.traerDatosSocio = response.socio[0];
          this.cargandoTabla = false;
        }
      },
      error => {
        console.log(error);
        this.cargandoTabla = false;
      }
    );
  }

  updateDatosSocios(tipo, nombreCampo, valorCampo){
    this._portalSociosService.updateDatosBasicosSocio(tipo,nombreCampo,valorCampo).subscribe(response=>{
      if(response.estado == 'success'){
        alert(response.mensaje);
      }else{
        alert(response.mensaje);
      }
    }, error=>{
      console.log(error);
    });
  }

}
