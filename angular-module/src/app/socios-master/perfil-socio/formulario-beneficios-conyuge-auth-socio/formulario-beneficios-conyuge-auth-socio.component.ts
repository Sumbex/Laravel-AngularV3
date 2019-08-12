import { Component, OnInit } from '@angular/core';
import { PortalSociosService } from 'src/app/servicios/portal-socios.service';

@Component({
  selector: 'app-formulario-beneficios-conyuge-auth-socio',
  templateUrl: './formulario-beneficios-conyuge-auth-socio.component.html',
  styleUrls: ['./formulario-beneficios-conyuge-auth-socio.component.css']
})
export class FormularioBeneficiosConyugeAuthSocioComponent implements OnInit {

  //Objeto con los datos del conyuge
  datosConyuge = {
    rut: '',
    fechaNacimiento: '',
    nombres: '',
    apellidoPaterno: '',
    apellidoMaterno: '',
    direccion: '',
    comuna: '',
    celular: ''
  }

  constructor(private _portalSociosService: PortalSociosService) { }

  ngOnInit() {
  }

  ingresarDatosConyuge(){
    console.log(this.datosConyuge);
  }

}
