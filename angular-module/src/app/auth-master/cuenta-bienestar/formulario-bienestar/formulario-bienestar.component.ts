import { Component, OnInit } from '@angular/core';
import { SociosService } from 'src/app/servicios/socios.service';
import { ValidarUsuarioService } from 'src/app/servicios/validar-usuario.service';

@Component({
  selector: 'app-formulario-bienestar',
  templateUrl: './formulario-bienestar.component.html',
  styleUrls: ['./formulario-bienestar.component.css']
})
export class FormularioBienestarComponent implements OnInit {
  blockSocio: boolean= false;
  rut: '';
  fechaSocioTest:'';
  rutSocioTest: '';
  idSocio: '';
  nombreSocioTest='';
  nombreUpperSocio='';


  constructor(
    private _SociosService:SociosService,
    private _validarusuario:ValidarUsuarioService) {
                        }
  ngOnInit() {
  }

  buscarSocio(){
    this.blockSocio = true;
    this._SociosService.traerDatosSocio(this.rut).subscribe((response) => {
      console.log(response);
        if(response.estado == "failed"){
          alert('Error, El rut ingresado no existe en nuestra base de datos, pruebe digitando otro rut.');
          this.rut = '';
          this.fechaSocioTest = '';
          this.rutSocioTest = '';
          this.nombreSocioTest = '';
          this.idSocio = '';
          this.blockSocio = false;
          return false;
        }else{
          this.rut = '';
          this.fechaSocioTest = response.fecha_nacimiento;
          this.rutSocioTest = response.rut;
          this.nombreSocioTest = response.nombres +' '+ response.a_paterno +' '+ response.a_materno;
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
