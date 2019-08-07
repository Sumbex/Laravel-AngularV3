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
  traerDatosSocio;

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

  listarDatosSocio(){
   this._SociosService.getDatosSocio(this.getIdSocio).subscribe((response) =>{
    if(response.estado == "failed"){
      alert('Error, El rut ingresado no existe en nuestra base de datos, pruebe digitando otro rut.');
      return false;
    }else{
      this.traerDatosSocio = response[0];
    }

       error => {
      console.log(error);
      }
    }
  );
  }

}
