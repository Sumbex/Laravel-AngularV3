import { Component, OnInit, Input } from '@angular/core';
import { SociosService } from 'src/app/servicios/socios.service';

@Component({
  selector: 'app-tabla-resumen-socio',
  templateUrl: './tabla-resumen-socio.component.html',
  styleUrls: ['./tabla-resumen-socio.component.css']
})
export class TablaResumenSocioComponent implements OnInit {

  firma:string='AUTORIZO AL SINDICATO A PAGAR AL BENEFICIARIO(S) ___________________________________________';

  @Input () getIdSocio;
  @Input () getNombreSocio;
  @Input () getRutSocio;
  @Input () getFechaSocio;
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

traerDatosConyuge:object = [
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

  traerDatosBeneficiario:object = [
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

  traerDatosPS:object = [
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

vista_tabla:boolean=false;
ocultar_tabla:boolean=true;
ocultar_imprimir:boolean=true;

constructor(private _SociosService:SociosService) {
  }


  ngOnInit() {
  }

  listarDatosResumenSocio(){
    this.vista_tabla = true;
    this._SociosService.getDatosSocio(this.getIdSocio).subscribe((response) =>{
     if(response.estado == "failed"){
       return false;
     }else{
       this.traerDatosSocio = response[0];
     }
     error => {
      console.log(error);
      this.vista_tabla = false;
      }
    }
  );
     this._SociosService.getDatosConyuge(this.getIdSocio).subscribe((response) =>{
      if(response.estado == "failed"){
        return false;
      }else{
        this.traerDatosConyuge = response.body;
      }
      error => {
        console.log(error);
        this.vista_tabla = false;
        }
      }
    );
      this._SociosService.getDatosBeneficiario(this.getIdSocio).subscribe((response) =>{
        if(response.estado == "failed"){
          return false;
        }else{
          this.traerDatosBeneficiario = response.body;
        }
        error => {
          console.log(error);
          this.vista_tabla = false;
          }
        }
      );
        this._SociosService.getDatosCarga(this.getIdSocio).subscribe((response) =>{
          if(response.estado == "failed"){
            return false;
          }else{
            this.traerDatosCarga = response.body;
          }
  
        error => {
       console.log(error);
       this.vista_tabla = false;
       }
     }
   );

   this._SociosService.getDatosPS(this.getIdSocio).subscribe((response) =>{
   if(response.estado == "failed"){
     return false;
   }else{
     this.traerDatosPS = response.body;
     this.firma;
     this.vista_tabla = false;
     this.ocultar_imprimir =false;
     this.ocultar_tabla = false;
   }

      error => {
     console.log(error);
     this.vista_tabla = false;
     }
   }
 );
 }

}
