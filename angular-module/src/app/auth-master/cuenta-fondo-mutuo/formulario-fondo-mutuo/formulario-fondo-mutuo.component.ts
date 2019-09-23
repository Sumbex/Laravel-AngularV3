import { Component, OnInit } from '@angular/core';
import { SociosService } from 'src/app/servicios/socios.service';
import { ValidarUsuarioService } from 'src/app/servicios/validar-usuario.service';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';

@Component({
  selector: 'app-formulario-fondo-mutuo',
  templateUrl: './formulario-fondo-mutuo.component.html',
  styleUrls: ['./formulario-fondo-mutuo.component.css']
})
export class FormularioFondoMutuoComponent implements OnInit {

    //Variables buscador socio
    blockSocio: boolean = false;
    rut: '';
    fechaSocioTest: '';
    rutSocioTest: '';
    idSocio: '';
    nombreSocioTest = '';
    nombreUpperSocio = '';

    constructor(
      private _SociosService: SociosService,
      private _validarusuario: ValidarUsuarioService,
      config: NgbModalConfig,
      private modalService: NgbModal) {
      config.backdrop = 'static';
      config.keyboard = false;
    }
  ngOnInit() {
  }

  buscarSocio() {
    this.blockSocio = true;
    this._SociosService.traerDatosSocio(this.rut).subscribe((response) => {
      console.log(response);
      if (response.estado == "failed") {
        alert('Error, El rut ingresado no existe en nuestra base de datos, pruebe digitando otro rut.');
        this.rut = '';
        this.fechaSocioTest = '';
        this.rutSocioTest = '';
        this.nombreSocioTest = '';
        this.idSocio = '';
        this.blockSocio = false;
        return false;
      } else {
        this.rut = '';
        this.fechaSocioTest = response.fecha_nacimiento;
        this.rutSocioTest = response.rut;
        this.nombreSocioTest = response.nombres + ' ' + response.a_paterno + ' ' + response.a_materno;
        this.nombreUpperSocio = this.nombreSocioTest.toUpperCase();
        this.idSocio = response.id;
        this.blockSocio = false;
      }
    },
      error => {
        console.log(error);
        this.blockSocio = false;
      }
    );

  }

}
