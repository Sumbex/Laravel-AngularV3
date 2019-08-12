import { Component, OnInit, Input } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { SociosService } from 'src/app/servicios/socios.service';

@Component({
  selector: 'app-tabla-beneficios-cargas',
  templateUrl: './tabla-beneficios-cargas.component.html',
  styleUrls: ['./tabla-beneficios-cargas.component.css']
})
export class TablaBeneficiosCargasComponent implements OnInit {
  abrirTablaBeneficiosCarga;

  @Input () getIdSocio;
  @Input () getNombreSocio;
  traerDatosCarga:object = [
                      'tipo_carga_id',
                      'rut',
                      'fecha_nacimiento',
                      'nombres',
                      'apellido_paterno',
                      'apellido_materno',
                      'direccion',
                      'celular',
                      'establecimiento',
                      'created_at',
                      'updated_at',
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
    this.abrirTablaBeneficiosCarga = this.modalService.open(TablaBeneficios, { size: 'xl' });
    this.listarDatosCarga();
  }

  limpiar_tabla(){
    this.vista_tabla = false;
  }

  listarDatosCarga(){
   this._SociosService.getDatosCarga(this.getIdSocio).subscribe((response) =>{
    if(response.estado == "failed"){
      // alert('Error, El rut ingresado no existe en nuestra base de datos, pruebe digitando otro rut.');
      this.vista_tabla = false;
      alert(response.mensaje);
      this.abrirTablaBeneficiosCarga.close();
      return false;
    }else{
      this.traerDatosCarga = response.body;
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
