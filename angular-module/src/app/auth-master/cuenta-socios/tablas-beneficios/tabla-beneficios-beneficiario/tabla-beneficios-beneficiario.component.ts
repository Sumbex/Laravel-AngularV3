import { Component, OnInit, Input} from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { SociosService } from 'src/app/servicios/socios.service';

@Component({
  selector: 'app-tabla-beneficios-beneficiario',
  templateUrl: './tabla-beneficios-beneficiario.component.html',
  styleUrls: ['./tabla-beneficios-beneficiario.component.css']
})
export class TablaBeneficiosBeneficiarioComponent implements OnInit {

  abrirTablaBeneficiosBeneficiario;

  @Input () getIdSocio:'';
  @Input () getNombreSocio:'';
  traerDatosBeneficiario:object = [
                        'relacion_parentesco',
                        'rut',
                        'fecha_nacimiento',
                        'nombres',
                        'apellido_paterno',
                        'apellido_materno',
                        'direccion',
                        'celular',
                        'created_at',
                        'updated_at',

                        ];

  constructor(config: NgbModalConfig, 
    private modalService: NgbModal,
    private _SociosService:SociosService) {
    config.backdrop = 'static';
    config.keyboard = false;
                        }

  ngOnInit() {
  }

  verTablaBeneficios(TablaBeneficios) {
    this.abrirTablaBeneficiosBeneficiario = this.modalService.open(TablaBeneficios, { size: 'xl' });
    // this.listarDatosConyuge();
  }

}
