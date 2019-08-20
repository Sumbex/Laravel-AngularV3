import { Component, OnInit} from '@angular/core';
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
  nombreUpperSocio='';
  /*-----------------------------*/
  idSocio='';

  archivoDocumento:null;

  blockSocio = false;

  constructor(
    private _SociosService:SociosService
  ) { }

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

  onSelectImage(event) {
    this.archivoDocumento = event.srcElement.files[0];
  }

  SubirDocumentoGeneral(){
    if(this.archivoDocumento == null){
    alert('ingrese el documento PDF');
      return false;
    }
    const data = new FormData();
    data.append('socio_id',this.idSocio);
    data.append('archivo',this.archivoDocumento);

    this._SociosService.insertarDatosConyuge(data).subscribe((response) =>{
      if(response.estado == 'failed'){
        alert(response.mensaje);
        return false;
      }
      if(response.estado == 'success'){
       this.archivoDocumento = null;
        alert(response.mensaje);
        return false;
      }
    },
    error => {
      console.log(error);
    }
    );
    
  }

}
