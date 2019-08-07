import { Component, OnInit, Input } from '@angular/core';
import { SociosService } from 'src/app/servicios/socios.service';

@Component({
  selector: 'app-beneficios-socios',
  templateUrl: './beneficios-socios.component.html',
  styleUrls: ['./beneficios-socios.component.css']
})
export class BeneficiosSociosComponent implements OnInit { 

  
  rut: string = '';
  fechaSocioTest='';
  rutSocioTest='';
  nombreSocioTest='';
  /*-----------------------------*/
  idSocio='';
  nombreCompletoSocio='';

  constructor(
    private _SociosService:SociosService
  ) { }

  ngOnInit() {
  }

  buscarSocio(){
    this._SociosService.traerDatosSocio(this.rut).subscribe((response) => {
      console.log(response);
        if(response.estado == "failed"){
          alert('Error, El rut ingresado no existe en nuestra base de datos, pruebe digitando otro rut.');
          this.rut = '';
          this.fechaSocioTest = '';
          this.rutSocioTest = '';
          this.nombreSocioTest = '';
          this.idSocio = '';
          return false;
        }else{
          this.rut = '';
          this.fechaSocioTest = response.fecha_nacimiento;
          this.rutSocioTest = response.rut;
          this.nombreSocioTest = response.nombres +' '+ response.a_paterno +' '+ response.a_materno;
          this.idSocio = response.id;
        }
    },
    error => {
      console.log(error);
    }
    );
    
  }

}
