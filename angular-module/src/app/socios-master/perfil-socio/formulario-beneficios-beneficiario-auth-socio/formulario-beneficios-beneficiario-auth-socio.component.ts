import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-formulario-beneficios-beneficiario-auth-socio',
  templateUrl: './formulario-beneficios-beneficiario-auth-socio.component.html',
  styleUrls: ['./formulario-beneficios-beneficiario-auth-socio.component.css']
})
export class FormularioBeneficiosBeneficiarioAuthSocioComponent implements OnInit {

  datosBeneficiario = {
    relacionParentesco: '',
    rut: '',
    fechaNacimiento: '',
    nombres: '',
    apellidoPaterno: '',
    apellidoMaterno: '',
    direccion: '',
    comuna: '',
    celular: ''
  }

  constructor() { }

  ngOnInit() {
  }

  ingresarDatosBeneficiario(){
    console.log(this.datosBeneficiario);
  }

}
