import { Component, OnInit, Input } from '@angular/core';
import { SociosService } from 'src/app/servicios/socios.service';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';

@Component({
  selector: 'app-tabla-resumen-socio',
  templateUrl: './tabla-resumen-socio.component.html',
  styleUrls: ['./tabla-resumen-socio.component.css']
})
export class TablaResumenSocioComponent implements OnInit {

  abrirTablaResumen;
  TraerDocumentoSocio;

  blockTablaSocio=false;
  blockTablaConyuge=false;
  blockTablaBeneficiario=false;
  blockTablaCarga=false;
  blockTablaPS=false;

  firma: string = 'AUTORIZO AL SINDICATO A PAGAR AL BENEFICIARIO(S) ___________________________________________';

  @Input() getIdSocio;
  @Input() getNombreSocio;
  @Input() getRutSocio;
  @Input() getFechaSocio;
  traerDatosSocio: object = [
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
    'grupo_sangre',
  ];

  traerDatosConyuge: object = [
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

  traerDatosBeneficiario: object = [
    'relacion',
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

  traerDatosCarga: object = [
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

  traerDatosPS: object = [
    'relacion_socio_id',
    'descripcion',
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

  vista_tabla: boolean = false;
  vista_pdf: boolean = false;
  ocultar_tabla: boolean = true;
  ocultar_imprimir: boolean = true;

  constructor(private _SociosService: SociosService,
    config: NgbModalConfig,
    private modalService: NgbModal, ) {
    config.backdrop = 'static';
    config.keyboard = false;
  }


  ngOnInit() {
  }
  verTablaResumen(TablaResumen) {
    this.abrirTablaResumen = this.modalService.open(TablaResumen, { size: 'lg' });
    this.traerDocumentoSocio();
  }

  traerDocumentoSocio() {
    this.vista_pdf = true;
    this._SociosService.getDocumentoResumen(this.getIdSocio).subscribe((response) => {
      if (response.estado == "failed") {
        this.vista_pdf = false;
        //  alert(response.mensaje);
        this.abrirTablaResumen.close();
        return false;
      } else {
        this.TraerDocumentoSocio = response.body[0].archivo;
        console.log(this.TraerDocumentoSocio);
        this.vista_pdf = false;
      }

      error => {
        console.log(error);
        this.vista_pdf = false;
      }
    }
    );
  }

  listarDatosResumenSocio() {
    this.vista_tabla = true;
    let num = 5;
    this._SociosService.getDatosSocio(this.getIdSocio).subscribe((response) => {
      if (response.estado == "failed") {
        num--;
        this.blockTablaSocio=true;
      } else {
        this.traerDatosSocio = response[0];
      }
    },
      error => {
        console.log(error);
        this.vista_tabla = false;
      }
    );
    this._SociosService.getDatosConyuge(this.getIdSocio).subscribe((response) => {
      if (response.estado == "failed") {
        num--;
        this.blockTablaConyuge=true;
      } else {
        this.traerDatosConyuge = response.body;
      }
    },
      error => {
        console.log(error);
        this.vista_tabla = false;
      }
    );
    this._SociosService.getDatosBeneficiario(this.getIdSocio).subscribe((response) => {
      if (response.estado == "failed") {
        num--;
        this.blockTablaBeneficiario=true;
      } else {
        this.traerDatosBeneficiario = response.body;
        // console.log(this.traerDatosBeneficiario);
      }
    },
      error => {
        console.log(error);
        this.vista_tabla = false;
      }
    );
    this._SociosService.getDatosCarga(this.getIdSocio).subscribe((response) => {
      if (response.estado == "failed") {
        num--;
        this.blockTablaCarga=true;
      } else {
        this.traerDatosCarga = response.body;
      }
    },
      error => {
        console.log(error);
        this.vista_tabla = false;
      }
    );

    this._SociosService.getDatosPS(this.getIdSocio).subscribe((response) => {
      if (response.estado == "failed") {
        num--;
        this.blockTablaPS=true;
      } 
      if (num == 0) {
        alert("No se han encontrado datos aun para mostrar.");
        this.vista_tabla = false;
      } else {
        this.traerDatosPS = response.body;
        console.log(this.traerDatosPS);
        this.firma;
        this.vista_tabla = false;
        this.ocultar_imprimir = false;
        this.ocultar_tabla = false;
      }
    },
      error => {
        console.log(error);
        this.vista_tabla = false;
      }
    );
  }

}
